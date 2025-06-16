@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Add New Price Tier Range</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.price-tier-ranges.store') }}" method="POST">
                        @csrf
                        
                        <div class="form-group">
                            <label for="tier_start">Tier Start</label>
                            <input type="number" 
                                   class="form-control @error('tier_start') is-invalid @enderror" 
                                   id="tier_start" 
                                   name="tier_start" 
                                   value="{{ old('tier_start') }}" 
                                   min="0" 
                                   required>
                            @error('tier_start')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group mt-2">
                            <label for="tier_end">Tier End</label>
                            <input type="number" 
                                   class="form-control @error('tier_end') is-invalid @enderror" 
                                   id="tier_end" 
                                   name="tier_end" 
                                   value="{{ old('tier_end') }}" 
                                   min="0" 
                                   >
                            @error('tier_end')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group mt-2">
                            <label for="tier_price">Tier Price</label>
                            <input type="number" 
                                   class="form-control @error('tier_price') is-invalid @enderror" 
                                   id="tier_price" 
                                   name="tier_price" 
                                   value="{{ old('tier_price') }}" 
                                   min="0" 
                                   step="0.01" 
                                   required>
                            @error('tier_price')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group mt-2">
                            <button type="submit" class="btn btn-primary">Create Price Tier Range</button>
                            <a href="{{ route('admin.price-tier-ranges.index') }}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 