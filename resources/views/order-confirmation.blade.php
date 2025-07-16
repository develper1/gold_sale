@extends('layouts.app')

@section('content')
<div class="container py-5">
    <h1>Order Confirmation</h1>
    <p>Thank you for your order! Your order ID is <strong>{{ $order->id }}</strong>.</p>
    <h3>Order Details</h3>
    <ul>
        <li>Name: {{ $order->billing_first_name }} {{ $order->billing_last_name }}</li>
        <li>Email: {{ $order->billing_email }}</li>
        <li>Status: {{ ucfirst($order->status) }}</li>
    </ul>
    <h3>Billing Info</h3>
    <ul>
        <li>Name: {{ $order->billing_first_name }} {{ $order->billing_last_name }}</li>
        <li>Email: {{ $order->billing_email }}</li>
        <li>Phone: {{ $order->billing_phone }}</li>
        <li>Address: {{ $order->billing_address_1 }}, {{ $order->billing_city }}, {{ $order->billing_state }}, {{ $order->billing_postcode }}, {{ $order->billing_country }}</li>
    </ul>
    <h3>Shipping Info</h3>
    <ul>
        <li>Name: {{ $order->shipping_first_name }} {{ $order->shipping_last_name }}</li>
        <li>Company: {{ $order->shipping_company }}</li>
        <li>Address: {{ $order->shipping_address_1 }}, {{ $order->shipping_city }}, {{ $order->shipping_state }}, {{ $order->shipping_postcode }}, {{ $order->shipping_country }}</li>
    </ul>
    <h3>Order Items</h3>
    <table class="table">
        <thead>
            <tr>
                <th>Product</th>
                <th>Quantity</th>
                <th>Price</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->items as $item)
            <tr>
                <td>{{ $item->name }}</td>
                <td>{{ $item->quantity }}</td>
                <td>${{ number_format($item->price, 2) }}</td>
                <td>${{ number_format($item->price * $item->quantity, 2) }}</td>
            </tr>
            @endforeach
            <tr>
                <td colspan="3"></td>
                <td>
                    <span>Subtotal: ${{ number_format($order->subtotal, 2) }}</span><br>
                    <span>Shipping Fee: ${{ number_format($order->shipping_fee, 2) }}</span><br>
                    <span>State Fee: ${{ number_format($order->state_fee, 2) }}</span><br>
                    <span>Service Fee: ${{ number_format($order->service_fee, 2) }}</span><br>
                    @if($order->payment_method === 'credit_card')
                    <span>Credit Card Fee: ${{ number_format($order->credit_card_fee, 2) }}</span><br>
                    @endif
                    <strong>Total: ${{ number_format($order->total, 2) }}</strong>
                </td>
            </tr>
        </tbody>
    </table>
</div>
@endsection 