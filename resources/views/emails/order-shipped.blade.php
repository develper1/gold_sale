<div style="background:#f5f7fb;padding:24px 0;margin:0;">
  <table role="presentation" cellpadding="0" cellspacing="0" border="0" align="center" width="100%" style="max-width:640px;margin:0 auto;background:#ffffff;border-radius:8px;box-shadow:0 2px 8px rgba(0,0,0,0.06);font-family:Arial,Helvetica,sans-serif;color:#2b2b2b;">
    <tr>
      <td style="padding:24px 24px 0;text-align:center;border-top-left-radius:8px;border-top-right-radius:8px;">
        <a href="{{ url('/') }}" target="_blank" style="text-decoration:none;display:inline-block;">
          <img src="{{ url('assets/media/logo.png') }}" alt="{{ config('app.name','Oasis Mint') }}" width="160" style="display:block;margin:0 auto;max-width:160px;height:auto;">
        </a>
        <h1 style="margin:16px 0 8px;font-size:24px;line-height:32px;color:#111111;">Order Shipped</h1>

        <!-- Order Number & Shipped Message -->
        <div style="background:#f0fdf4;border:2px solid #16a34a;border-radius:6px;padding:20px;margin:16px 0;text-align:center;">
          <p style="margin:0 0 12px;font-size:16px;line-height:1.6;color:#166534;">
            <strong>Your order number {{ $order->id }} has now been shipped.</strong>
          </p>
          <div style="font-size:14px;color:#555;margin-top:8px;">Thank you for your purchase. Your order is on its way.</div>
        </div>

        <div style="margin:0 0 16px;border:1px solid #e5e7eb;border-radius:6px;overflow:hidden;">
          <div style="background:#f9fafb;padding:10px 14px;font-size:12px;color:#6b7280;text-transform:uppercase;letter-spacing:0.5px;">
            Shipment Details
          </div>
          <div style="padding:14px;text-align:left;">
            <p style="margin:0 0 8px;font-size:14px;line-height:20px;color:#111111;">
              <strong>Method of Shipment:</strong>
              {{ $order->shipping_method ?: 'Not provided' }}
            </p>
            <p style="margin:0;font-size:14px;line-height:20px;color:#111111;">
              <strong>Tracking Number:</strong>
              {{ $order->tracking_number ?: 'Not provided' }}
            </p>
          </div>
        </div>

        <p style="margin:0 0 16px;font-size:14px;line-height:20px;color:#111111;">
          You can track your order or visit our website if you have any questions.
        </p>
        <a href="{{ url('/') }}" target="_blank" style="display:inline-block;background:#2563eb;color:#ffffff!important;text-decoration:none;padding:12px 24px;border-radius:6px;font-weight:600;font-size:14px;margin-top:8px;">Visit Our Website →</a>
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
