@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Price Tier Ranges</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.price-tier-ranges.create') }}" class="btn btn-primary btn-sm">
                            Add New Price Tier Range
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Tier Start</th>
                                <th>Tier End</th>
                                <th>Tier Price</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($priceTierRanges as $priceTierRange)
                                <tr>
                                    <td>{{ $priceTierRange->id }}</td>
                                    <td>{{ $priceTierRange->tier_start }}</td>
                                    <td>{{ $priceTierRange->tier_end }}</td>
                                    <td>${{ number_format($priceTierRange->tier_price, 2) }}</td>
                                    <td>
                                        <a href="{{ route('admin.price-tier-ranges.edit', $priceTierRange) }}" 
                                           class="btn btn-info btn-sm">
                                            Edit
                                        </a>
                                        <form action="{{ route('admin.price-tier-ranges.destroy', $priceTierRange) }}" 
                                              method="POST" 
                                              class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="btn btn-danger btn-sm" 
                                                    onclick="return confirm('Are you sure you want to delete this price tier range?')">
                                                Delete
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 