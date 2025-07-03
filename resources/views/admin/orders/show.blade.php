@extends('admin.layouts.app')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="card">
        <div class="card-header">
            <h5>Order #{{ $order->id }}</h5>
        </div>
        <div class="card-body">
            <h6>Customer Info</h6>
            <ul>
                <li>Name: {{ $order->billing_first_name }} {{ $order->billing_last_name }}</li>
                <li>Email: {{ $order->billing_email }}</li>
                <li>Phone: {{ $order->billing_phone }}</li>
                <li>Address: {{ $order->billing_address_1 }}, {{ $order->billing_city }}, {{ $order->billing_state }}, {{ $order->billing_postcode }}, {{ $order->billing_country }}</li>
            </ul>
            <h6>Order Info</h6>
            <ul>
                <li>Status: {{ ucfirst($order->status) }}</li>
                <li>Payment Method: {{ ucfirst($order->payment_method) }}</li>
                <li>Subtotal: ${{ number_format($order->subtotal, 2) }}</li>
                <li>Shipping Fee: ${{ number_format($order->shipping_fee, 2) }}</li>
                <li>State Fee: ${{ number_format($order->state_fee, 2) }}</li>
                <li>Service Fee: ${{ number_format($order->service_fee, 2) }}</li>
                <li>Total: ${{ number_format($order->total, 2) }}</li>
                <li>Order Comments: {{ $order->order_comments }}</li>
                <li>Created At: {{ $order->created_at->format('Y-m-d H:i') }}</li>
            </ul>
            <h6>Order Items</h6>
            <table class="table">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Image</th>
                        <th>Quantity</th>
                        <th>Price</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->items as $item)
                    <tr>
                        <td>{{ $item->name }}</td>
                        <td>
                            @if($item->product && $item->product->images->count() > 0)
                                <img src="{{ asset('storage/app/public/' . $item->product->images->first()->image_path) }}" alt="{{ $item->name }}" width="60">
                            @else
                                No Image
                            @endif
                        </td>
                        <td>{{ $item->quantity }}</td>
                        <td>${{ number_format($item->price, 2) }}</td>
                        <td>${{ number_format($item->price * $item->quantity, 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection 