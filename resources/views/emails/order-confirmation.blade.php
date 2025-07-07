<div style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto;">
    <h1 style="color: #333;">Order Confirmation</h1>
    <p>Thank you for your order! Your order ID is <strong>{{ $order->order_uid }}</strong>.</p>
    <h3 style="color: #333;">Order Details</h3>
    <ul>
        <li><strong>Name:</strong> {{ $order->billing_first_name }} {{ $order->billing_last_name }}</li>
        <li><strong>Email:</strong> {{ $order->billing_email }}</li>
        <li><strong>Total:</strong> ${{ number_format($order->total, 2) }}</li>
        <li><strong>Status:</strong> {{ ucfirst($order->status) }}</li>
    </ul>
    <h3 style="color: #333;">Order Items</h3>
    <table style="width: 100%; border-collapse: collapse;">
        <thead>
            <tr>
                <th style="border: 1px solid #ccc; padding: 8px;">Product</th>
                <th style="border: 1px solid #ccc; padding: 8px;">Quantity</th>
                <th style="border: 1px solid #ccc; padding: 8px;">Price</th>
                <th style="border: 1px solid #ccc; padding: 8px;">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->items as $item)
            <tr>
                <td style="border: 1px solid #ccc; padding: 8px;">{{ $item->name }}</td>
                <td style="border: 1px solid #ccc; padding: 8px;">{{ $item->quantity }}</td>
                <td style="border: 1px solid #ccc; padding: 8px;">${{ number_format($item->price, 2) }}</td>
                <td style="border: 1px solid #ccc; padding: 8px;">${{ number_format($item->price * $item->quantity, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div> 