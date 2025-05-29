@extends('admin.layouts.app')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="card">
        <div class="row">
            <div class="col-md-6">
                <h5 class="card-header">Edit State Fee</h5>
            </div>
            <div class="col-md-6">
                <div style="text-align: end;">
                    <a href="{{ route('admin.statefee.index') }}" class="btn btn-primary mt-3">All State Fees</a>
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
            <form action="{{ route('admin.statefee.update', $stateFee) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label>Name</label>
                        <input type="text" disabled  name="name" class="form-control" value="{{ $stateFee->name }}">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label>Code</label>
                        <input type="text" disabled name="code" class="form-control" value="{{ $stateFee->code }}">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label>Amount</label>
                        <input type="number" step="0.01" name="amount" class="form-control" value="{{ $stateFee->amount }}">
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">Update</button>
                        <a href="{{ route('admin.statefee.index') }}" class="btn btn-secondary">Cancel</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection 