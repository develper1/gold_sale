
@extends('admin.layouts.app')

@section('content')

<div class="container-xxl flex-grow-1 container-p-y">

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Users List Table -->
    <div class="card">
        <div class="row">
            <div class="col-md-6">
                <h5 class="card-header">Users</h5>
            </div>
        </div>

        <div class="table-responsive text-nowrap">
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Registered</th>
                        <th>Orders</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">

                    @foreach($users as $data)
                        <tr>
                            <td>{{ $data->id }}</td>
                            <td>{{ $data->name }}</td>
                            <td>{{ $data->email }}</td>
                            <td>{{ $data->created_at->format('M d, Y') }}</td>
                            <td>
                                <span class="badge bg-label-primary">{{ $data->orders_count }}</span>
                            </td>
                            <td>
                                <div class="dropdown">
                                    <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                        <i class="ti ti-dots-vertical"></i>
                                    </button>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item" href="{{ route('admin.users.show', $data->id) }}">
                                            <i class="fa-solid fa-eye me-1"></i> View Details
                                        </a>
                                        <a class="dropdown-item" href="{{ route('admin.users.edit', $data->id) }}">
                                            <i class="fa-solid fa-edit me-1"></i> Edit / Reset Password
                                        </a>
                                        <form id="form-{{ $data->id }}" action="{{ route('admin.users.destroy', $data->id) }}" method="POST">
                                            @method('DELETE')
                                            @csrf
                                            <a class="dropdown-item text-danger" onclick="confirmDelete({{ $data->id }})" href="javascript:void(0)">
                                                <i class="fa-solid fa-trash me-1"></i> Delete
                                            </a>
                                        </form>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach

                </tbody>
            </table>
        </div>
    </div>

</div>

@endsection

@push('scripts')
<script>
    function confirmDelete(id) {
        if (confirm('Are you sure you want to delete this user? This action cannot be undone.')) {
            document.getElementById('form-' + id).submit();
        }
    }
</script>
@endpush
