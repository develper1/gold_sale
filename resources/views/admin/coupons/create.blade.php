@extends('admin.layouts.app')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="card">
        <div class="row">
            <div class="col-md-6">
                <h5 class="card-header">Add Coupon</h5>
            </div>
            <div class="col-md-6">
                <div style="text-align: end;">
                    <a href="{{ route('admin.coupons.index') }}" target="_blank" class="btn btn-primary mt-3" style="margin-right: 5px;">All Coupons</a>
                </div>
            </div>
        </div>
        <hr class="my-0">
        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <form action="{{ route('admin.coupons.store') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label>Coupon Code</label>
                        <input type="text" name="code" class="form-control" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>Description</label>
                        <input type="text" name="description" class="form-control">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label>Discount</label>
                        <input type="number" step="0.01" name="discount" class="form-control" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>Order Total</label>
                        <input type="number" step="0.01" name="order_total" class="form-control">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label>Percent or Dollar Coupon</label>
                        <select name="discount_type" class="form-control">
                            <option value="percent">Percent</option>
                            <option value="dollar">Dollar</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>If Product Specific, Select Products</label>
                        <select name="product_id" id="product_id" class="form-control" multiple>
                            <option value="">Search for products...</option>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <div class="form-check">
                            <input type="checkbox" name="is_active" value="1" class="form-check-input" id="is_active" checked>
                            <label class="form-check-label" for="is_active">Active</label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">Save</button>
                        <a href="{{ route('admin.coupons.index') }}" class="btn btn-secondary">Cancel</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
$(document).ready(function() {
    $('#product_id').select2({
        placeholder: 'Search products...',
        allowClear: true,
        multiple: false,    
        minimumInputLength: 1,
        ajax: {
            url: '{{ route("admin.products.search") }}',
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    q: params.term
                };
            },
            processResults: function (data) {
                return {
                    results: data.map(function(item) {
                        return { id: item.id, text: item.name };
                    })
                };
            },
            cache: true
        }
    });
});
</script>
@endpush
@endsection 