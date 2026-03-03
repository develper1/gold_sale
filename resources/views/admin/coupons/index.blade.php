@extends('admin.layouts.app')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="card">
        <div class="row">
            <div class="col-md-6">
                <h5 class="card-header">Coupons</h5>
            </div>
            <div class="col-md-6">
                <div style="text-align: end;">
                    <a href="{{ route('admin.coupons.create') }}"  class="btn btn-primary mt-3" style="margin-right: 5px;">Add Coupon</a>
                </div>
            </div>
            <div class="col-md-12 card-header">
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
            </div>
        </div>
        <div class="card-body">
            <table class="table table-striped">
                <thead>
                    <tr>
                    <th>ID</th>
                    <th>Code</th>
                    <th>Description</th>
                    <th>Valid From</th>
                    <th>Valid To</th>
                    <th>Discount</th>
                    <th>Free Shipping</th>
                    <th>Free Service</th>
                    <th>Active</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($coupons as $coupon)
                    <tr>
                        <td>{{ $coupon->id }}</td>
                        <td>{{ $coupon->code }}</td>
                        <td>{{ $coupon->description }}</td>
                        <td>{{ $coupon->valid_from }}</td>
                        <td>{{ $coupon->valid_to }}</td>
                        <td>
                            @if($coupon->discount_type === 'percent' && $coupon->discount)
                                {{ number_format($coupon->discount, 0) }}% off
                            @elseif($coupon->discount_type === 'dollar' && $coupon->discount)
                                ${{ number_format($coupon->discount, 2) }} off
                            @else
                                -
                            @endif
                        </td>
                        <td>{{ $coupon->free_shipping ? 'Yes' : 'No' }}</td>
                        <td>{{ $coupon->free_service_fee ? 'Yes' : 'No' }}</td>
                        <td>{{ $coupon->is_active ? 'Yes' : 'No' }}</td>
                        <td>
                            <div class="dropdown">
                                <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="ti ti-dots-vertical"></i></button>
                                <div class="dropdown-menu">
                                    <form id="form-{{ $coupon->id }}" action="{{ route('admin.coupons.destroy', $coupon->id) }}" method="POST">
                                        @method('DELETE')
                                        @csrf
                                        <a class="dropdown-item delete-btn" onclick="confirmDelete({{ $coupon->id }})" href="javascript:void(0)">
                                            <i class="fa-solid fa-trash me-1"></i> Delete
                                        </a>
                                    </form>
                                <a class="dropdown-item "   href="{{ route('admin.coupons.edit', $coupon->id) }}"><i class="fa-solid fa-edit me-1"></i> Edit</a>
                                </div>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
            </table>
        </div>
    </div>
</div>
@endsection 
@push('scripts')
<script>
$(document).ready(function() {
    $('.table').DataTable();
});
function confirmDelete(id) {
    if (confirm('Are you sure you want to delete this item?')) {
        document.getElementById(`form-${id}`).submit();
    }
}    
</script>
@endpush