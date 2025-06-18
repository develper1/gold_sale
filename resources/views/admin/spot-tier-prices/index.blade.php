@extends('admin.layouts.app')

@section('page-title', 'Spot Tier Pricing')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Spot Tier Pricing</h5>
            <a href="{{ route('admin.spot-tier-prices.create') }}" class="btn btn-primary">Add Spot Tier Price</a>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Tier Start</th>
                            <th>Tier End</th>
                            <th>Type</th>
                            <th>Value</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($spotTierPrices as $spotTierPrice)
                        <tr>
                            <td>{{ $spotTierPrice->tier_start }}</td>
                            <td>{{ $spotTierPrice->tier_end }}</td>
                            <td>{{ ucfirst($spotTierPrice->type) }}</td>
                            <td>{{ $spotTierPrice->value }}</td>
                            <td>
                                <a href="{{ route('admin.spot-tier-prices.edit', $spotTierPrice->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                <form action="{{ route('admin.spot-tier-prices.destroy', $spotTierPrice->id) }}" method="POST" style="display:inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                {{ $spotTierPrices->links() }}
            </div>
        </div>
    </div>
</div>
@endsection 