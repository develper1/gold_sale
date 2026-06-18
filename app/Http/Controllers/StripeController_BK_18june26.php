<?php

namespace App\Http\Controllers;

use App\Mail\AchPaymentFailedNotification;
use App\Mail\PaymentReceivedNotification;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class StripeController extends Controller
{
    /**
     * Create a Stripe SetupIntent for ACH bank account linking.
     * No amount is needed at this stage — the PaymentIntent is created
     * later during order submission with the final calculated total.
     */
    public function createSetupIntent(Request $request)
    {
        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

        try {
            $user = $request->user();

            $customer = \Stripe\Customer::create([
                'email' => $user->email,
                'name'  => $user->name,
            ]);

            $setupIntent = \Stripe\SetupIntent::create([
                'customer'             => $customer->id,
                'payment_method_types' => ['us_bank_account'],
                'payment_method_options' => [
                    'us_bank_account' => [
                        'financial_connections' => [
                            'permissions' => ['payment_method'],
                        ],
                    ],
                ],
            ]);

            return response()->json([
                'client_secret' => $setupIntent->client_secret,
                'customer_id'   => $customer->id,
            ]);

        } catch (\Stripe\Exception\ApiErrorException $e) {
            Log::error('Stripe SetupIntent creation failed: ' . $e->getMessage());
            return response()->json(['error' => 'Could not initialize bank linking. Please try again.'], 500);
        }
    }

    /**
     * Handle incoming Stripe webhook events.
     * This route is CSRF-exempt (see VerifyCsrfToken.php) and outside auth middleware.
     */
    public function handleWebhook(Request $request)
    {
        $payload   = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');
        $secret    = env('STRIPE_WEBHOOK_SECRET');

        try {
            $event = \Stripe\Webhook::constructEvent($payload, $sigHeader, $secret);
        } catch (\UnexpectedValueException $e) {
            Log::error('Stripe webhook invalid payload: ' . $e->getMessage());
            return response()->json(['error' => 'Invalid payload'], 400);
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            Log::error('Stripe webhook signature verification failed: ' . $e->getMessage());
            return response()->json(['error' => 'Invalid signature'], 400);
        }

        $paymentIntent = $event->data->object;

        match ($event->type) {
            'payment_intent.succeeded'       => $this->onPaymentSucceeded($paymentIntent),
            'payment_intent.payment_failed'  => $this->onPaymentFailed($paymentIntent),
            default                          => null,
        };

        return response()->json(['status' => 'ok']);
    }

    private function onPaymentSucceeded($paymentIntent): void
    {
        $order = Order::where('transaction_id', $paymentIntent->id)->first();

        if (!$order || $order->status !== 'ach_pending') {
            return;
        }

        $order->status = 'paid';
        $order->save();

        // Email customer
        try {
            Mail::to($order->billing_email)->send(new PaymentReceivedNotification($order));
        } catch (\Exception $e) {
            Log::error('ACH settled customer email failed for order ' . $order->id . ': ' . $e->getMessage());
        }

        // Email admin
        try {
            $adminEmail = env('MAIL_ADMIN_EMAIL', 'info@oasismint.com');
            if ($adminEmail && filter_var($adminEmail, FILTER_VALIDATE_EMAIL)) {
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
                $html = "
                <!DOCTYPE html><html><head><meta charset='UTF-8'></head>
                <body style='font-family:Arial,sans-serif;line-height:1.6;color:#333;'>
                    <div style='background:#d1fae5;border:1px solid #34d399;padding:15px;border-radius:4px;margin-bottom:15px;'>
                        <h2 style='margin:0;color:#065f46;'>✅ ACH Payment Settled – Order #{$order->id}</h2>
                        <p style='margin:6px 0 0;color:#065f46;'>The bank debit has cleared. Order is now <strong>Paid</strong>.</p>
                    </div>
                    <p>
                        <a href='" . route('admin.orders.show', $order->id) . "'
                        style='background:#007bff;color:white;padding:12px 24px;text-decoration:none;border-radius:4px;display:inline-block;margin:10px 0;'>
                        View Order in Admin
                        </a>
                    </p>
                    <div style='background:#f8f9fa;padding:15px;margin:10px 0;border-radius:4px;'>
                        <h3 style='margin-top:0;'>Order Summary</h3>
                        <table style='width:100%;'>
                            <tr><td><strong>Order ID:</strong></td><td>#{$order->id}</td></tr>
                            <tr><td><strong>Payment:</strong></td><td>ACH Bank Transfer – <span style='color:green;'>SETTLED</span></td></tr>
                            <tr><td><strong>Total:</strong></td><td><strong>$" . number_format($order->total, 2) . "</strong></td></tr>
                        </table>
                    </div>
                    <div style='background:#f8f9fa;padding:15px;margin:10px 0;border-radius:4px;'>
                        <h3 style='margin-top:0;'>Customer</h3>
                        <p>
                            <strong>{$order->billing_first_name} {$order->billing_last_name}</strong><br>
                            📧 {$order->billing_email}<br>
                            📱 " . ($order->billing_phone ?: 'N/A') . "
                        </p>
                    </div>
                    <h3>Items</h3>
                    <table style='width:100%;border-collapse:collapse;font-size:14px;'>
                        <thead style='background:#e9ecef;'>
                            <tr>
                                <th style='padding:8px;border:1px solid #ddd;text-align:left;'>Product</th>
                                <th style='padding:8px;border:1px solid #ddd;text-align:center;'>Qty</th>
                                <th style='padding:8px;border:1px solid #ddd;text-align:right;'>Price</th>
                                <th style='padding:8px;border:1px solid #ddd;text-align:right;'>Total</th>
                            </tr>
                        </thead>
                        <tbody>{$itemsHtml}</tbody>
                    </table>
                    <h3>Financial Breakdown</h3>
                    <table style='width:300px;'>
                        <tr><td>Subtotal:</td><td style='text-align:right;'>$" . number_format($order->subtotal, 2) . "</td></tr>
                        <tr><td>Shipping:</td><td style='text-align:right;'>$" . number_format($order->shipping_fee, 2) . "</td></tr>
                        " . ($order->state_fee > 0 ? "<tr><td>State Fee:</td><td style='text-align:right;'>$" . number_format($order->state_fee, 2) . "</td></tr>" : '') . "
                        " . ($order->service_fee > 0 ? "<tr><td>Service Fee:</td><td style='text-align:right;'>$" . number_format($order->service_fee, 2) . "</td></tr>" : '') . "
                        <tr style='font-weight:bold;font-size:16px;border-top:2px solid #333;'>
                            <td style='padding-top:10px;'>TOTAL:</td>
                            <td style='text-align:right;padding-top:10px;'>$" . number_format($order->total, 2) . "</td>
                        </tr>
                    </table>
                    <hr style='margin:20px 0;'>
                    <p style='font-size:12px;color:#666;'>Automated notification from " . config('app.name') . " | " . now() . "</p>
                </body></html>
                ";
                Mail::html($html, function ($message) use ($order, $adminEmail) {
                    $message->to($adminEmail)
                        ->subject("✅ ACH Payment Settled – Order #{$order->id} – {$order->billing_email}");
                });
            }
        } catch (\Exception $e) {
            Log::error('ACH settled admin email failed for order ' . $order->id . ': ' . $e->getMessage());
        }

        Log::info('ACH payment settled for order ' . $order->id);
    }

    private function onPaymentFailed($paymentIntent): void
    {
        $order = Order::where('transaction_id', $paymentIntent->id)->first();

        if (!$order || $order->status !== 'ach_pending') {
            return;
        }

        $order->status = 'ach_failed';
        $order->save();

        // Restore inventory for limited-stock products
        foreach ($order->items as $item) {
            $product = Product::find($item->product_id);
            if ($product && $product->inventory_type === 'limited' && $product->quantity_available !== null) {
                $product->quantity_available += $item->quantity;
                $product->save();
            }
        }

        // Email customer
        try {
            Mail::to($order->billing_email)->send(new AchPaymentFailedNotification($order));
        } catch (\Exception $e) {
            Log::error('ACH failed customer email failed for order ' . $order->id . ': ' . $e->getMessage());
        }

        // Email admin
        try {
            $adminEmail = env('MAIL_ADMIN_EMAIL', 'info@oasismint.com');
            if ($adminEmail && filter_var($adminEmail, FILTER_VALIDATE_EMAIL)) {
                $failReason = $paymentIntent->last_payment_error->message ?? 'Unknown reason';
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
                $html = "
                <!DOCTYPE html><html><head><meta charset='UTF-8'></head>
                <body style='font-family:Arial,sans-serif;line-height:1.6;color:#333;'>
                    <div style='background:#fee2e2;border:1px solid #f87171;padding:15px;border-radius:4px;margin-bottom:15px;'>
                        <h2 style='margin:0;color:#991b1b;'>❌ ACH Payment Failed – Order #{$order->id}</h2>
                        <p style='margin:6px 0 0;color:#991b1b;'>The bank debit was rejected. Inventory has been restored.</p>
                    </div>
                    <div style='background:#fff3cd;border:1px solid #ffc107;padding:10px;margin:10px 0;border-radius:4px;'>
                        ⚠️ <strong>Failure Reason:</strong> {$failReason}
                    </div>
                    <p>
                        <a href='" . route('admin.orders.show', $order->id) . "'
                        style='background:#dc3545;color:white;padding:12px 24px;text-decoration:none;border-radius:4px;display:inline-block;margin:10px 0;'>
                        View Order in Admin
                        </a>
                    </p>
                    <div style='background:#f8f9fa;padding:15px;margin:10px 0;border-radius:4px;'>
                        <h3 style='margin-top:0;'>Order Summary</h3>
                        <table style='width:100%;'>
                            <tr><td><strong>Order ID:</strong></td><td>#{$order->id}</td></tr>
                            <tr><td><strong>Payment:</strong></td><td>ACH Bank Transfer – <span style='color:#dc3545;'>FAILED</span></td></tr>
                            <tr><td><strong>Total:</strong></td><td><strong>$" . number_format($order->total, 2) . "</strong></td></tr>
                        </table>
                    </div>
                    <div style='background:#f8f9fa;padding:15px;margin:10px 0;border-radius:4px;'>
                        <h3 style='margin-top:0;'>Customer</h3>
                        <p>
                            <strong>{$order->billing_first_name} {$order->billing_last_name}</strong><br>
                            📧 {$order->billing_email}<br>
                            📱 " . ($order->billing_phone ?: 'N/A') . "
                        </p>
                    </div>
                    <h3>Items</h3>
                    <table style='width:100%;border-collapse:collapse;font-size:14px;'>
                        <thead style='background:#e9ecef;'>
                            <tr>
                                <th style='padding:8px;border:1px solid #ddd;text-align:left;'>Product</th>
                                <th style='padding:8px;border:1px solid #ddd;text-align:center;'>Qty</th>
                                <th style='padding:8px;border:1px solid #ddd;text-align:right;'>Price</th>
                                <th style='padding:8px;border:1px solid #ddd;text-align:right;'>Total</th>
                            </tr>
                        </thead>
                        <tbody>{$itemsHtml}</tbody>
                    </table>
                    <h3>Financial Breakdown</h3>
                    <table style='width:300px;'>
                        <tr><td>Subtotal:</td><td style='text-align:right;'>$" . number_format($order->subtotal, 2) . "</td></tr>
                        <tr><td>Shipping:</td><td style='text-align:right;'>$" . number_format($order->shipping_fee, 2) . "</td></tr>
                        " . ($order->state_fee > 0 ? "<tr><td>State Fee:</td><td style='text-align:right;'>$" . number_format($order->state_fee, 2) . "</td></tr>" : '') . "
                        " . ($order->service_fee > 0 ? "<tr><td>Service Fee:</td><td style='text-align:right;'>$" . number_format($order->service_fee, 2) . "</td></tr>" : '') . "
                        <tr style='font-weight:bold;font-size:16px;border-top:2px solid #333;'>
                            <td style='padding-top:10px;'>TOTAL:</td>
                            <td style='text-align:right;padding-top:10px;'>$" . number_format($order->total, 2) . "</td>
                        </tr>
                    </table>
                    <hr style='margin:20px 0;'>
                    <p style='font-size:12px;color:#666;'>Automated notification from " . config('app.name') . " | " . now() . "</p>
                </body></html>
                ";
                Mail::html($html, function ($message) use ($order, $adminEmail) {
                    $message->to($adminEmail)
                        ->subject("❌ ACH Payment Failed – Order #{$order->id} – {$order->billing_email}");
                });
            }
        } catch (\Exception $e) {
            Log::error('ACH failed admin email failed for order ' . $order->id . ': ' . $e->getMessage());
        }

        Log::info('ACH payment failed for order ' . $order->id);
    }
}
