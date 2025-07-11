<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderConfirmation;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        // Verify PayPal payment
        $paypalOrderId = $request->input('paypal_order_id');
        if (!$paypalOrderId) {
            return response()->json(['error' => 'Missing PayPal order ID'], 422);
        }

        // Get PayPal access token
        $clientId = "AS1q2MeR_lXKqcjYgcZrVY1wRN4n1CLbgOz1p0dpaIFu-LsW2slgQtuiqq1anG2Yi-2eoc2ByMjcjf7U";
        $secret = "EKTLhYTd_VWl0otXtQc4K3DxmxGwLGMu_gL-uHXhq2YGRvFViDMN5g8GDbAt33yQ_DUC-4CpZV0fuksL";
        $isSandbox = true; // Set to false for live
        $baseUrl = $isSandbox ? 'https://api-m.sandbox.paypal.com' : 'https://api-m.paypal.com';
        $accessTokenResponse = Http::asForm()->withBasicAuth($clientId, $secret)
            ->post($baseUrl . '/v1/oauth2/token', [
                'grant_type' => 'client_credentials',
            ]);
        if (!$accessTokenResponse->ok()) {
            return response()->json(['error' => 'Could not authenticate with PayPal'], 500);
        }
        $accessToken = $accessTokenResponse['access_token'];

        // Verify order status
        $paypalResponse = Http::withToken($accessToken)
            ->get($baseUrl . '/v2/checkout/orders/' . $paypalOrderId);
        if (!$paypalResponse->ok() || $paypalResponse['status'] !== 'COMPLETED') {
            return response()->json(['error' => 'PayPal payment not completed'], 422);
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
            // 'payment_method' => 'required', // No longer needed
        ]);

        $cart = session('cart', []);
        $subtotal = 0;
        foreach ($cart as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }
        $shipping_fee = $request->input('shipping_fee', 0);
        $state_fee = $request->input('state_fee', 0);
        $service_fee = $request->input('service_fee', 0);
        $setting = \App\Models\Setting::first();
        $creditCardPercentage = $setting ? $setting->credit_card_percentage : 0;
        $creditCardFee = ($subtotal) * ($creditCardPercentage / 100);

        // Coupon logic
        $appliedCoupon = session('applied_coupon');
        if ($appliedCoupon) {
            if (!empty($appliedCoupon['free_shipping'])) {
                $shipping_fee = 0;
            }
            if (!empty($appliedCoupon['free_service_fee'])) {
                $service_fee = 0;
            }
        }
        $total = $subtotal + $shipping_fee + $state_fee + $service_fee + $creditCardFee;

        $order = Order::create([
            'user_id' => Auth::id(),
            'order_uid' => Order::generateOrderUid(),
            'transaction_id' => $paypalOrderId,
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
            'shipping_first_name' => $request->shipping_first_name,
            'shipping_last_name' => $request->shipping_last_name,
            'shipping_company' => $request->shipping_company,
            'shipping_address_1' => $request->shipping_address_1,
            'shipping_address_2' => $request->shipping_address_2,
            'shipping_city' => $request->shipping_city,
            'shipping_state' => $request->shipping_state,
            'shipping_postcode' => $request->shipping_postcode,
            'shipping_country' => $request->shipping_country,
            'order_comments' => $request->order_comments,
            'subtotal' => $subtotal,
            'shipping_fee' => $shipping_fee,
            'state_fee' => $state_fee,
            'service_fee' => $service_fee,
            'credit_card_fee' => $creditCardFee,
            'credit_card_percentage' => $creditCardPercentage,
            'total' => $total,
            'payment_method' => 'paypal',
            'status' => 'paid',
        ]);

        foreach ($cart as $item) {
            $order->items()->create([
                'product_id' => $item['id'],
                'name' => $item['name'],
                'price' => $item['price'],
                'quantity' => $item['quantity'],
                'image' => $item['image'] ?? null,
            ]);
        }

        // Send order confirmation email
        $order->load('items');
        try {
            Mail::to($order->billing_email)->send(new OrderConfirmation($order));
        } catch (\Exception $e) {
            dd($e->getMessage());
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
            ->where(function($q) {
                $today = date('Y-m-d');
                $q->whereNull('valid_from')->orWhere('valid_from', '<=', $today);
            })
            ->where(function($q) {
                $today = date('Y-m-d');
                $q->whereNull('valid_to')->orWhere('valid_to', '>=', $today);
            })
            ->first();
        if (!$coupon) {
            return response()->json(['valid' => false, 'message' => 'Invalid or expired coupon.']);
        }
        // Store coupon in session for use on order
        session(['applied_coupon' => $coupon->only(['id','code','free_shipping','free_service_fee'])]);
        return response()->json([
            'valid' => true,
            'free_shipping' => (bool)$coupon->free_shipping,
            'free_service_fee' => (bool)$coupon->free_service_fee,
            'description' => $coupon->description,
            'code' => $coupon->code,
        ]);
    }
} 