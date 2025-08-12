@extends('admin.layouts.app')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="card">
        <div class="row">
            <div class="col-md-6">
                <h5 class="card-header">State Fee List</h5>
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
                            <th>Name</th>
                            <th>Code</th>
                            <th>Fee Type</th>
                            <th>Amount</th>
                            <th>Updated At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($stateFees as $stateFee)
                            <tr>
                                <td>{{ $stateFee->id }}</td>
                                <td>{{ $stateFee->name }}</td>
                                <td>{{ $stateFee->code }}</td>
                                <td>
                                    <span class="badge bg-{{ $stateFee->fee_type === 'percentage' ? 'info' : 'primary' }}">
                                        {{ ucfirst($stateFee->fee_type) }}
                                    </span>
                                </td>
                                <td>
                                    @if($stateFee->fee_type === 'percentage')
                                        {{ number_format($stateFee->amount, 2) }}%
                                    @else
                                        ${{ number_format($stateFee->amount, 2) }}
                                    @endif
                                </td>
                                <td>{{ $stateFee->updated_at ? $stateFee->updated_at->format('Y-m-d H:i:s') : 'N/A' }}</td>
                                <td>
                                    <a href="{{ route('admin.statefee.edit', $stateFee) }}" class="btn btn-sm btn-primary">Edit</a>
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
</script>
@endpush 