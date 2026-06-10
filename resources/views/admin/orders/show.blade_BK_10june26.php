@extends('admin.layouts.app')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if(session('info'))
                <div class="alert alert-info alert-dismissible fade show" role="alert">
                    {{ session('info') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                @if(session('refund_debug'))
                <script>
                (function() {
                    var debug = @json(session('refund_debug'));
                    console.groupCollapsed('%c[Refund Debug] PayPal declined the refund', 'color: #dc3545; font-weight: bold;');
                    console.log('HTTP Status:', debug.status);
                    try {
                        console.log('PayPal Response:', typeof debug.body === 'string' ? JSON.parse(debug.body || '{}') : debug.body);
                    } catch (e) {
                        console.log('Raw Body:', debug.body);
                    }
                    if (debug.name) console.log('Error Type:', debug.name);
                    if (debug.debug_id) console.log('PayPal Debug ID:', debug.debug_id, '(use when contacting PayPal support)');
                    console.groupEnd();
                })();
                </script>
                @endif
            @endif
    <div class="card">
        <div class="card-header">
            <h5>Order #{{ $order->id }}</h5>
        </div>
        <div class="card-body">
            <h6>Order Info</h6>
            <ul>
                <li>Status: {{ ucfirst($order->status) }}</li>
                <li>Payment Method: {{ ucfirst($order->payment_method) }}</li>
                @if($order->shipping_method || $order->tracking_number)
                <li>Shipping Method: {{ $order->shipping_method ?? '—' }}</li>
                <li>Tracking Number: {{ $order->tracking_number ?? '—' }}</li>
                @endif
                <li>Order Comments: {{ $order->order_comments }}</li>
                <li>Created At: {{ $order->created_at->format('d M Y') }}</li>
                <li>Transaction ID: {{ $order->transaction_id }}</li>
            </ul>

            @php
                $currentStatus = request('status', $order->status);
            @endphp

            <div>
            <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST" class="mb-4">
                @csrf
                <input type="hidden" name="from" value="show">
                <div class="row g-2 align-items-end">
                    <div class="col-md-4">
                        <label class="form-label">Update Status</label>
                        <select name="status" class="form-select" id="order-status-select">
                            <option value="pending" {{ $currentStatus === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="paid" {{ $currentStatus === 'paid' ? 'selected' : '' }}>Paid</option>
                            <option value="processed" {{ $currentStatus === 'processed' ? 'selected' : '' }}>Processed</option>
                            <option value="shipped" {{ $currentStatus === 'shipped' ? 'selected' : '' }}>Shipped</option>
                            <option value="delivered" {{ $currentStatus === 'delivered' ? 'selected' : '' }}>Delivered</option>
                            <option value="refunded" {{ $currentStatus === 'refunded' ? 'selected' : '' }}>Refunded</option>
                            <option value="partially_refunded" {{ $currentStatus === 'partially_refunded' ? 'selected' : '' }}>Partially Refunded</option>
                            <option value="canceled" {{ $currentStatus === 'canceled' ? 'selected' : '' }}>Canceled</option>
                        </select>
                    </div>
                    <div class="col-md-4" id="shipping-info-fields" style="display: none;">
                        <div class="mb-2">
                            <label class="form-label mb-1">Shipping Method</label>
                            <input
                                type="text"
                                name="shipping_method"
                                class="form-control form-control-sm"
                                placeholder="e.g. UPS Ground"
                                value="{{ old('shipping_method', $order->shipping_method) }}"
                            >
                        </div>
                        <div>
                            <label class="form-label mb-1">Tracking Number</label>
                            <input
                                type="text"
                                name="tracking_number"
                                class="form-control form-control-sm"
                                placeholder="e.g. 1Z..."
                                value="{{ old('tracking_number', $order->tracking_number) }}"
                            >
                        </div>
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </div>
            </form>
            </div>
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
                <li>Shipping Method: {{ $order->shipping_method ?? '—' }}</li>
                <li>Tracking Number: {{ $order->tracking_number ?? '—' }}</li>
            </ul>
            <script>
            (function () {
                var select = document.getElementById('order-status-select');
                var shippingFields = document.getElementById('shipping-info-fields');
                if (!select || !shippingFields) return;

                function toggleShippingFields() {
                    if (select.value === 'shipped') {
                        shippingFields.style.display = '';
                    } else {
                        shippingFields.style.display = 'none';
                    }
                }

                select.addEventListener('change', toggleShippingFields);
                toggleShippingFields();
            })();
            </script>

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
                            @if($order->payment_method === 'credit_card' || $order->payment_method === 'paypal')
                            <span>Credit Card Fee: ${{ number_format($order->credit_card_fee, 2) }}</span><br>
                            @endif
                            <strong>Total: ${{ number_format($order->total, 2) }}</strong>
                        </td>
                    </tr>
                </tbody>
            </table>
            {{-- Refunds & Cancellations --}}
            @if(in_array($order->status, ['paid', 'processed', 'shipped', 'partially_refunded']))
            <div class="card border mt-4" id="refunds">
                <div class="card-header py-2 bg-light">
                    <h6 class="mb-0">Refunds & Cancellations</h6>
                </div>
                <div class="card-body pt-3">
                    @if($canRefundViaPayPal)
                        @php
                            $remaining = (float) $order->total - (float) ($order->refunded_amount ?? 0);
                        @endphp
                        @if($remaining > 0)
                            <div class="d-flex flex-wrap gap-2 mb-3">
                                <!-- <form action="{{ route('admin.orders.cancel', $order->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Cancel this order and issue a full refund?');">
                                    @csrf
                                    <input type="hidden" name="reason" value="Order canceled by admin">
                                    <button type="submit" class="btn btn-warning btn-sm">Cancel Order (Full Refund)</button>
                                </form> -->
                                <form action="{{ route('admin.orders.refund', $order->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Issue full refund of ${{ number_format($remaining, 2) }}?');">
                                    @csrf
                                    <button type="submit" class="btn btn-secondary btn-sm">Full Refund (${{ number_format($remaining, 2) }})</button>
                                </form>
                            </div>
                            @if($remaining > 0)
                            <form action="{{ route('admin.orders.refund', $order->id) }}" method="POST" class="border-top pt-3 refund-partial-form" enctype="application/x-www-form-urlencoded">
                                @csrf
                                <h6 class="mb-2">Partial Refund</h6>
                                <div class="row g-2 align-items-end">
                                    <div class="col-md-3">
                                        <label class="form-label">Amount ($)</label>
                                        <input type="number" name="amount" class="form-control form-control-sm" step="0.01" min="0.01" max="{{ number_format($remaining, 2, '.', '') }}" placeholder="0.00" required>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Reason (optional)</label>
                                        <input type="text" name="reason" class="form-control form-control-sm" placeholder="e.g. Partial refund for damaged item">
                                    </div>
                                    <div class="col-md-3">
                                        <button type="submit" class="btn btn-primary btn-sm btn-refund-submit">Issue Partial Refund</button>
                                    </div>
                                </div>
                                <div class="form-text">Max: ${{ number_format($remaining, 2) }}</div>
                            </form>
                            <script>
                            document.querySelector('.refund-partial-form')?.addEventListener('submit', function() {
                                var btn = this.querySelector('.btn-refund-submit');
                                if (btn) btn.disabled = true;
                            });
                            </script>
                            @endif
                        @else
                            <p class="text-muted mb-0">This order has been fully refunded.</p>
                        @endif
                    @else
                        <p class="text-muted mb-0">
                            @if(empty($order->transaction_id))
                                Refund via API is not available — no PayPal transaction ID. Process refund manually if needed.
                            @else
                                Refund via API is not available for this payment method. Process refund manually if needed.
                            @endif
                        </p>
                    @endif
                    @if($order->refunds->isNotEmpty())
                        <div class="mt-3">
                            <h6>Refund History</h6>
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Type</th>
                                        <th>Amount</th>
                                        <th>Reason</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($order->refunds as $refund)
                                    <tr>
                                        <td>{{ $refund->created_at->format('M d, Y H:i') }}</td>
                                        <td>{{ ucfirst($refund->refund_type) }}</td>
                                        <td>${{ number_format($refund->amount, 2) }}</td>
                                        <td>{{ $refund->reason ?? '—' }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                    @if($order->refunded_amount > 0)
                        <p class="mb-0 small text-muted">Refunded so far: ${{ number_format($order->refunded_amount ?? 0, 2) }} of ${{ number_format($order->total, 2) }}</p>
                    @endif
                </div>
            </div>
            @endif

            {{-- Admin Notes --}}
            <div class="card border mt-4">
                <div class="card-header py-2">
                    <h6 class="mb-0">Admin Notes</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.orders.updateNotes', $order->id) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <textarea name="admin_notes" class="form-control" rows="4" maxlength="5000"
                                placeholder="Internal notes — not visible to the customer…">{{ old('admin_notes', $order->admin_notes) }}</textarea>
                            <div class="form-text text-muted">Max 5,000 characters. Never shown to customers.</div>
                        </div>
                        <button type="submit" class="btn btn-primary btn-sm">Save Notes</button>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection 