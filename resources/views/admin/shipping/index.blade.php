@extends('admin.layouts.app')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="card">
        <div class="row">
            <div class="col-md-6">
                <h5 class="card-header">Shipping List</h5>
            </div>
            <div class="col-md-6">
                <div style="text-align: end;">
                    <a href="{{ route('admin.shipping.create') }}" class="btn btn-primary mt-3">Add New Shipping</a>
                </div>
            </div>
        </div>
        <hr class="my-0">
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Order Amount</th>
                            <th>Shipping Charges</th>
                            <th>Created At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($shippings as $shipping)
                            <tr>
                                <td>{{ $shipping->id }}</td>
                                <td>${{ number_format($shipping->order_amount, 2) }}</td>
                                <td>${{ number_format($shipping->shipping_charges, 2) }}</td>
                                <td>{{ $shipping->created_at->format('Y-m-d H:i:s') }}</td>
                                <td>
                                    <a href="{{ route('admin.shipping.edit', $shipping) }}" class="btn btn-sm btn-primary">Edit</a>
                                    <form action="{{ route('admin.shipping.destroy', $shipping) }}" method="POST" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this shipping record?')">Delete</button>
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
@endsection
@push('scripts')
<script>
    $(document).ready(function() {
        $('.table').DataTable();
    });
    function confirmDelete(id, url) {
        if (confirm('Are you sure you want to delete this item?')) {
            document.getElementById(`form-${id}`).submit();
        }
    }    
</script>
@endpush