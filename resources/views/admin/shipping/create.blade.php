@extends('admin.layouts.app')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="card">
        <div class="row">
            <div class="col-md-6">
                <h5 class="card-header">Add Shipping</h5>
            </div>
            <div class="col-md-6">
                <div style="text-align: end;">
                    <a href="{{ route('admin.shipping.index') }}" class="btn btn-primary mt-3">All Shipping</a>
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
            <form action="{{ route('admin.shipping.store') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label>Order Amount</label>
                        <input type="number" step="0.01" name="order_amount" class="form-control" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>Shipping Charges</label>
                        <input type="number" step="0.01" name="shipping_charges" class="form-control" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">Save</button>
                        <a href="{{ route('admin.shipping.index') }}" class="btn btn-secondary">Cancel</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection 