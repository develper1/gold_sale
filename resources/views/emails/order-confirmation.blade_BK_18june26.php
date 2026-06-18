<div style="background:#f5f7fb;padding:24px 0;margin:0;">
  <table role="presentation" cellpadding="0" cellspacing="0" border="0" align="center" width="100%"
    style="max-width:640px;margin:0 auto;background:#ffffff;border-radius:8px;box-shadow:0 2px 8px rgba(0,0,0,0.06);font-family:Arial,Helvetica,sans-serif;color:#2b2b2b;">
    <tr>
      <td style="padding:24px 24px 0;text-align:center;border-top-left-radius:8px;border-top-right-radius:8px;">
        <a href="{{ url('/') }}" target="_blank" style="text-decoration:none;display:inline-block;">
          <img src="{{ url('assets/media/logo.png') }}" alt="{{ config('app.name', 'Oasis Mint') }}" width="160"
            style="display:block;margin:0 auto;max-width:160px;height:auto;">
        </a>
        <h1 style="margin:16px 0 8px;font-size:24px;line-height:32px;color:#111111;">Order Confirmation</h1>

        <!-- Prominent Order Number Box -->
        <div
          style="background:#f0f4ff;border:2px solid #2563eb;border-radius:6px;padding:16px;margin:16px 0;text-align:center;">
          <div style="font-size:12px;color:#555;text-transform:uppercase;letter-spacing:0.5px;margin-bottom:4px;">Sales
            Order #</div>
          <div style="font-size:28px;font-weight:bold;color:#2563eb;letter-spacing:1px;">{{ $order->id }}</div>
        </div>

        @php
          $isPaidAtOrder   = in_array($order->payment_method, ['credit_card', 'paypal']);
          $isAchProcessing = $order->payment_method === 'ach' && in_array($order->status, ['ach_pending', 'paid']);
        @endphp

        @if($isPaidAtOrder)
          <p style="margin:0 0 16px;font-size:14px;line-height:20px;color:#16a34a;">
            <strong>Thank you! Your payment has been received.</strong> We will process your order shortly.
          </p>
        @elseif($isAchProcessing)
          <p style="margin:0 0 16px;font-size:14px;line-height:20px;color:#2563eb;">
            <strong>Thank you! Your ACH bank payment has been submitted.</strong><br>
            Your bank account debit is now processing. Funds typically settle within
            <strong>1–4 business days</strong>. You will receive a payment confirmation
            email once the payment has cleared.
          </p>
        @else
          <p style="margin:0 0 16px;font-size:14px;line-height:20px;color:#b42318;">
            <strong>Thank you for your order!</strong><br>
            To complete payment for your order, follow instructions below.
          </p>

          <p style="margin:0 0 16px;font-size:14px;line-height:20px;color:#111111;">
            <strong>Wire/ACH or eCheck Instructions:</strong><br>
            Bank of America Routing #026009593<br>
            Account of Oasis Mint LLC #483110079771<br>
            1234 Saint Johns Place #130426<br>
            Brooklyn, NY 11213<br><br>

            <strong>Zelle payments to:</strong> Sales@oasismint.com
          </p>

          <p style="margin:0 0 16px;font-size:14px;line-height:20px;color:#111111;">
            Once payment is received by our office, an email confirmation will be sent
            to you. You must contact our office with your order number within <strong>24 hours</strong>
            with any questions to affect payment. Payment must be received within <strong>48 hours</strong>
            or the order will be automatically canceled.
          </p>
        @endif

        <hr style="border:none;border-top:1px solid #e5e7eb;margin:16px 0;">

        <p style="margin:0 0 16px;font-size:14px;line-height:20px;color:#111111;">
          If any form of payment is returned unpaid or returned, the office or authorized agent may debit my account for
          the full amount with a service fee of $50 plus any actual charges assessed by this office and from your
          financial institution as a result of the dishonored check or chargeback.
        </p>
        <!-- <p style="margin:0 0 16px;font-size:14px;line-height:20px;color:#b42318;">
            To complete payment for your order, please call our office within <strong>2 business days (48 hours)</strong> with your order number to make payment.
        </p> -->
      </td>
    </tr>
    <tr>
      <td style="padding:0 24px 24px;">
        <!-- Order Details (REMOVED duplicate Sales Order #) -->
        <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%"
          style="border-collapse:collapse;margin-top:8px;">
          <tr>
            <td colspan="2" style="padding:12px 0;border-bottom:1px solid #e6e6e6;">
              <h3 style="margin:0;font-size:16px;color:#111111;">Order Details</h3>
            </td>
          </tr>
          <tr>
            <td style="padding:8px 0;font-size:14px;color:#555;">Sales Order #</td>
            <td style="padding:8px 0;font-size:16px;color:#2563eb;text-align:right;font-weight:bold;">{{ $order->id }}
            </td>
          </tr>
          <tr>
            <td style="padding:8px 0;font-size:14px;color:#555;">Name</td>
            <td style="padding:8px 0;font-size:14px;color:#111;text-align:right;">{{ $order->billing_first_name }}
              {{ $order->billing_last_name }}
            </td>
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
            <td style="padding:8px 0;font-size:16px;color:#111;text-align:right;">
              <strong>${{ number_format($order->total, 2) }}</strong>
            </td>
          </tr>
        </table>

        <!-- NEW: Customer Information (Bill To, Ship To, Payment Method) -->
        <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%"
          style="border-collapse:collapse;margin-top:16px;">
          <tr>
            <td colspan="2" style="padding:12px 0;border-bottom:1px solid #e6e6e6;">
              <h3 style="margin:0;font-size:16px;color:#111111;">Customer Information</h3>
            </td>
          </tr>
          <tr>
            <td valign="top" style="padding:12px 8px 12px 0;width:50%;">
              <div style="font-size:12px;color:#666;text-transform:uppercase;letter-spacing:0.5px;margin-bottom:4px;">
                Bill To</div>
              <div style="font-size:14px;color:#111;line-height:1.4;">
                {{ $order->billing_first_name }} {{ $order->billing_last_name }}<br>
                {{ $order->billing_address_1 }}@if($order->billing_address_2), {{ $order->billing_address_2 }}@endif,
                {{ $order->billing_city }}, {{ $order->billing_state }}, {{ $order->billing_postcode }},
                {{ $order->billing_country }}<br>
                {{ $order->billing_phone }}
              </div>
            </td>
            <td valign="top" style="padding:12px 0 12px 8px;width:50%;">
              <div style="font-size:12px;color:#666;text-transform:uppercase;letter-spacing:0.5px;margin-bottom:4px;">
                Ship To</div>
              <div style="font-size:14px;color:#111;line-height:1.4;">
                {{ $order->shipping_first_name ?? $order->billing_first_name }}
                {{ $order->shipping_last_name ?? $order->billing_last_name }}<br>
                {{ $order->shipping_address_1 ?? $order->billing_address_1 }}@if($order->shipping_address_2),
                {{ $order->shipping_address_2 }}@endif, {{ $order->shipping_city ?? $order->billing_city }},
                {{ $order->shipping_state ?? $order->billing_state }},
                {{ $order->shipping_postcode ?? $order->billing_postcode }},
                {{ $order->shipping_country ?? $order->billing_country }}<br>
                {{ $order->billing_phone }}
              </div>
            </td>
          </tr>
          <tr>
            <td colspan="2" style="padding:12px 0;border-top:1px solid #e6e6e6;">
              <div style="font-size:12px;color:#666;text-transform:uppercase;letter-spacing:0.5px;margin-bottom:4px;">
                Payment Method</div>
              <div style="font-size:14px;color:#111;font-weight:bold;">
                @if($order->payment_method === 'credit_card')
                  Credit Card
                @elseif($order->payment_method === 'paypal')
                  PayPal
                @elseif($order->payment_method === 'zelle')
                  Zelle (Pending)
                @elseif($order->payment_method === 'ach')
                  ACH Bank Transfer
                @elseif($order->payment_method === 'check')
                  Check (Pending)
                @else
                  {{ ucfirst(str_replace('_', ' ', $order->payment_method)) }}
                @endif
              </div>
              @if($isAchProcessing)
                <div style="font-size:12px;color:#2563eb;margin-top:4px;">⏳ Bank debit submitted — processing 1–4 business days</div>
              @elseif(in_array($order->payment_method, ['zelle', 'wire', 'bank_wire', 'cheque']))
                <div style="font-size:12px;color:#b42318;margin-top:4px;">⚠️ Payment pending - awaiting confirmation</div>
                @if($order->payment_method === 'zelle')
                  <div style="background:#fff7ed;border-radius:4px;padding:12px;margin-top:8px;text-align:center;">
                    <div style="font-size:13px;color:#9a3412;font-weight:bold;">⚡ Send
                      ${{ number_format($order->total, 2) }} via Zelle</div>
                    <div style="font-size:18px;font-weight:bold;color:#2563eb;margin:4px 0;">Sales@oasismint.com</div>
                    <div style="font-size:12px;color:#9a3412;">Due within 24 hours</div>
                  </div>
                @endif
              @endif
            </td>
          </tr>
        </table>

        <!-- Order Fees -->
        <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%"
          style="border-collapse:collapse;margin-top:16px;">
          <tr>
            <td colspan="2" style="padding:12px 0;border-bottom:1px solid #e6e6e6;">
              <h3 style="margin:0;font-size:16px;color:#111111;">Order Fees</h3>
            </td>
          </tr>
          <tr>
            <td style="padding:6px 0;font-size:14px;color:#555;">Subtotal</td>
            <td style="padding:6px 0;font-size:14px;color:#111;text-align:right;">
              ${{ number_format($order->subtotal, 2) }}</td>
          </tr>
          @if(($order->coupon_discount ?? 0) > 0)
            <tr>
              <td style="padding:6px 0;font-size:14px;color:#16a34a;">Coupon Discount
                ({{ $order->coupon_code }}{{ $order->coupon_description ? ' – ' . $order->coupon_description : '' }}) –
                This discount has been applied according to the coupon</td>
              <td style="padding:6px 0;font-size:14px;color:#16a34a;text-align:right;">
                -${{ number_format($order->coupon_discount, 2) }}</td>
            </tr>
          @endif
          <tr>
            <td style="padding:6px 0;font-size:14px;color:#555;">Shipping Fee</td>
            <td style="padding:6px 0;font-size:14px;color:#111;text-align:right;">
              ${{ number_format($order->shipping_fee, 2) }}</td>
          </tr>
          <tr>
            <td style="padding:6px 0;font-size:14px;color:#555;">State Fee</td>
            <td style="padding:6px 0;font-size:14px;color:#111;text-align:right;">
              ${{ number_format($order->state_fee, 2) }}</td>
          </tr>
          <tr>
            <td style="padding:6px 0;font-size:14px;color:#555;">Service Fee</td>
            <td style="padding:6px 0;font-size:14px;color:#111;text-align:right;">
              ${{ number_format($order->service_fee, 2) }}</td>
          </tr>
          @if($order->payment_method === 'credit_card' || $order->payment_method === 'paypal')
            <tr>
              <td style="padding:6px 0;font-size:14px;color:#555;">Credit Card Fee</td>
              <td style="padding:6px 0;font-size:14px;color:#111;text-align:right;">
                ${{ number_format($order->credit_card_fee, 2) }}</td>
            </tr>
          @endif
          <tr>
            <td style="padding:10px 0;border-top:1px dashed #e6e6e6;font-size:15px;color:#111;">Total</td>
            <td style="padding:10px 0;border-top:1px dashed #e6e6e6;font-size:18px;color:#111;text-align:right;">
              <strong>${{ number_format($order->total, 2) }}</strong>
            </td>
          </tr>
        </table>

        <!-- Order Items -->
        <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%"
          style="border-collapse:collapse;margin-top:16px;">
          <tr>
            <td colspan="4" style="padding:12px 0;border-bottom:1px solid #e6e6e6;">
              <h3 style="margin:0;font-size:16px;color:#111111;">Order Items</h3>
            </td>
          </tr>
          <tr>
            <th align="left" style="padding:8px 0;font-size:13px;color:#666;border-bottom:1px solid #eee;">Product</th>
            <th align="center" style="padding:8px 0;font-size:13px;color:#666;border-bottom:1px solid #eee;">Qty</th>
            <th align="right" style="padding:8px 0;font-size:13px;color:#666;border-bottom:1px solid #eee;">Price</th>
            <th align="right" style="padding:8px 0;font-size:13px;color:#666;border-bottom:1px solid #eee;">Subtotal
            </th>
          </tr>
          @foreach($order->items as $item)
            <tr>
              <td style="padding:8px 0;font-size:14px;color:#111;border-bottom:1px solid #f2f2f2;">{{ $item->name }}</td>
              <td align="center" style="padding:8px 0;font-size:14px;color:#111;border-bottom:1px solid #f2f2f2;">
                {{ $item->quantity }}
              </td>
              <td align="right" style="padding:8px 0;font-size:14px;color:#111;border-bottom:1px solid #f2f2f2;">
                ${{ number_format($item->price, 2) }}</td>
              <td align="right" style="padding:8px 0;font-size:14px;color:#111;border-bottom:1px solid #f2f2f2;">
                ${{ number_format($item->price * $item->quantity, 2) }}</td>
            </tr>
          @endforeach
        </table>

      </td>
    </tr>
    <tr>
      <td
        style="padding:16px 24px 24px;text-align:center;color:#8c8c8c;font-size:12px;border-bottom-left-radius:8px;border-bottom-right-radius:8px;">
        <p style="margin:8px 0;">&copy; {{ date('Y') }} {{ config('app.name', 'Oasis Mint') }}. All rights reserved.</p>
        <p style="margin:0;">
          <a href="{{ url('/') }}" style="color:#8c8c8c;text-decoration:underline;">Visit our website</a>
        </p>
      </td>
    </tr>
  </table>
</div>