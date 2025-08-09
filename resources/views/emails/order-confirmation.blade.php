<div style="background:#f5f7fb;padding:24px 0;margin:0;">
  <table role="presentation" cellpadding="0" cellspacing="0" border="0" align="center" width="100%" style="max-width:640px;margin:0 auto;background:#ffffff;border-radius:8px;box-shadow:0 2px 8px rgba(0,0,0,0.06);font-family:Arial,Helvetica,sans-serif;color:#2b2b2b;">
    <tr>
      <td style="padding:24px 24px 0;text-align:center;border-top-left-radius:8px;border-top-right-radius:8px;">
        <a href="{{ url('/') }}" target="_blank" style="text-decoration:none;display:inline-block;">
          <img src="{{ url('assets/media/logo.png') }}" alt="{{ config('app.name','Oasis Mint') }}" width="160" style="display:block;margin:0 auto;max-width:160px;height:auto;">
        </a>
        <h1 style="margin:16px 0 8px;font-size:24px;line-height:32px;color:#111111;">Order Confirmation</h1>
        <p style="margin:0 0 8px;font-size:14px;line-height:20px;color:#555555;">Thank you for your order! Your order ID is <strong>{{ $order->id }}</strong>.</p>
        <p style="margin:0 0 16px;font-size:14px;line-height:20px;color:#b42318;"><strong>Payment must be received within 48 hours of order placed or the order will be automatically canceled.</strong></p>
      </td>
    </tr>
    <tr>
      <td style="padding:0 24px 24px;">
        <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%" style="border-collapse:collapse;margin-top:8px;">
          <tr>
            <td colspan="2" style="padding:12px 0;border-bottom:1px solid #e6e6e6;">
              <h3 style="margin:0;font-size:16px;color:#111111;">Order Details</h3>
            </td>
          </tr>
          <tr>
            <td style="padding:8px 0;font-size:14px;color:#555;">Name</td>
            <td style="padding:8px 0;font-size:14px;color:#111;text-align:right;">{{ $order->billing_first_name }} {{ $order->billing_last_name }}</td>
          </tr>
          <tr>
            <td style="padding:8px 0;font-size:14px;color:#555;">Email</td>
            <td style="padding:8px 0;font-size:14px;color:#111;text-align:right;">{{ $order->billing_email }}</td>
          </tr>
          <tr>
            <td style="padding:8px 0;font-size:14px;color:#555;">Status</td>
            <td style="padding:8px 0;font-size:14px;color:#111;text-align:right;">{{ ucfirst($order->status) }}</td>
          </tr>
          <tr>
            <td style="padding:8px 0;font-size:14px;color:#555;">Total</td>
            <td style="padding:8px 0;font-size:16px;color:#111;text-align:right;"><strong>${{ number_format($order->total, 2) }}</strong></td>
          </tr>
        </table>

        <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%" style="border-collapse:collapse;margin-top:16px;">
          <tr>
            <td colspan="2" style="padding:12px 0;border-bottom:1px solid #e6e6e6;">
              <h3 style="margin:0;font-size:16px;color:#111111;">Order Fees</h3>
            </td>
          </tr>
          <tr>
            <td style="padding:6px 0;font-size:14px;color:#555;">Subtotal</td>
            <td style="padding:6px 0;font-size:14px;color:#111;text-align:right;">${{ number_format($order->subtotal, 2) }}</td>
          </tr>
          <tr>
            <td style="padding:6px 0;font-size:14px;color:#555;">Shipping Fee</td>
            <td style="padding:6px 0;font-size:14px;color:#111;text-align:right;">${{ number_format($order->shipping_fee, 2) }}</td>
          </tr>
          <tr>
            <td style="padding:6px 0;font-size:14px;color:#555;">State Fee</td>
            <td style="padding:6px 0;font-size:14px;color:#111;text-align:right;">${{ number_format($order->state_fee, 2) }}</td>
          </tr>
          <tr>
            <td style="padding:6px 0;font-size:14px;color:#555;">Service Fee</td>
            <td style="padding:6px 0;font-size:14px;color:#111;text-align:right;">${{ number_format($order->service_fee, 2) }}</td>
          </tr>
          @if($order->payment_method === 'credit_card' || $order->payment_method === 'paypal')
          <tr>
            <td style="padding:6px 0;font-size:14px;color:#555;">Credit Card Fee</td>
            <td style="padding:6px 0;font-size:14px;color:#111;text-align:right;">${{ number_format($order->credit_card_fee, 2) }}</td>
          </tr>
          @endif
          <tr>
            <td style="padding:10px 0;border-top:1px dashed #e6e6e6;font-size:15px;color:#111;">Total</td>
            <td style="padding:10px 0;border-top:1px dashed #e6e6e6;font-size:18px;color:#111;text-align:right;"><strong>${{ number_format($order->total, 2) }}</strong></td>
          </tr>
        </table>

        <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%" style="border-collapse:collapse;margin-top:16px;">
          <tr>
            <td colspan="4" style="padding:12px 0;border-bottom:1px solid #e6e6e6;">
              <h3 style="margin:0;font-size:16px;color:#111111;">Order Items</h3>
            </td>
          </tr>
          <tr>
            <th align="left" style="padding:8px 0;font-size:13px;color:#666;border-bottom:1px solid #eee;">Product</th>
            <th align="center" style="padding:8px 0;font-size:13px;color:#666;border-bottom:1px solid #eee;">Qty</th>
            <th align="right" style="padding:8px 0;font-size:13px;color:#666;border-bottom:1px solid #eee;">Price</th>
            <th align="right" style="padding:8px 0;font-size:13px;color:#666;border-bottom:1px solid #eee;">Subtotal</th>
          </tr>
          @foreach($order->items as $item)
          <tr>
            <td style="padding:8px 0;font-size:14px;color:#111;border-bottom:1px solid #f2f2f2;">{{ $item->name }}</td>
            <td align="center" style="padding:8px 0;font-size:14px;color:#111;border-bottom:1px solid #f2f2f2;">{{ $item->quantity }}</td>
            <td align="right" style="padding:8px 0;font-size:14px;color:#111;border-bottom:1px solid #f2f2f2;">${{ number_format($item->price, 2) }}</td>
            <td align="right" style="padding:8px 0;font-size:14px;color:#111;border-bottom:1px solid #f2f2f2;">${{ number_format($item->price * $item->quantity, 2) }}</td>
          </tr>
          @endforeach
        </table>

      </td>
    </tr>
    <tr>
      <td style="padding:16px 24px 24px;text-align:center;color:#8c8c8c;font-size:12px;border-bottom-left-radius:8px;border-bottom-right-radius:8px;">
        <p style="margin:8px 0;">&copy; {{ date('Y') }} {{ config('app.name','Oasis Mint') }}. All rights reserved.</p>
        <p style="margin:0;">
          <a href="{{ url('/') }}" style="color:#8c8c8c;text-decoration:underline;">Visit our website</a>
        </p>
      </td>
    </tr>
  </table>
</div>