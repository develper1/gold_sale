<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderRefund;
use App\Models\Product;
use Illuminate\Support\Facades\Log;

class RefundService
{
    /**
     * Check if an order can be refunded via PayPal API.
     */
    public function canRefundViaPayPal(Order $order): bool
    {
        if (empty($order->transaction_id)) {
            return false;
        }
        return in_array($order->payment_method, ['paypal', 'credit_card'], true)
            && !str_starts_with($order->transaction_id, 'pi_');
    }

    /**
     * Check if an order can be refunded via Stripe API.
     */
    public function canRefundViaStripe(Order $order): bool
    {
        if (empty($order->transaction_id)) {
            return false;
        }
        return in_array($order->payment_method, ['ach', 'echeck', 'credit_card', 'bank_wire'], true)
            && str_starts_with($order->transaction_id, 'pi_');
    }

    /**
     * Issue a refund via Stripe for a PaymentIntent.
     * Pass null for amount to issue a full refund.
     */
    protected function refundStripePaymentIntent(string $paymentIntentId, ?float $amount = null): array
    {
        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

        try {
            $params = ['payment_intent' => $paymentIntentId];
            if ($amount !== null && $amount > 0) {
                $params['amount'] = (int) round($amount * 100);
            }
            $refund = \Stripe\Refund::create($params);
            return ['success' => true, 'refund_id' => $refund->id];
        } catch (\Stripe\Exception\ApiErrorException $e) {
            Log::error('Stripe refund failed: ' . $e->getMessage());
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * Get PayPal API base URL and credentials.
     */
    protected function getPayPalConfig(): array
    {
        $isSandbox = (bool) env('PAYPAL_SANDBOX', true);
        return [
            'base_url' => $isSandbox ? 'https://api-m.sandbox.paypal.com' : 'https://api-m.paypal.com',
            'client_id' => $isSandbox ? env('PAYPAL_SANDBOX_CLIENT_ID') : env('PAYPAL_LIVE_CLIENT_ID'),
            'secret' => $isSandbox ? env('PAYPAL_SANDBOX_SECRET_KEY') : env('PAYPAL_LIVE_SECRET_KEY'),
        ];
    }

    /**
     * Get PayPal OAuth access token.
     */
    protected function getPayPalAccessToken(): ?string
    {
        $config = $this->getPayPalConfig();
        $response = \Http::asForm()
            ->withBasicAuth($config['client_id'], $config['secret'])
            ->post($config['base_url'] . '/v1/oauth2/token', [
                'grant_type' => 'client_credentials',
            ]);

        if (!$response->ok()) {
            Log::error('PayPal auth failed: ' . $response->body());
            return null;
        }

        return $response->json()['access_token'] ?? null;
    }

    /**
     * Get capture ID from PayPal order.
     */
    protected function getCaptureIdFromPayPalOrder(string $paypalOrderId): ?string
    {
        $token = $this->getPayPalAccessToken();
        if (!$token) {
            return null;
        }

        $config = $this->getPayPalConfig();
        $response = \Http::withToken($token)
            ->withHeaders(['Prefer' => 'return=representation'])
            ->get($config['base_url'] . '/v2/checkout/orders/' . $paypalOrderId);

        if (!$response->ok()) {
            Log::error('PayPal get order failed: ' . $response->body());
            return null;
        }

        $data = $response->json();
        $purchaseUnits = $data['purchase_units'] ?? [];

        foreach ($purchaseUnits as $unit) {
            $payments = $unit['payments'] ?? [];
            $captures = $payments['captures'] ?? [];
            if (!empty($captures[0]['id'])) {
                return $captures[0]['id'];
            }
        }

        return null;
    }

    /**
     * Issue refund via PayPal.
     *
     * @param string $captureId PayPal capture ID
     * @param float|null $amount Amount for partial refund, null for full refund
     * @return array{success: bool, refund_id?: string, error?: string}
     */
    protected function refundPayPalCapture(string $captureId, ?float $amount = null): array
    {
        $token = $this->getPayPalAccessToken();
        if (!$token) {
            return ['success' => false, 'error' => 'Could not authenticate with PayPal.'];
        }

        $config = $this->getPayPalConfig();
        // PayPal expects {} for full refund, not []. An empty array serializes as [] which causes VALIDATION_ERROR.
        $body = $amount !== null && $amount > 0
            ? [
                'amount' => [
                    'value' => number_format($amount, 2, '.', ''),
                    'currency_code' => 'USD',
                ],
            ]
            : new \stdClass(); // empty object -> {}

        $response = \Http::withToken($token)
            ->withHeaders(['Prefer' => 'return=representation'])
            ->post($config['base_url'] . '/v2/payments/captures/' . $captureId . '/refund', $body);

        // PayPal returns 201 Created for successful refunds; ok() only checks for 200
        $isSuccess = $response->status() >= 200 && $response->status() < 300;

        if (!$isSuccess) {
            $errorBody = $response->json() ?? [];
            $message = $errorBody['message'] ?? null;
            if ($message === null && !empty($errorBody['details'][0]['description'])) {
                $message = $errorBody['details'][0]['description'];
            }
            if ($message === null && !empty($errorBody['details'][0]['issue'])) {
                $message = $errorBody['details'][0]['issue'];
            }
            if ($message === null) {
                $message = 'PayPal declined the refund. Please try again or process manually.';
            }
            $debugData = [
                'status' => $response->status(),
                'body' => $response->body(),
                'name' => $errorBody['name'] ?? null,
                'debug_id' => $errorBody['debug_id'] ?? null,
            ];
            Log::error('PayPal refund failed', $debugData);
            return [
                'success' => false,
                'error' => $message,
                'debug' => $debugData,
            ];
        }

        $refundData = $response->json();
        $refundId = $refundData['id'] ?? null;

        return ['success' => true, 'refund_id' => $refundId];
    }

    /**
     * Restore inventory for order items with limited inventory.
     */
    protected function restoreInventory(Order $order): void
    {
        foreach ($order->items as $item) {
            $product = $item->product_id ? Product::find($item->product_id) : null;
            if ($product && $product->inventory_type === 'limited' && $product->quantity_available !== null) {
                $product->quantity_available = $product->quantity_available + $item->quantity;
                $product->save();
            }
        }
    }

    /**
     * Cancel order: full refund via PayPal or Stripe + set status to canceled.
     */
    public function cancelOrder(Order $order, ?string $reason = null): array
    {
        if (!in_array($order->status, ['paid', 'shipped', 'processed'], true)) {
            return ['success' => false, 'error' => 'Order can only be canceled if it is paid, processed, or shipped.'];
        }

        $isPayPalOrder = $this->canRefundViaPayPal($order);
        $isStripeOrder = $this->canRefundViaStripe($order);

        if (!$isPayPalOrder && !$isStripeOrder) {
            return ['success' => false, 'error' => 'Refund via API is not available for this order. Process manually if needed.'];
        }

        if ($isPayPalOrder) {
            $captureId = $this->getCaptureIdFromPayPalOrder($order->transaction_id);
            if (!$captureId) {
                return ['success' => false, 'error' => 'Could not find PayPal capture for this order.'];
            }
            $result = $this->refundPayPalCapture($captureId, null);
        } else {
            $result = $this->refundStripePaymentIntent($order->transaction_id, null);
        }

        if (!$result['success']) {
            return $result;
        }

        \DB::transaction(function () use ($order, $result, $reason) {
            OrderRefund::create([
                'order_id' => $order->id,
                'amount' => $order->total,
                'refund_type' => 'full',
                'gateway_refund_id' => $result['refund_id'] ?? null,
                'reason' => $reason ?? 'Order canceled',
            ]);

            $order->refunded_amount = $order->total;
            $order->status = 'canceled';
            $order->save();

            $this->restoreInventory($order);
        });

        return ['success' => true, 'message' => 'Order canceled and refund processed.'];
    }

    /**
     * Full refund: refund full remaining amount via PayPal or Stripe.
     */
    public function fullRefund(Order $order, ?string $reason = null): array
    {
        if (!in_array($order->status, ['paid', 'shipped', 'processed'], true)) {
            return ['success' => false, 'error' => 'Order can only be refunded if it is paid, processed, or shipped.'];
        }

        $remaining = (float) $order->total - (float) ($order->refunded_amount ?? 0);
        if ($remaining <= 0) {
            return ['success' => false, 'error' => 'Order is already fully refunded.'];
        }

        $isPayPalOrder = $this->canRefundViaPayPal($order);
        $isStripeOrder = $this->canRefundViaStripe($order);

        if (!$isPayPalOrder && !$isStripeOrder) {
            return ['success' => false, 'error' => 'Refund via API is not available for this order. Process manually if needed.'];
        }

        if ($isPayPalOrder) {
            $captureId = $this->getCaptureIdFromPayPalOrder($order->transaction_id);
            if (!$captureId) {
                return ['success' => false, 'error' => 'Could not find PayPal capture for this order.'];
            }
            $result = $this->refundPayPalCapture($captureId, null);
        } else {
            $result = $this->refundStripePaymentIntent($order->transaction_id, null);
        }

        if (!$result['success']) {
            return $result;
        }

        \DB::transaction(function () use ($order, $remaining, $result, $reason) {
            OrderRefund::create([
                'order_id' => $order->id,
                'amount' => $remaining,
                'refund_type' => 'full',
                'gateway_refund_id' => $result['refund_id'] ?? null,
                'reason' => $reason,
            ]);

            $order->refunded_amount = $order->total;
            $order->status = 'refunded';
            $order->save();

            $this->restoreInventory($order);
        });

        return ['success' => true, 'message' => 'Full refund processed.'];
    }

    /**
     * Partial refund via PayPal or Stripe.
     */
    public function partialRefund(Order $order, float $amount, ?string $reason = null): array
    {
        if (!in_array($order->status, ['paid', 'shipped', 'partially_refunded', 'processed'], true)) {
            return ['success' => false, 'error' => 'Order cannot be partially refunded in its current status.'];
        }

        $refundedSoFar = (float) ($order->refunded_amount ?? 0);
        $remaining = (float) $order->total - $refundedSoFar;

        if ($amount <= 0) {
            return ['success' => false, 'error' => 'Refund amount must be greater than zero.'];
        }

        if ($amount > $remaining) {
            return ['success' => false, 'error' => 'Refund amount cannot exceed remaining amount ($' . number_format($remaining, 2) . ').'];
        }

        $isPayPalOrder = $this->canRefundViaPayPal($order);
        $isStripeOrder = $this->canRefundViaStripe($order);

        if (!$isPayPalOrder && !$isStripeOrder) {
            return ['success' => false, 'error' => 'Refund via API is not available for this order. Process manually if needed.'];
        }

        if ($isPayPalOrder) {
            $captureId = $this->getCaptureIdFromPayPalOrder($order->transaction_id);
            if (!$captureId) {
                return ['success' => false, 'error' => 'Could not find PayPal capture for this order.'];
            }
            $result = $this->refundPayPalCapture($captureId, $amount);
        } else {
            $result = $this->refundStripePaymentIntent($order->transaction_id, $amount);
        }

        if (!$result['success']) {
            return $result;
        }

        \DB::transaction(function () use ($order, $amount, $result, $reason) {
            OrderRefund::create([
                'order_id' => $order->id,
                'amount' => $amount,
                'refund_type' => 'partial',
                'gateway_refund_id' => $result['refund_id'] ?? null,
                'reason' => $reason,
            ]);

            $newRefundedTotal = (float) ($order->refunded_amount ?? 0) + $amount;
            $order->refunded_amount = $newRefundedTotal;
            $order->status = abs($newRefundedTotal - (float) $order->total) < 0.01 ? 'refunded' : 'partially_refunded';
            $order->save();
        });

        return ['success' => true, 'message' => 'Partial refund of $' . number_format($amount, 2) . ' processed.'];
    }
}
