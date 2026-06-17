<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Mail\OrderConfirmation;
use App\Mail\LowInventoryNotification;
use GuzzleHttp\Client;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        $paymentMethod = $request->input('payment_method', 'paypal');
        $paypalOrderId = $request->input('paypal_order_id');
        $isPaypal = $paymentMethod === 'paypal';
        $isCreditCard = $paymentMethod === 'credit_card';
        $isAch = $paymentMethod === 'ach';
        $isEcheck = $paymentMethod === 'echeck';
        $isBankWire = $paymentMethod === 'bank_wire';

        if ($isAch || $isEcheck) {
            $request->validate([
                'stripe_payment_method_id' => 'required|string',
                'stripe_customer_id' => 'required|string',
            ]);
        }

        if ($isCreditCard) {
            $request->validate([
                'stripe_payment_method_id' => 'required|string',
            ]);
        }

        if ($isPaypal) {
            // Verify PayPal payment
            if (!$paypalOrderId) {
                return response()->json(['error' => 'Missing PayPal order ID'], 422);
            }



            $isPaypalSandbox = env('PAYPAL_SANDBOX'); // Set to false for live

            // Get PayPal access token

            $clientId = $isPaypalSandbox ? env('PAYPAL_SANDBOX_CLIENT_ID') : env('PAYPAL_LIVE_CLIENT_ID');
            $secret = $isPaypalSandbox ? env('PAYPAL_SANDBOX_SECRET_KEY') : env('PAYPAL_LIVE_SECRET_KEY');

            $baseUrl = $isPaypalSandbox ? 'https://api-m.sandbox.paypal.com' : 'https://api-m.paypal.com';
            $accessTokenResponse = \Http::asForm()->withBasicAuth($clientId, $secret)
                ->post($baseUrl . '/v1/oauth2/token', [
                    'grant_type' => 'client_credentials',
                ]);
            if (!$accessTokenResponse->ok()) {
                return response()->json(['error' => 'Could not authenticate with PayPal'], 500);
            }
            $accessToken = $accessTokenResponse->json()['access_token'];

            // Verify order status and get payment source (card vs PayPal wallet)
            $paypalResponse = \Http::withToken($accessToken)
                ->withHeaders(['Prefer' => 'return=representation'])
                ->get($baseUrl . '/v2/checkout/orders/' . $paypalOrderId);

            if (!$paypalResponse->ok()) {
                return response()->json(['error' => 'PayPal order could not be verified'], 422);
            }

            $paypalOrderData = $paypalResponse->json();
            if (($paypalOrderData['status'] ?? '') !== 'COMPLETED') {
                return response()->json(['error' => 'PayPal payment not completed'], 422);
            }

            // Resolve actual payment source for display: card = credit_card, paypal = PayPal wallet
            // Note: $isCreditCard stays false - we never run Authorize.Net for PayPal flow
            $paymentSource = $paypalOrderData['payment_source'] ?? [];
            if (!empty($paymentSource['card'])) {
                $paymentMethod = 'credit_card';
            } elseif (!empty($paymentSource['paypal'])) {
                $paymentMethod = 'paypal';
            } else {
                // Fallback: try paypal_details from client capture response
                $paypalDetails = $request->input('paypal_details');
                if ($paypalDetails) {
                    $details = is_string($paypalDetails) ? json_decode($paypalDetails, true) : $paypalDetails;
                    $detailsPaymentSource = $details['paymentSource'] ?? $details['payment_source'] ?? [];
                    $paymentMethod = !empty($detailsPaymentSource['card']) ? 'credit_card' : 'paypal';
                } else {
                    $paymentMethod = 'paypal';
                }
            }


        }

        $validated = $request->validate([
            'billing_first_name' => 'required',
            'billing_last_name' => 'required',
            'billing_email' => 'required|email',
            'billing_address_1' => 'required',
            'billing_city' => 'required',
            'billing_state' => 'required',
            'billing_postcode' => 'required',
            'billing_country' => 'required',
            'agree_terms' => 'required|accepted',
        ], [
            'agree_terms.required' => 'You must agree to the Terms of Sale and AML policies to complete your order.',
            'agree_terms.accepted' => 'You must agree to the Terms of Sale and AML policies to complete your order.',
        ]);

        // Fraud prevention: only users explicitly approved by an admin may ship to a
        // different address. For all other users the shipping address is forced to
        // match billing so we never silently accept a mismatched address.
        // Always read fresh from DB so admin permission changes take effect immediately
        // without requiring the user to log out and back in.
        $user = Auth::id() ? \App\Models\User::find(Auth::id()) : null;
        $allowDifferentShipping = $user && $user->allow_different_shipping;

        if ($request->input('ship_to_different_address') && !$allowDifferentShipping) {
            // Silently ignore the different-address request and use billing details.
            $request->merge(['ship_to_different_address' => null]);
        }

        $cart = session('cart', []);
        $productIds = collect($cart)->pluck('id')->unique();
        $products = Product::whereIn('id', $productIds)->get()->keyBy('id');


        // Validate inventory before processing order
        foreach ($cart as $item) {
            $product = Product::find($item['id']);
            if ($product && $product->inventory_type === 'limited' && $product->quantity_available !== null) {
                if ($item['quantity'] > $product->quantity_available) {
                    return response()->json([
                        'error' => 'Insufficient stock for ' . $product->name . '. Only ' . $product->quantity_available . ' items available.'
                    ], 422);
                }
            }
        }

        $subtotal = 0;
        $shippableSubtotal = 0; // Only physical / shippable items
        foreach ($cart as $item) {
            $subtotal += $item['price'] * $item['quantity'];

            $product = $products[$item['id']] ?? null;
            $isShippable = false;
            if ($product) {
                // Prefer explicit is_physical flag; fallback to existing is_non_physical
                $isShippable = isset($product->is_physical)
                    ? (bool) $product->is_physical
                    : !$product->is_non_physical;
            }
            if ($isShippable) {
                $shippableSubtotal += $item['price'] * $item['quantity'];
            }
        }
        $shipping_fee = $request->input('shipping_fee', 0);
        $state_fee = $request->input('state_fee', 0);
        $service_fee = $request->input('service_fee', 0);
        $setting = \App\Models\Setting::first();
        $creditCardPercentage = $setting ? $setting->credit_card_percentage : 0;
        $creditCardFee = 0;

        // If cart has ONLY non-physical products, no shipping
        $hasShippableProduct = false;

        foreach ($cart as $item) {
            if (
                isset($products[$item['id']]) &&
                (
                    (isset($products[$item['id']]->is_physical) && $products[$item['id']]->is_physical) ||
                    (!isset($products[$item['id']]->is_physical) && !$products[$item['id']]->is_non_physical)
                )
            ) {
                $hasShippableProduct = true;
                break;
            }
        }

        if (!$hasShippableProduct) {
            $shipping_fee = 0;
        } else {
            // Prorate shipping so only shippable items contribute
            $shippingRatio = $subtotal > 0 ? $shippableSubtotal / $subtotal : 0;
            $shipping_fee = round($shipping_fee * $shippingRatio, 2);
        }



        // Coupon logic
        $appliedCoupon = session('applied_coupon');
        $couponDiscount = 0;
        if ($appliedCoupon) {
            if (!empty($appliedCoupon['free_shipping'])) {
                $shipping_fee = 0;
            }
            if (!empty($appliedCoupon['free_service_fee'])) {
                $service_fee = 0;
            }
            // Percent or dollar discount (applied to subtotal)
            $discountType = $appliedCoupon['discount_type'] ?? null;
            $discountVal = isset($appliedCoupon['discount']) ? (float) $appliedCoupon['discount'] : 0;
            if ($discountType === 'percent' && $discountVal > 0) {
                $couponDiscount = round($subtotal * ($discountVal / 100), 2);
                $couponDiscount = min($couponDiscount, $subtotal);
            } elseif ($discountType === 'dollar' && $discountVal > 0) {
                $couponDiscount = min($discountVal, $subtotal);
                $couponDiscount = round($couponDiscount, 2);
            }
        }

        // Calculate subtotal for gold/silver/platinum products only (for credit card fee calculation)
        $goldSilverSubtotal = 0;
        foreach ($cart as $item) {
            $productType = $item['product_type'] ?? null;
            if (in_array($productType, ['gold', 'silver', 'platinum'])) {
                $goldSilverSubtotal += $item['price'] * $item['quantity'];
            }
        }

        // Apply proportional coupon discount to gold/silver for CC fee (when discount is on full subtotal)
        $goldSilverAfterDiscount = $goldSilverSubtotal;
        if ($couponDiscount > 0 && $subtotal > 0) {
            $discountRatio = $couponDiscount / $subtotal;
            $goldSilverAfterDiscount = round($goldSilverSubtotal * (1 - $discountRatio), 2);
        }

        // Calculate total before credit card fee (for gold/silver/platinum products only)
        $totalBeforeCreditCardFee = $subtotal - $couponDiscount + $shipping_fee + $state_fee + $service_fee;
        $totalBeforeCreditCardFee = round($totalBeforeCreditCardFee, 2);

        // Calculate credit card fee based on gold/silver/platinum products total including all fees
        if ($isCreditCard || $isPaypal) {
            $ccBase = $goldSilverAfterDiscount + $shipping_fee + $state_fee + $service_fee;
            $creditCardFee = round($ccBase * ($creditCardPercentage / 100), 2);
        }

        // Total is subtotal - coupon discount + fees + credit card fee
        $total = $subtotal - $couponDiscount + $shipping_fee + $state_fee + $service_fee + $creditCardFee;
        $total = round($total, 2);

        // Authorize.Net ACH/eCheck or Credit Card payment via direct API
        $transactionId = null;

        if ($isAch || $isEcheck) {
            \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

            try {
                $paymentIntent = \Stripe\PaymentIntent::create([
                    'amount' => (int) round($total * 100),
                    'currency' => 'usd',
                    'customer' => $request->stripe_customer_id,
                    'payment_method' => $request->stripe_payment_method_id,
                    'payment_method_types' => ['us_bank_account'],
                    'confirm' => true,
                    'description' => 'Order – ' . config('app.name'),
                ]);

                $transactionId = $paymentIntent->id;

            } catch (\Stripe\Exception\ApiErrorException $e) {
                Log::error('Stripe ACH/Echeck PaymentIntent failed: ' . $e->getMessage());
                $errKey = $isAch ? 'ach' : 'echeck';
                return response()->json(['errors' => [$errKey => 'Bank payment could not be processed: ' . $e->getMessage()]], 422);
            }
        }

        if ($isCreditCard) {
            \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

            try {
                $paymentIntent = \Stripe\PaymentIntent::create([
                    'amount' => (int) round($total * 100),
                    'currency' => 'usd',
                    'payment_method' => $request->stripe_payment_method_id,
                    'payment_method_types' => ['card'],
                    'confirm' => true,
                    'description' => 'Order – ' . config('app.name'),
                ]);

                $transactionId = $paymentIntent->id;

            } catch (\Stripe\Exception\ApiErrorException $e) {
                Log::error('Stripe Credit Card processing failed: ' . $e->getMessage());
                return response()->json(['errors' => ['credit_card' => 'Payment failed: ' . $e->getMessage()]], 422);
            }
        }

        if ($isBankWire) {
            \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

            try {
                $stripeCustomer = \Stripe\Customer::create([
                    'email' => $request->billing_email,
                    'name' => trim($request->billing_first_name . ' ' . $request->billing_last_name),
                ]);

                $paymentIntent = \Stripe\PaymentIntent::create([
                    'amount' => (int) round($total * 100),
                    'currency' => 'usd',
                    'customer' => $stripeCustomer->id,
                    'payment_method_types' => ['customer_balance'],
                    'payment_method_data' => [
                        'type' => 'customer_balance',
                    ],
                    'payment_method_options' => [
                        'customer_balance' => [
                            'funding_type' => 'bank_transfer',
                            'bank_transfer' => [
                                'type' => 'us_bank_transfer',
                            ],
                        ],
                    ],
                    'confirm' => true,
                    'return_url' => url('/'),
                ], [
                    'expand' => ['next_action.display_bank_transfer_instructions.financial_addresses']
                ]);

                $transactionId = $paymentIntent->id;

                $nextAction = $paymentIntent->next_action;
                $wireDetails = null;
                if ($nextAction && $nextAction->type === 'display_bank_transfer_instructions') {
                    $instructions = $nextAction->display_bank_transfer_instructions;
                    $reference = $instructions->reference ?? null;
                    $financialAddress = $instructions->financial_addresses[0] ?? null;
                    if ($financialAddress && $financialAddress->type === 'aba') {
                        $aba = $financialAddress->aba;
                        $wireDetails = [
                            'bank_name' => $aba->bank_name ?? 'Stripe Virtual Bank',
                            'routing_number' => $aba->routing_number ?? '',
                            'account_number' => $aba->account_number ?? '',
                            'reference' => $reference,
                        ];
                    }
                }

                if (!$wireDetails) {
                    throw new \Exception('Failed to generate virtual bank details from Stripe.');
                }

            } catch (\Exception $e) {
                Log::error('Stripe Bank Wire creation failed: ' . $e->getMessage());
                return response()->json(['errors' => ['bank_wire' => 'Bank wire payment could not be initiated: ' . $e->getMessage()]], 422);
            }
        }

        // Determine whether a separate shipping address should be used.
        // Only allowed when the user has been granted permission by an admin.
        $useShippingAddress = $request->input('ship_to_different_address') && $allowDifferentShipping;

        $order = Order::create([
            'user_id' => \Auth::id(),
            'order_uid' => Order::generateOrderUid(),
            'transaction_id' => $isPaypal ? $paypalOrderId : (($isCreditCard || $isAch || $isEcheck || $isBankWire) ? $transactionId : null),
            'billing_first_name' => $request->billing_first_name,
            'billing_last_name' => $request->billing_last_name,
            'billing_email' => $request->billing_email,
            'billing_phone' => $request->billing_phone,
            'billing_address_1' => $request->billing_address_1,
            'billing_address_2' => $request->billing_address_2,
            'billing_city' => $request->billing_city,
            'billing_state' => $request->billing_state,
            'billing_postcode' => $request->billing_postcode,
            'billing_country' => $request->billing_country,
            // Shipping address: use separate address only if user is approved, otherwise mirror billing
            'shipping_first_name' => $useShippingAddress ? $request->shipping_first_name : $request->billing_first_name,
            'shipping_last_name' => $useShippingAddress ? $request->shipping_last_name : $request->billing_last_name,
            'shipping_company' => $useShippingAddress ? $request->shipping_company : null,
            'shipping_address_1' => $useShippingAddress ? $request->shipping_address_1 : $request->billing_address_1,
            'shipping_address_2' => $useShippingAddress ? $request->shipping_address_2 : $request->billing_address_2,
            'shipping_city' => $useShippingAddress ? $request->shipping_city : $request->billing_city,
            'shipping_state' => $useShippingAddress ? $request->shipping_state : $request->billing_state,
            'shipping_postcode' => $useShippingAddress ? $request->shipping_postcode : $request->billing_postcode,
            'shipping_country' => $useShippingAddress ? $request->shipping_country : $request->billing_country,
            'order_comments' => $request->order_comments,
            'subtotal' => $subtotal,
            'shipping_fee' => $shipping_fee,
            'state_fee' => $state_fee,
            'service_fee' => $service_fee,
            'credit_card_fee' => $creditCardFee,
            'credit_card_percentage' => $creditCardPercentage,
            'total' => $total,
            'payment_method' => $paymentMethod,
            'status' => ($isPaypal || $isCreditCard) ? 'paid' : (($isAch || $isEcheck || $isBankWire) ? 'ach_pending' : 'pending'),
            'coupon_code' => $couponDiscount > 0 ? ($appliedCoupon['code'] ?? null) : null,
            'coupon_discount' => $couponDiscount,
            'coupon_description' => $couponDiscount > 0 ? ($appliedCoupon['description'] ?? null) : null,
            'stripe_bank_name' => ($isAch || $isEcheck) ? $request->stripe_bank_name : ($isBankWire ? json_encode($wireDetails) : null),
            'stripe_account_mask' => ($isAch || $isEcheck) ? $request->stripe_account_mask : null,
            'stripe_payment_method_id' => ($isAch || $isEcheck || $isCreditCard) ? $request->stripe_payment_method_id : null,
        ]);

        foreach ($cart as $item) {
            $order->items()->create([
                'product_id' => $item['id'],
                'name' => $item['name'],
                'price' => $item['price'],
                'quantity' => $item['quantity'],
                'image' => $item['image'] ?? null,
            ]);

            // Decrement inventory for limited inventory products
            $product = Product::find($item['id']);
            if ($product && $product->inventory_type === 'limited' && $product->quantity_available !== null) {
                $product->quantity_available = max(0, $product->quantity_available - $item['quantity']);
                $product->save();

                // Reload product to get fresh data
                $product->refresh();

                // Check if inventory is at or below threshold and send notification
                if (
                    $product->low_inventory_threshold !== null &&
                    $product->quantity_available <= $product->low_inventory_threshold
                ) {
                    try {
                        $adminEmail = config('mail.admin_email', env('MAIL_ADMIN_EMAIL', 'info@oasismint.com'));
                        if ($adminEmail && filter_var($adminEmail, FILTER_VALIDATE_EMAIL)) {
                            \Mail::to($adminEmail)->send(new LowInventoryNotification(
                                $product,
                                $product->quantity_available,
                                $product->low_inventory_threshold
                            ));
                            Log::info('Low Inventory Notification sent for product ID: ' . $product->id . ' (Qty: ' . $product->quantity_available . ', Threshold: ' . $product->low_inventory_threshold . ') to ' . $adminEmail);
                        } else {
                            Log::warning('Low Inventory Notification not sent: Invalid admin email configured: ' . ($adminEmail ?? 'null'));
                        }
                    } catch (\Exception $e) {
                        // Log error but don't fail the order
                        Log::error('Low Inventory Notification Error for product ID ' . $product->id . ': ' . $e->getMessage());
                        Log::error('Low Inventory Notification Error Trace: ' . $e->getTraceAsString());
                    }
                }
            }
        }

        // Send order confirmation email
        $order->load('items');
        try {
            \Mail::to($order->billing_email)->send(new OrderConfirmation($order));
        } catch (\Exception $e) {
            Log::error('Order confirmation email failed: ' . $e->getMessage(), ['order_id' => $order->id, 'trace' => $e->getTraceAsString()]);
        }

        // NEW: Send copy to ADMIN
        // try {
        //     $adminEmail = env('MAIL_ADMIN_EMAIL', 'info@oasismint.com');
        //     if ($adminEmail && filter_var($adminEmail, FILTER_VALIDATE_EMAIL)) {
        //         \Mail::to($adminEmail)->send(new OrderConfirmation($order));  // Same email, or create AdminOrderNotification
        //     }
        // } catch (\Exception $e) {
        //     Log::error('Failed to send admin order notification: ' . $e->getMessage());
        // }
        // Send ADMIN notification 
        try {
            $adminEmail = config('mail.admin_email', env('MAIL_ADMIN_EMAIL', 'info@oasismint.com'));

            if ($adminEmail && filter_var($adminEmail, FILTER_VALIDATE_EMAIL)) {

                $isHighValue = $order->total > 5000;
                $isDifferentShipping = $order->billing_address_1 !== $order->shipping_address_1;
                $itemCount = $order->items->sum('quantity');

                // Build items table rows
                $itemsHtml = '';
                foreach ($order->items as $item) {
                    $itemsHtml .= "
                        <tr>
                            <td style='padding:8px;border:1px solid #ddd;'>{$item->name}</td>
                            <td style='padding:8px;border:1px solid #ddd;text-align:center;'>{$item->quantity}</td>
                            <td style='padding:8px;border:1px solid #ddd;text-align:right;'>$" . number_format($item->price, 2) . "</td>
                            <td style='padding:8px;border:1px solid #ddd;text-align:right;'>$" . number_format($item->price * $item->quantity, 2) . "</td>
                        </tr>
                    ";
                }

                // Alert boxes
                $alerts = '';
                if ($isHighValue) {
                    $alerts .= "<div style='background:#fff3cd;border:1px solid #ffc107;padding:10px;margin:10px 0;border-radius:4px;'>⚠️ <strong>HIGH VALUE ORDER:</strong> $" . number_format($order->total, 2) . "</div>";
                }
                if ($isDifferentShipping) {
                    $alerts .= "<div style='background:#fff3cd;border:1px solid #ffc107;padding:10px;margin:10px 0;border-radius:4px;'>📍 <strong>Different Shipping Address</strong> - Verify user permission</div>";
                }

                $userType = $order->user_id
                    ? "<span style='color:green;'>✓ Registered User (ID: {$order->user_id})</span>"
                    : "<span style='color:orange;'>⚠️ Guest Checkout</span>";

                if ($order->status === 'paid') {
                    $paymentStatus = "<span style='color:green;'>PAID</span>";
                } elseif ($order->status === 'ach_pending') {
                    $paymentStatus = "<span style='color:#2563eb;'>ACH PROCESSING</span>";
                } else {
                    $paymentStatus = "<span style='color:orange;'>PENDING</span>";
                }

                $html = "
                <!DOCTYPE html>
                <html>
                <head>
                    <meta charset='UTF-8'>
                </head>
                <body style='font-family:Arial,sans-serif;line-height:1.6;color:#333;'>
                    <h2>🛒 New Order Received - #{$order->id}</h2>
                    
                    {$alerts}
                    
                    <p>
                        <a href='" . route('admin.orders.show', $order->id) . "' 
                        style='background:#007bff;color:white;padding:12px 24px;text-decoration:none;border-radius:4px;display:inline-block;margin:10px 0;'>
                        View & Process Order in Admin
                        </a>
                    </p>
                    
                    <div style='background:#f8f9fa;padding:15px;margin:10px 0;border-radius:4px;'>
                        <h3 style='margin-top:0;'>Order Summary</h3>
                        <table style='width:100%;'>
                            <tr><td><strong>Order ID:</strong></td><td>#{$order->id}</td></tr>
                            <tr><td><strong>Placed:</strong></td><td>{$order->created_at->format('M d, Y H:i')} ({$order->created_at->diffForHumans()})</td></tr>
                            <tr><td><strong>Payment:</strong></td><td>" . strtoupper($order->payment_method) . " - {$paymentStatus}</td></tr>
                            <tr><td><strong>Total:</strong></td><td><strong>$" . number_format($order->total, 2) . "</strong></td></tr>
                        </table>
                    </div>
                    
                    <div style='background:#f8f9fa;padding:15px;margin:10px 0;border-radius:4px;'>
                        <h3 style='margin-top:0;'>Customer</h3>
                        <p>
                            <strong>{$order->billing_first_name} {$order->billing_last_name}</strong><br>
                            📧 {$order->billing_email}<br>
                            📱 " . ($order->billing_phone ?: 'N/A') . "<br>
                            {$userType}
                        </p>
                    </div>
                    
                    <div style='background:#f8f9fa;padding:15px;margin:10px 0;border-radius:4px;'>
                        <h3 style='margin-top:0;'>Shipping Address</h3>
                        <p>
                            {$order->shipping_first_name} {$order->shipping_last_name}<br>
                            {$order->shipping_address_1}<br>
                            " . ($order->shipping_address_2 ? $order->shipping_address_2 . '<br>' : '') . "
                            {$order->shipping_city}, {$order->shipping_state} {$order->shipping_postcode}<br>
                            {$order->shipping_country}
                        </p>
                    </div>
                    
                    <h3>Items ({$itemCount} total)</h3>
                    <table style='width:100%;border-collapse:collapse;font-size:14px;'>
                        <thead style='background:#e9ecef;'>
                            <tr>
                                <th style='padding:8px;border:1px solid #ddd;text-align:left;'>Product</th>
                                <th style='padding:8px;border:1px solid #ddd;text-align:center;'>Qty</th>
                                <th style='padding:8px;border:1px solid #ddd;text-align:right;'>Price</th>
                                <th style='padding:8px;border:1px solid #ddd;text-align:right;'>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            {$itemsHtml}
                        </tbody>
                    </table>
                    
                    <h3>Financial Breakdown</h3>
                    <table style='width:300px;'>
                        <tr><td>Subtotal:</td><td style='text-align:right;'>$" . number_format($order->subtotal, 2) . "</td></tr>
                        " . (($order->coupon_discount ?? 0) > 0 ? "<tr><td style='color:#16a34a;'>Coupon Discount (" . e($order->coupon_code) . ($order->coupon_description ? ' – ' . e($order->coupon_description) : '') . "):</td><td style='text-align:right;color:#16a34a;'>-$" . number_format($order->coupon_discount, 2) . "</td></tr>" : '') . "
                        <tr><td>Shipping:</td><td style='text-align:right;'>$" . number_format($order->shipping_fee, 2) . "</td></tr>
                        " . ($order->state_fee > 0 ? "<tr><td>State Fee:</td><td style='text-align:right;'>$" . number_format($order->state_fee, 2) . "</td></tr>" : '') . "
                        " . ($order->service_fee > 0 ? "<tr><td>Service Fee:</td><td style='text-align:right;'>$" . number_format($order->service_fee, 2) . "</td></tr>" : '') . "
                        " . ($order->credit_card_fee > 0 ? "<tr><td>CC Fee ({$order->credit_card_percentage}%):</td><td style='text-align:right;'>$" . number_format($order->credit_card_fee, 2) . "</td></tr>" : '') . "
                        <tr style='font-weight:bold;font-size:16px;border-top:2px solid #333;'>
                            <td style='padding-top:10px;'>TOTAL:</td>
                            <td style='text-align:right;padding-top:10px;'>$" . number_format($order->total, 2) . "</td>
                        </tr>
                    </table>
                    
                    <hr style='margin:20px 0;'>
                    <p style='font-size:12px;color:#666;'>
                        Automated notification from " . config('app.name') . " | " . now() . "
                    </p>
                </body>
                </html>
                ";

                \Mail::html($html, function ($message) use ($order, $adminEmail) {
                    $message->to($adminEmail)
                        ->subject("🔔 NEW ORDER #{$order->id} - {$order->billing_email}")
                        ->priority(1);
                });

                Log::info('Admin notification sent for order: ' . $order->id);
            }
        } catch (\Exception $e) {
            Log::error('Failed to send admin order notification: ' . $e->getMessage());
        }

        session()->forget('cart');
        session()->forget('applied_coupon');

        return response()->json(['redirect_url' => route('order.confirmation', $order->id)]);

    }

    public function confirmation($orderId)
    {
        $order = Order::with('items')->findOrFail($orderId);
        return view('order-confirmation', compact('order'));
    }

    public function validateCoupon(Request $request)
    {
        $code = $request->input('coupon_code');
        if (!$code) {
            session()->forget('applied_coupon');
            return response()->json(['valid' => false, 'message' => 'No coupon code provided.']);
        }
        $coupon = \App\Models\Coupon::where('code', $code)
            ->where('is_active', true)
            ->where(function ($q) {
                $today = date('Y-m-d');
                $q->whereNull('valid_from')->orWhere('valid_from', '<=', $today);
            })
            ->where(function ($q) {
                $today = date('Y-m-d');
                $q->whereNull('valid_to')->orWhere('valid_to', '>=', $today);
            })
            ->first();
        if (!$coupon) {
            return response()->json(['valid' => false, 'message' => 'Invalid or expired coupon.']);
        }
        // Store coupon in session for use on order
        $sessionData = $coupon->only(['id', 'code', 'description', 'free_shipping', 'free_service_fee', 'discount', 'discount_type']);
        session(['applied_coupon' => $sessionData]);
        return response()->json([
            'valid' => true,
            'free_shipping' => (bool) $coupon->free_shipping,
            'free_service_fee' => (bool) $coupon->free_service_fee,
            'discount' => $coupon->discount ? (float) $coupon->discount : null,
            'discount_type' => $coupon->discount_type,
            'description' => $coupon->description,
            'code' => $coupon->code,
        ]);
    }
}