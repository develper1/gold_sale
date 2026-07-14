<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Services\ExpressCheckoutService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

/**
 * Stripe "express" wallet checkout flow (Amazon Pay, Link, Apple Pay).
 *
 * These wallets are redirect/popup based and therefore cannot use the
 * synchronous charge path in OrderController::store(). Instead:
 *   1. createIntent(): validate, create a `payment_pending` order (no inventory
 *      decrement) and a Stripe PaymentIntent whose payment_method_types come
 *      from the wallet registry (config/express_checkout.php), return the
 *      client secret.
 *   2. The browser confirms client-side (Express Checkout Element) which
 *      redirects the buyer to the wallet and back to handleReturn().
 *   3. handleReturn() (and, as a safety net, the Stripe webhook) finalise the
 *      order via ExpressCheckoutService.
 *
 * All non-wallet payment methods (card, ACH, eCheck, bank wire, PayPal) are
 * untouched by this controller.
 */
class ExpressCheckoutController extends Controller
{
    public function __construct(private ExpressCheckoutService $expressCheckoutService)
    {
    }

    /**
     * Create the pending order + wallet PaymentIntent.
     */
    public function createIntent(Request $request)
    {
        $request->validate([
            'express_type' => 'required|string',
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

        $expressType = $request->input('express_type');
        $method = config("express_checkout.methods.$expressType");

        // Only accept wallets that exist in the registry and are enabled.
        if (!$method || !($method['enabled'] ?? false)) {
            return response()->json(['error' => 'This payment option is not available.'], 422);
        }

        $cart = session('cart', []);
        if (empty($cart)) {
            return response()->json(['error' => 'Your cart is empty.'], 422);
        }

        $productIds = collect($cart)->pluck('id')->unique();
        $products = Product::whereIn('id', $productIds)->get()->keyBy('id');

        // Inventory availability check (does NOT decrement — that happens on success)
        foreach ($cart as $item) {
            $product = $products[$item['id']] ?? null;
            if ($product && $product->inventory_type === 'limited' && $product->quantity_available !== null) {
                if ($item['quantity'] > $product->quantity_available) {
                    return response()->json([
                        'error' => 'Insufficient stock for ' . $product->name . '. Only ' . $product->quantity_available . ' items available.',
                    ], 422);
                }
            }
        }

        $totals = $this->computeTotals($request, $cart, $products, (bool) ($method['surcharge'] ?? false));

        // Determine shipping address (only approved users may ship elsewhere).
        $user = Auth::id() ? \App\Models\User::find(Auth::id()) : null;
        $allowDifferentShipping = $user && $user->allow_different_shipping;
        $useShippingAddress = $request->input('ship_to_different_address') && $allowDifferentShipping;

        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

        $order = null;

        try {
            $order = Order::create([
                'user_id' => Auth::id(),
                'order_uid' => Order::generateOrderUid(),
                'transaction_id' => null,
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
                'subtotal' => $totals['subtotal'],
                'shipping_fee' => $totals['shipping_fee'],
                'state_fee' => $totals['state_fee'],
                'service_fee' => $totals['service_fee'],
                'credit_card_fee' => $totals['credit_card_fee'],
                'credit_card_percentage' => $totals['credit_card_percentage'],
                'total' => $totals['total'],
                'payment_method' => $expressType,
                'status' => 'payment_pending',
                'coupon_code' => $totals['coupon_discount'] > 0 ? ($totals['applied_coupon']['code'] ?? null) : null,
                'coupon_discount' => $totals['coupon_discount'],
                'coupon_description' => $totals['coupon_discount'] > 0 ? ($totals['applied_coupon']['description'] ?? null) : null,
            ]);

            // Order items — inventory is NOT decremented here (only on success).
            foreach ($cart as $item) {
                $order->items()->create([
                    'product_id' => $item['id'],
                    'name' => $item['name'],
                    'price' => $item['price'],
                    'quantity' => $item['quantity'],
                    'image' => $item['image'] ?? null,
                ]);
            }

            $paymentIntent = \Stripe\PaymentIntent::create([
                'amount' => (int) round($totals['total'] * 100),
                'currency' => 'usd',
                'payment_method_types' => $method['payment_method_types'],
                'description' => 'Order – ' . config('app.name'),
                'metadata' => [
                    'order_id' => $order->id,
                    'order_uid' => $order->order_uid,
                    'express_type' => $expressType,
                ],
            ]);

            $order->transaction_id = $paymentIntent->id;
            $order->save();

            return response()->json([
                'client_secret' => $paymentIntent->client_secret,
                'return_url' => route('express.return'),
                'order_id' => $order->id,
            ]);
        } catch (\Stripe\Exception\ApiErrorException $e) {
            Log::error('Express PaymentIntent creation failed (' . $expressType . '): ' . $e->getMessage());
            if ($order && !$order->transaction_id) {
                $order->items()->delete();
                $order->delete();
            }
            return response()->json(['error' => 'Could not initialise payment. Please try again.'], 500);
        } catch (\Exception $e) {
            Log::error('Express order creation failed (' . $expressType . '): ' . $e->getMessage());
            if ($order && !$order->transaction_id) {
                $order->items()->delete();
                $order->delete();
            }
            return response()->json(['error' => 'Could not start your order. Please try again.'], 500);
        }
    }

    /**
     * Handle the redirect back from the wallet.
     */
    public function handleReturn(Request $request)
    {
        $paymentIntentId = $request->query('payment_intent');

        if (!$paymentIntentId) {
            return redirect()->route('checkout')->with('error', 'Payment was not completed. Please try again.');
        }

        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

        try {
            $paymentIntent = \Stripe\PaymentIntent::retrieve($paymentIntentId);
        } catch (\Stripe\Exception\ApiErrorException $e) {
            Log::error('Express return: could not retrieve PaymentIntent ' . $paymentIntentId . ': ' . $e->getMessage());
            return redirect()->route('checkout')->with('error', 'We could not verify your payment. Please contact support if you were charged.');
        }

        $order = Order::where('transaction_id', $paymentIntentId)->first();

        if (!$order) {
            Log::error('Express return: no order for PaymentIntent ' . $paymentIntentId);
            return redirect()->route('checkout')->with('error', 'We could not locate your order. Please contact support if you were charged.');
        }

        switch ($paymentIntent->status) {
            case 'succeeded':
                $this->expressCheckoutService->finalizeSucceededOrder($order);
                session()->forget('cart');
                session()->forget('applied_coupon');
                return redirect()->route('order.confirmation', $order->id);

            case 'processing':
                // Payment is still settling — the webhook will finalise it.
                session()->forget('cart');
                session()->forget('applied_coupon');
                return redirect()->route('order.confirmation', $order->id)
                    ->with('info', 'Your payment is processing. We will email you once it is confirmed.');

            default:
                // requires_payment_method / canceled / requires_action, etc.
                $this->expressCheckoutService->markFailed($order, 'Wallet returned status: ' . $paymentIntent->status);
                return redirect()->route('checkout')->with('error', 'Your payment was not completed. Please try again or choose another payment method.');
        }
    }

    /**
     * Compute order totals — mirrors OrderController::store() exactly. The
     * processing (credit-card) surcharge is applied when the wallet's registry
     * entry has surcharge => true.
     */
    private function computeTotals(Request $request, array $cart, $products, bool $applySurcharge): array
    {
        $subtotal = 0;
        $shippableSubtotal = 0;
        foreach ($cart as $item) {
            $subtotal += $item['price'] * $item['quantity'];

            $product = $products[$item['id']] ?? null;
            $isShippable = false;
            if ($product) {
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

        // Gold/silver/platinum subtotal for surcharge base
        $goldSilverSubtotal = 0;
        foreach ($cart as $item) {
            $productType = $item['product_type'] ?? null;
            if (in_array($productType, ['gold', 'silver', 'platinum'])) {
                $goldSilverSubtotal += $item['price'] * $item['quantity'];
            }
        }

        $goldSilverAfterDiscount = $goldSilverSubtotal;
        if ($couponDiscount > 0 && $subtotal > 0) {
            $discountRatio = $couponDiscount / $subtotal;
            $goldSilverAfterDiscount = round($goldSilverSubtotal * (1 - $discountRatio), 2);
        }

        if ($applySurcharge) {
            $ccBase = $goldSilverAfterDiscount + $shipping_fee + $state_fee + $service_fee;
            $creditCardFee = round($ccBase * ($creditCardPercentage / 100), 2);
        }

        $total = $subtotal - $couponDiscount + $shipping_fee + $state_fee + $service_fee + $creditCardFee;
        $total = round($total, 2);

        return [
            'subtotal' => $subtotal,
            'shipping_fee' => $shipping_fee,
            'state_fee' => $state_fee,
            'service_fee' => $service_fee,
            'credit_card_fee' => $creditCardFee,
            'credit_card_percentage' => $creditCardPercentage,
            'coupon_discount' => $couponDiscount,
            'applied_coupon' => $appliedCoupon,
            'total' => $total,
        ];
    }
}
