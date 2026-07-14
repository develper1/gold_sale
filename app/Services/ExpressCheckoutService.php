<?php

namespace App\Services;

use App\Mail\LowInventoryNotification;
use App\Mail\OrderConfirmation;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

/**
 * Finalisation for Stripe "express" wallet orders (Amazon Pay, Link, Apple Pay).
 *
 * These wallets are redirect/popup based and cannot use the synchronous charge
 * path in OrderController::store(). The order is created up-front as
 * `payment_pending` (see ExpressCheckoutController::createIntent) WITHOUT
 * decrementing inventory. Inventory is only reduced once payment actually
 * succeeds — either when the buyer returns to our return URL, or when the
 * Stripe webhook fires — whichever happens first. Both paths call
 * finalizeSucceededOrder(), which is idempotent so it can safely run twice.
 */
class ExpressCheckoutService
{
    /**
     * Human-friendly label for a wallet, from the registry.
     */
    public static function label(string $method): string
    {
        return config("express_checkout.methods.$method.label")
            ?? ucwords(str_replace('_', ' ', $method));
    }

    /**
     * Finalise a successful express-wallet order.
     *
     * Idempotent: only acts when the order is still `payment_pending`, so the
     * return route and the webhook cannot both apply the changes.
     *
     * @return bool true if this call performed the finalisation, false if it was a no-op.
     */
    public function finalizeSucceededOrder(Order $order): bool
    {
        // Re-read the freshest state to avoid a return/webhook race.
        $order->refresh();

        if ($order->status !== 'payment_pending') {
            return false;
        }

        $lowInventoryProducts = [];

        DB::transaction(function () use ($order, &$lowInventoryProducts) {
            $order->status = 'paid';
            $order->save();

            // Inventory is decremented here (on success) — never at order creation
            // for express wallets — so abandoned redirects never hold stock.
            foreach ($order->items as $item) {
                if (!$item->product_id) {
                    continue;
                }
                $product = Product::find($item->product_id);
                if ($product && $product->inventory_type === 'limited' && $product->quantity_available !== null) {
                    $product->quantity_available = max(0, $product->quantity_available - $item->quantity);
                    $product->save();
                    $product->refresh();

                    if (
                        $product->low_inventory_threshold !== null &&
                        $product->quantity_available <= $product->low_inventory_threshold
                    ) {
                        $lowInventoryProducts[] = $product;
                    }
                }
            }
        });

        // Customer confirmation
        try {
            $order->load('items');
            Mail::to($order->billing_email)->send(new OrderConfirmation($order));
        } catch (\Exception $e) {
            Log::error('Express order confirmation email failed for order ' . $order->id . ': ' . $e->getMessage());
        }

        // Admin notification
        $this->sendAdminNotification($order);

        // Low inventory alerts (after commit)
        foreach ($lowInventoryProducts as $product) {
            try {
                $adminEmail = config('mail.admin_email', env('MAIL_ADMIN_EMAIL', 'info@oasismint.com'));
                if ($adminEmail && filter_var($adminEmail, FILTER_VALIDATE_EMAIL)) {
                    Mail::to($adminEmail)->send(new LowInventoryNotification(
                        $product,
                        $product->quantity_available,
                        $product->low_inventory_threshold
                    ));
                }
            } catch (\Exception $e) {
                Log::error('Low inventory notification (express) failed for product ' . $product->id . ': ' . $e->getMessage());
            }
        }

        Log::info('Express order finalised as paid: ' . $order->id . ' (' . $order->payment_method . ')');

        return true;
    }

    /**
     * Mark a pending express-wallet order as failed.
     *
     * No inventory is restored because none was ever decremented for a
     * pending express order. Idempotent via the payment_pending guard.
     */
    public function markFailed(Order $order, ?string $reason = null): void
    {
        $order->refresh();

        if ($order->status !== 'payment_pending') {
            return;
        }

        $order->status = 'payment_failed';
        $order->save();

        Log::info('Express order marked failed: ' . $order->id . ($reason ? ' — ' . $reason : ''));
    }

    /**
     * Send a concise "order paid" notification to the store admin.
     */
    protected function sendAdminNotification(Order $order): void
    {
        try {
            $adminEmail = config('mail.admin_email', env('MAIL_ADMIN_EMAIL', 'info@oasismint.com'));
            if (!$adminEmail || !filter_var($adminEmail, FILTER_VALIDATE_EMAIL)) {
                return;
            }

            $label = self::label($order->payment_method);

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
                <div style='background:#e6f0ff;border:1px solid #3b82f6;padding:15px;border-radius:4px;margin-bottom:15px;'>
                    <h2 style='margin:0;color:#1e3a8a;'>{$label} Order Paid – Order #{$order->id}</h2>
                    <p style='margin:6px 0 0;color:#1e3a8a;'>Payment completed via {$label}. Order is now <strong>Paid</strong>.</p>
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
                        <tr><td><strong>Payment:</strong></td><td>{$label} – <span style='color:green;'>PAID</span></td></tr>
                        <tr><td><strong>Total:</strong></td><td><strong>$" . number_format($order->total, 2) . "</strong></td></tr>
                    </table>
                </div>
                <div style='background:#f8f9fa;padding:15px;margin:10px 0;border-radius:4px;'>
                    <h3 style='margin-top:0;'>Customer</h3>
                    <p>
                        <strong>{$order->billing_first_name} {$order->billing_last_name}</strong><br>
                        {$order->billing_email}<br>
                        " . ($order->billing_phone ?: 'N/A') . "
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
                    " . ($order->credit_card_fee > 0 ? "<tr><td>Processing Fee ({$order->credit_card_percentage}%):</td><td style='text-align:right;'>$" . number_format($order->credit_card_fee, 2) . "</td></tr>" : '') . "
                    <tr style='font-weight:bold;font-size:16px;border-top:2px solid #333;'>
                        <td style='padding-top:10px;'>TOTAL:</td>
                        <td style='text-align:right;padding-top:10px;'>$" . number_format($order->total, 2) . "</td>
                    </tr>
                </table>
                <hr style='margin:20px 0;'>
                <p style='font-size:12px;color:#666;'>Automated notification from " . config('app.name') . " | " . now() . "</p>
            </body></html>
            ";

            Mail::html($html, function ($message) use ($order, $adminEmail, $label) {
                $message->to($adminEmail)
                    ->subject("{$label} Order Paid – Order #{$order->id} – {$order->billing_email}");
            });
        } catch (\Exception $e) {
            Log::error('Express admin notification failed for order ' . $order->id . ': ' . $e->getMessage());
        }
    }
}
