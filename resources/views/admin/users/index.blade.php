
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
            <div class="col-md-6">
                <form action="{{ route('admin.users.index') }}" method="GET" class="d-flex gap-2 align-items-center justify-content-end mt-3 me-3">
                    <input type="text" name="search" class="form-control" style="max-width: 240px;" placeholder="Search by name or email..." value="{{ request('search') }}">
                    <input type="hidden" name="sort_by" value="{{ $sortBy }}">
                    <input type="hidden" name="sort_dir" value="{{ $sortDir }}">
                    <button type="submit" class="btn btn-primary">Search</button>
                </form>
            </div>
        </div>

        @php
            $sortUrl = function(string $col) use ($sortBy, $sortDir) {
                $dir = ($sortBy === $col && $sortDir === 'asc') ? 'desc' : 'asc';
                return request()->fullUrlWithQuery(['sort_by' => $col, 'sort_dir' => $dir]);
            };
            $sortIcon = function(string $col) use ($sortBy, $sortDir) {
                if ($sortBy !== $col) return '<span style="opacity:.3;font-size:.75rem;">⇅</span>';
                return $sortDir === 'asc'
                    ? '<span style="font-size:.75rem;">▲</span>'
                    : '<span style="font-size:.75rem;">▼</span>';
            };
        @endphp
        <div class="table-responsive text-nowrap">
            <table class="table">
                <thead>
                    <tr>
                        <th>
                            <a href="{{ $sortUrl('id') }}" class="text-body text-decoration-none">
                                ID {!! $sortIcon('id') !!}
                            </a>
                        </th>
                        <th>
                            <a href="{{ $sortUrl('name') }}" class="text-body text-decoration-none">
                                Name {!! $sortIcon('name') !!}
                            </a>
                        </th>
                        <th>
                            <a href="{{ $sortUrl('email') }}" class="text-body text-decoration-none">
                                Email {!! $sortIcon('email') !!}
                            </a>
                        </th>
                        <th>Ship Country</th>
                        <th>
                            <a href="{{ $sortUrl('created_at') }}" class="text-body text-decoration-none">
                                Registered {!! $sortIcon('created_at') !!}
                            </a>
                        </th>
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
                            <td>{{ $data->shipping_country ?: '-' }}</td>
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
