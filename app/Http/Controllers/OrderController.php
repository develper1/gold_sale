<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'billing_first_name' => 'required',
            'billing_last_name' => 'required',
            'billing_email' => 'required|email',
            'billing_address_1' => 'required',
            'billing_city' => 'required',
            'billing_state' => 'required',
            'billing_postcode' => 'required',
            'billing_country' => 'required',
            'payment_method' => 'required',
        ]);

        $cart = session('cart', []);
        $subtotal = 0;
        foreach ($cart as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }
        $shipping_fee = $request->input('shipping_fee', 0);
        $state_fee = $request->input('state_fee', 0);
        $service_fee = $request->input('service_fee', 0);
        $total = $subtotal + $shipping_fee + $state_fee + $service_fee;

        $order = Order::create([
            'user_id' => Auth::id(),
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
            'total' => $total,
            'payment_method' => $request->payment_method,
            'status' => 'pending',
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

        session()->forget('cart');

        return redirect()->route('order.confirmation', $order->id);
    }

    public function confirmation($orderId)
    {
        $order = Order::with('items')->findOrFail($orderId);
        return view('order-confirmation', compact('order'));
    }
} 