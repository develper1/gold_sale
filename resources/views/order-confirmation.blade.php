@extends('layouts.app')

@section('content')
    <div class="container py-5">
        <h1>Order Confirmation</h1>
        <p>Thank you for your order! Your order number is <strong
                style="font-size: 15px; font-weight: bold; color: #2563eb; letter-spacing: 1px;">{{ $order->id }}</strong>.
        </p>
        @php
            $isPaidAtOrder = in_array($order->payment_method, ['credit_card', 'paypal']);
            $isBankPaymentProcessing = in_array($order->payment_method, ['ach', 'echeck']);
            $isBankWireStripe = ($order->payment_method === 'bank_wire');

            $wireDetails = null;
            if ($isBankWireStripe && !empty($order->stripe_bank_name)) {
                $wireDetails = json_decode($order->stripe_bank_name, true);
            }

            $paymentMethodNames = [
                'credit_card' => 'Credit Card (via Stripe)',
                'paypal' => 'Paypal (via Paypal)',
                'ach' => 'ACH (via Stripe)',
                'echeck' => 'Echeck (via Stripe)',
                'bank_wire' => 'Bank Wire (via Stripe)',
                'zelle' => 'Zelle',
                'cheque' => 'Certified Check'
            ];
            $friendlyPaymentMethod = $paymentMethodNames[$order->payment_method] ?? ucfirst($order->payment_method);
        @endphp

        @if($isPaidAtOrder)
            <p class="text-success"><strong>Thank you! Your payment has been received.</strong> We will process your order
                shortly.</p>
        @elseif($isBankPaymentProcessing)
            <div
                style="background-color: #d1fae5; border: 1px solid #34d399; color: #065f46; padding: 15px; border-radius: 4px; margin-bottom: 20px;">
                <h4 style="margin: 0 0 5px; font-weight: bold;"><i class="fa fa-university"></i> Bank Payment Processing</h4>
                <p style="margin: 0; font-size: 14px;">Your bank account payment has been successfully initiated. Please note
                    that ACH/eCheck transfers take 3-5 business days to clear. Your order will be processed once the payment has
                    settled.</p>
            </div>
        @elseif($isBankWireStripe && $wireDetails)
            <div
                style="background-color: #f0fdf4; border: 1px solid #bbf7d0; color: #166534; padding: 20px 32px; border-radius: 6px; margin-bottom: 20px;">
                <h4 style="margin: 0 0 10px; font-weight: bold;"><i class="fa fa-university"></i> Stripe Bank Wire Instructions
                </h4>
                <p style="margin: 0 0 15px; font-size: 14px; color: #1e293b;">To complete your order, please initiate a wire
                    transfer from your bank using the virtual routing and account details below. <strong>You must include the
                        Memo / Reference code to ensure automated payment matching.</strong></p>
                <table
                    style="width: 100%; max-width: 500px; border-collapse: collapse; font-size: 14px; color: #334155; margin-bottom: 10px; margin-left: 10px;">
                    <tr style="border-bottom: 1px solid #e2e8f0;">
                        <td style="padding: 8px 0; font-weight: 600;padding-left:8px">Bank Name:</td>
                        <td style="padding: 8px 0;padding-left:8px">{{ $wireDetails['bank_name'] ?? 'Stripe Virtual Bank' }}
                        </td>
                    </tr>
                    <tr style="border-bottom: 1px solid #e2e8f0;">
                        <td style="padding: 8px 0; font-weight: 600;padding-left:8px">Routing Number (ABA):</td>
                        <td style="padding: 8px 0;padding-left:8px">{{ $wireDetails['routing_number'] ?? '' }}</td>
                    </tr>
                    <tr style="border-bottom: 1px solid #e2e8f0;">
                        <td style="padding: 8px 0; font-weight: 600;padding-left:8px">Account Number:</td>
                        <td style="padding: 8px 0;padding-left:8px">{{ $wireDetails['account_number'] ?? '' }}</td>
                    </tr>
                    <tr style="border-bottom: 1px solid #e2e8f0;">
                        <td style="padding: 8px 0; font-weight: 600; color: #dc2626;padding-left:8px">Memo / Reference
                            Code:</td>
                        <td style="padding: 8px 0; font-weight: bold; color: #dc2626;padding-left:8px">{{
                $wireDetails['reference'] ?? '' }}</td>
                    </tr>
                </table>
                <p style="margin: 15px 0 0; font-size: 12px; color: #64748b; font-style: italic; margin-left: 10px;">Note: Wire
                    transfers typically
                    clear within 1 business day. Your order status will update to Paid as soon as the transfer completes.</p>
            </div>
        @else
            <p>
                <strong>
                    To complete payment for your order, follow instructions received in your
                    email confirmation. You must contact our office with your order number
                    within <strong>24 hours</strong> with any questions to affect payment. Payment must be
                    received within <strong>48 hours</strong> or the order will be automatically canceled.
                </strong>
            </p>

            @if($order->payment_method === 'zelle')
                <p>
                    Payment via Zelle must be sent to
                    <a href="mailto:Sales@oasismint.com">Sales@oasismint.com</a>
                    within 24 hours of Order Confirmation.
                </p>
            @endif
        @endif

        <hr>
        <p>If any payment — including credit card, ACH, or eCheck — is returned unpaid or charged back, you authorize us to
            debit your account for the full outstanding amount, plus a $50 service fee, along with any fees assessed by your
            financial institution.</p>


        <h3>Order Details</h3>
        <ul>
            <li>Name: {{ $order->billing_first_name }} {{ $order->billing_last_name }}</li>
            <li>Email: {{ $order->billing_email }}</li>
            <li>Payment Method: {{ $friendlyPaymentMethod }}</li>
            <li>Status: {{ $order->status === 'ach_pending' ? 'Pending Bank Settlement' : ucfirst($order->status) }}</li>
        </ul>
        <h3>Billing Info</h3>
        <ul>
            <li>Name: {{ $order->billing_first_name }} {{ $order->billing_last_name }}</li>
            <li>Email: {{ $order->billing_email }}</li>
            <li>Phone: {{ $order->billing_phone }}</li>
            <li>Address: {{ $order->billing_address_1 }}@if($order->billing_address_2),
            {{ $order->billing_address_2 }}@endif, {{ $order->billing_city }}, {{ $order->billing_state }},
                {{ $order->billing_postcode }}, {{ $order->billing_country }}
            </li>
        </ul>
        <h3>Shipping Info</h3>
        <ul>
            <li>Name: {{ $order->shipping_first_name }} {{ $order->shipping_last_name }}</li>
            <li>Company: {{ $order->shipping_company }}</li>
            <li>Address: {{ $order->shipping_address_1 }}@if($order->shipping_address_2),
            {{ $order->shipping_address_2 }}@endif, {{ $order->shipping_city }}, {{ $order->shipping_state }},
                {{ $order->shipping_postcode }}, {{ $order->shipping_country }}
            </li>
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
                        @if(($order->coupon_discount ?? 0) > 0)
                            <span class="text-success">Coupon Discount
                                ({{ $order->coupon_code }}{{ $order->coupon_description ? ' – ' . $order->coupon_description : '' }})
                                – This discount has been applied according to the coupon:
                                -${{ number_format($order->coupon_discount, 2) }}</span><br>
                        @endif
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