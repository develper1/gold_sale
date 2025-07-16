@extends('admin.layouts.app')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="card">
        <div class="card-header">
            <h5>Order #{{ $order->id }}</h5>
        </div>
        <div class="card-body">
            <h6>Order Info</h6>
            <ul>
                <li>Status: {{ ucfirst($order->status) }}</li>
                <li>Payment Method: {{ ucfirst($order->payment_method) }}</li>
                <li>Order Comments: {{ $order->order_comments }}</li>
                <li>Created At: {{ $order->created_at->format('d M Y') }}</li>
                <li>Transaction ID: {{ $order->transaction_id }}</li>
            </ul>
            <h6>Billing Info</h6>
            <ul>
                <li>Name: {{ $order->billing_first_name }} {{ $order->billing_last_name }}</li>
                <li>Email: {{ $order->billing_email }}</li>
                <li>Phone: {{ $order->billing_phone }}</li>
                <li>Address: {{ $order->billing_address_1 }}, {{ $order->billing_city }}, {{ $order->billing_state }}, {{ $order->billing_postcode }}, {{ $order->billing_country }}</li>
            </ul>
            <h6>Shipping Info</h6>
            <ul>
                <li>Name: {{ $order->shipping_first_name }} {{ $order->shipping_last_name }}</li>
                <li>Company: {{ $order->shipping_company }}</li>
                <li>Address: {{ $order->shipping_address_1 }}, {{ $order->shipping_city }}, {{ $order->shipping_state }}, {{ $order->shipping_postcode }}, {{ $order->shipping_country }}</li>
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
                    <tr>
                        <td colspan="4"></td>
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
    </div>
</div>
@endsection 