@extends('layouts.app')

@section('content')
<div class="container py-5">
    <h1>Order Confirmation</h1>
    <p>Thank you for your order! Your order ID is <strong>{{ $order->id }}</strong>.</p>
    <h3>Order Details</h3>
    <ul>
        <li>Name: {{ $order->billing_first_name }} {{ $order->billing_last_name }}</li>
        <li>Email: {{ $order->billing_email }}</li>
        <li>Total: ${{ number_format($order->total, 2) }}</li>
        <li>Status: {{ ucfirst($order->status) }}</li>
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
        </tbody>
    </table>
</div>
@endsection 