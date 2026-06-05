@extends('layouts.app')

@section('content')
<div class="container py-5">
    <h1>Order Confirmation</h1>
    <p>Thank you for your order! Your order number is <strong style="font-size: 15px; font-weight: bold; color: #2563eb; letter-spacing: 1px;">{{ $order->id }}</strong>.</p>
    @php
        $isPaidAtOrder = in_array($order->payment_method, ['credit_card', 'paypal']);
    @endphp

    @if($isPaidAtOrder)
    <p class="text-success"><strong>Thank you! Your payment has been received.</strong> We will process your order shortly.</p>
    @else
    <p><strong>To complete payment for your order, please call our office within <strong>2 business days (48 hours)</strong>
        with your order number to make payment. Payment must be received within 48 hours of order placement
        or the order will be automatically canceled.</strong></p>

    @if(in_array($order->payment_method, ['ach', 'bank_wire', 'wire']))
    <p>
        You MUST email the accounts department at 
        <a href="mailto:Payments@OasisMint.com">Payments@OasisMint.com</a>
        to request account and routing information within 2 business days of order confirmation.
    </p>
    @endif

    @if($order->payment_method === 'zelle')
    <p>
        Payment via Zelle must be sent to 
        <a href="mailto:Zelle@OasisMint.com">Zelle@OasisMint.com</a>
        within 24 hours of Order Confirmation.
    </p>
    @endif
    @endif

    <hr>
    <p>If any form of payment is returned unpaid or returned, the office or authorized agent may debit my account for the full amount with a service fee of $50 plus any actual charges assessed by this office and from your financial institution as a result of the dishonored check or chargeback.</p>
    

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
        <li>Address: {{ $order->billing_address_1 }}@if($order->billing_address_2), {{ $order->billing_address_2 }}@endif, {{ $order->billing_city }}, {{ $order->billing_state }}, {{ $order->billing_postcode }}, {{ $order->billing_country }}</li>
    </ul>
    <h3>Shipping Info</h3>
    <ul>
        <li>Name: {{ $order->shipping_first_name }} {{ $order->shipping_last_name }}</li>
        <li>Company: {{ $order->shipping_company }}</li>
        <li>Address: {{ $order->shipping_address_1 }}@if($order->shipping_address_2), {{ $order->shipping_address_2 }}@endif, {{ $order->shipping_city }}, {{ $order->shipping_state }}, {{ $order->shipping_postcode }}, {{ $order->shipping_country }}</li>
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
                    @if($order->payment_method === 'credit_card' || $order->payment_method === 'paypal')
                    <span>Credit Card Fee: ${{ number_format($order->credit_card_fee, 2) }}</span><br>
                    @endif
                    <strong>Total: ${{ number_format($order->total, 2) }}</strong>
                </td>
            </tr>
        </tbody>
    </table>
</div>
@endsection
