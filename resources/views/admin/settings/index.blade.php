@extends('admin.layouts.app')

@section('page-title', 'Settings')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="card">
        <div class="card-header">
            <h5>Settings</h5>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            <form action="{{ route('admin.settings.update', 1) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label for="credit_card_percentage" class="form-label">Credit Card Amount (%)</label>
                    <input type="number" step="0.01" min="0" max="100" name="credit_card_percentage" id="credit_card_percentage" class="form-control @error('credit_card_percentage') is-invalid @enderror" value="{{ old('credit_card_percentage', $credit_card_percentage) }}" required>
                    @error('credit_card_percentage')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <button type="submit" class="btn btn-primary">Update</button>
            </form>
        </div>
    </div>
</div>
@endsection 