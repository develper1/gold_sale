@extends('admin.layouts.app')

@section('content')

<div class="container-xxl flex-grow-1 container-p-y">
    <div class="card">
        <div class="row">
            <div class="col-md-6">
                <h5 class="card-header">Orders</h5>
            </div>
        </div>
        <div class="table-responsive text-nowrap">
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Customer</th>
                        <th>Email</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Created At</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @foreach($orders as $order)
                    <tr>
                        <td>{{ $order->id }}</td>
                        <td>{{ $order->billing_first_name }} {{ $order->billing_last_name }}</td>
                        <td>{{ $order->billing_email }}</td>
                        <td>${{ number_format($order->total, 2) }}</td>
                        <td>{{ ucfirst($order->status) }}</td>
                        <td>{{ $order->created_at->format('d M Y') }}</td>
                        <td>
                            <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-sm btn-info">View</a>
                            @if(in_array($order->status, ['paid', 'processed', 'shipped', 'partially_refunded']) && $order->transaction_id)
                            <a href="{{ route('admin.orders.show', $order->id) }}#refunds" class="btn btn-sm btn-outline-secondary">Refund</a>
                            @endif
                                <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST" style="display:inline-block;">
                                    @csrf
                                    <input type="hidden" name="from" value="index">
                                    <select name="status" class="form-select form-select-sm d-inline w-auto me-1">
                                        <option value="pending"  {{ $order->status === 'pending'  ? 'selected' : '' }}>Pending</option>
                                        <option value="paid"     {{ $order->status === 'paid'     ? 'selected' : '' }}>Paid</option>
                                        <option value="processed" {{ $order->status === 'processed' ? 'selected' : '' }}>Processed</option>
                                        <option value="shipped"  {{ $order->status === 'shipped'  ? 'selected' : '' }}>Shipped</option>
                                        <option value="delivered" {{ $order->status === 'delivered' ? 'selected' : '' }}>Delivered</option>
                                        <option value="refunded" {{ $order->status === 'refunded' ? 'selected' : '' }}>Refunded</option>
                                        <option value="partially_refunded" {{ $order->status === 'partially_refunded' ? 'selected' : '' }}>Partially Refunded</option>
                                        <option value="canceled" {{ $order->status === 'canceled' ? 'selected' : '' }}>Canceled</option>
                                    </select>
                                    <button type="submit" class="btn btn-sm btn-primary">Update</button>
                                </form>
                            <form action="{{ route('admin.orders.destroy', $order->id) }}" method="POST" style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this order?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-3 ms-3">
            {{ $orders->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>
@endsection 