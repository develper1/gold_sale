@extends('admin.layouts.app')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="card">
        <div class="row">
            <div class="col-md-6">
                <h5 class="card-header">Edit Service Fee</h5>
            </div>
            <div class="col-md-6">
                <div style="text-align: end;">
                    <a href="{{ route('admin.services.index') }}" class="btn btn-primary mt-3">All Service Fees</a>
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
            <form action="{{ route('admin.services.update', $service) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label>Order Amount</label>
                        <input type="number" step="0.01" name="order_amount" class="form-control" value="{{ $service->order_amount }}" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>Service Fee</label>
                        <input type="number" step="0.01" name="services_fee" class="form-control" value="{{ $service->services_fee }}" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">Update</button>
                        <a href="{{ route('admin.services.index') }}" class="btn btn-secondary">Cancel</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection 