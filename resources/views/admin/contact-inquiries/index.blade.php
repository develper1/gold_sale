@extends('admin.layouts.app')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="card">
        <h5 class="card-header">Contact Forms</h5>
        <hr class="my-0">
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif

            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Message Preview</th>
                            <th>Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @forelse($inquiries as $inquiry)
                            <tr>
                                <td>{{ $inquiry->id }}</td>
                                <td>{{ $inquiry->name }}</td>
                                <td>
                                    <a href="mailto:{{ $inquiry->email }}">{{ $inquiry->email }}</a>
                                </td>
                                <td>{{ Str::limit($inquiry->message, 60) }}</td>
                                <td>{{ $inquiry->created_at->format('Y-m-d H:i') }}</td>
                                <td>
                                    <a href="{{ route('admin.contact-inquiries.show', $inquiry->id) }}" class="btn btn-sm btn-info">
                                        <i class="ti ti-eye me-1"></i> View
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">No contact inquiries yet.</td>
                            </tr>
                        @endforelse
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
        @if($inquiries->isNotEmpty())
        $('.table').DataTable({
            order: [[4, 'desc']],
            columnDefs: [
                { orderable: false, targets: 5 }
            ]
        });
        @endif
    });
</script>
@endpush
