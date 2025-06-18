@extends('admin.layouts.app')

@section('page-title', 'Edit Spot Tier Price')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Edit Spot Tier Price</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.spot-tier-prices.update', $spotTierPrice->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label for="tier_start" class="form-label">Tier Start</label>
                    <input type="number" name="tier_start" id="tier_start" class="form-control" value="{{ $spotTierPrice->tier_start }}" required>
                </div>
                <div class="mb-3">
                    <label for="tier_end" class="form-label">Tier End</label>
                    <input type="number" name="tier_end" id="tier_end" class="form-control" value="{{ $spotTierPrice->tier_end }}" required>
                </div>
                <div class="mb-3">
                    <label for="type" class="form-label">Type</label>
                    <select name="type" id="type" class="form-control" required>
                        <option value="percentage" {{ $spotTierPrice->type == 'percentage' ? 'selected' : '' }}>Percentage (%)</option>
                        <option value="fixed" {{ $spotTierPrice->type == 'fixed' ? 'selected' : '' }}>Fixed Amount</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="value" class="form-label">Value</label>
                    <input type="number" step="0.01" name="value" id="value" class="form-control" value="{{ $spotTierPrice->value }}" required>
                </div>
                <button type="submit" class="btn btn-primary">Update</button>
            </form>
        </div>
    </div>
</div>
@endsection 