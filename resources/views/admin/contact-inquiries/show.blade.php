@extends('admin.layouts.app')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5>Contact Inquiry #{{ $inquiry->id }}</h5>
            <a href="{{ route('admin.contact-inquiries.index') }}" class="btn btn-sm btn-outline-secondary">
                <i class="ti ti-arrow-left me-1"></i> Back to List
            </a>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <table class="table table-borderless">
                        <tr>
                            <th class="text-muted" style="width: 120px;">Name</th>
                            <td>{{ $inquiry->name }}</td>
                        </tr>
                        <tr>
                            <th class="text-muted">Email</th>
                            <td>
                                <a href="mailto:{{ $inquiry->email }}">{{ $inquiry->email }}</a>
                            </td>
                        </tr>
                        <tr>
                            <th class="text-muted">Submitted</th>
                            <td>{{ $inquiry->created_at->format('F j, Y \a\t g:i A') }}</td>
                        </tr>
                        @if($inquiry->ip_address)
                        <tr>
                            <th class="text-muted">IP Address</th>
                            <td>{{ $inquiry->ip_address }}</td>
                        </tr>
                        @endif
                    </table>
                </div>
            </div>
            <hr>
            <h6 class="text-muted mb-2">Message</h6>
            <div class="border rounded p-4 bg-light">
                <p class="mb-0" style="white-space: pre-wrap;">{{ $inquiry->message }}</p>
            </div>
        </div>
    </div>
</div>
@endsection
