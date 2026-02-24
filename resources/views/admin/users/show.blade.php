
@extends('admin.layouts.app')

@section('content')

<div class="container-xxl flex-grow-1 container-p-y">

    <!-- Back Button -->
    <div class="mb-3">
        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary btn-sm">
            <i class="fa-solid fa-arrow-left me-1"></i> Back to Users
        </a>
        <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-primary btn-sm ms-2">
            <i class="fa-solid fa-edit me-1"></i> Edit / Reset Password
        </a>
    </div>

    <div class="row">

        <!-- User Details Card -->
        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fa-solid fa-user me-2"></i>User Details</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless mb-0">
                        <tbody>
                            <tr>
                                <th class="ps-0" style="width:40%;">ID</th>
                                <td>{{ $user->id }}</td>
                            </tr>
                            <tr>
                                <th class="ps-0">Name</th>
                                <td class="text-break">{{ $user->name }}</td>
                            </tr>
                            <tr>
                                <th class="ps-0">Email</th>
                                <td class="text-break">{{ $user->email }}</td>
                            </tr>
                            <tr>
                                <th class="ps-0">Registered</th>
                                <td>{{ $user->created_at->format('M d, Y') }}</td>
                            </tr>
                            <tr>
                                <th class="ps-0">Last Updated</th>
                                <td>{{ $user->updated_at->format('M d, Y') }}</td>
                            </tr>
                            <tr>
                                <th class="ps-0">Total Orders</th>
                                <td>
                                    <span class="badge bg-label-primary">{{ $user->orders->count() }}</span>
                                </td>
                            </tr>
                            <tr>
                                <th class="ps-0">Total Spent</th>
                                <td>
                                    <strong>${{ number_format($user->orders->sum('total'), 2) }}</strong>
                                </td>
                            </tr>
                            <tr>
                                <th class="ps-0">Ship to Different Address</th>
                                <td>
                                    @if($user->allow_different_shipping)
                                        <span class="badge bg-label-success">Approved</span>
                                    @else
                                        <span class="badge bg-label-secondary">Not Allowed</span>
                                    @endif
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Purchase History Card -->
        <div class="col-md-8 mb-4">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fa-solid fa-clock-rotate-left me-2"></i>Purchase History</h5>
                </div>
                <div class="card-body p-0">
                    @if($user->orders->isEmpty())
                        <div class="p-4 text-center text-muted">
                            <i class="fa-solid fa-box-open fa-2x mb-2"></i>
                            <p class="mb-0">No orders found for this user.</p>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th>Order #</th>
                                        <th>Date</th>
                                        <th>Items</th>
                                        <th>Total</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($user->orders->sortByDesc('created_at') as $order)
                                        <tr>
                                            <td>
                                                <strong>{{ $order->id ?? '#'}}</strong>
                                            </td>
                                            <td>{{ $order->created_at->format('M d, Y') }}</td>
                                            <td>{{ $order->items->count() }}</td>
                                            <td>${{ number_format($order->total, 2) }}</td>
                                            <td>
                                                @php
                                                    $statusColors = [
                                                        'pending'   => 'bg-label-warning',
                                                        'paid'      => 'bg-label-success',
                                                        'canceled'  => 'bg-label-danger',
                                                        'shipped'   => 'bg-label-info',
                                                        'completed' => 'bg-label-success',
                                                    ];
                                                    $color = $statusColors[$order->status] ?? 'bg-label-secondary';
                                                @endphp
                                                <span class="badge {{ $color }}">{{ ucfirst($order->status) }}</span>
                                            </td>
                                            <td>
                                                <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-sm btn-outline-primary">
                                                    <i class="fa-solid fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>

    </div>

</div>

@endsection

