@extends('admin.layouts.app')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="card">
        <div class="row">
            <div class="col-md-6">
                <h5 class="card-header">Subscriber List</h5>
            </div>
            <div class="col-md-6 text-end mt-3">
                <button type="button" id="bulk-delete-btn" class="btn btn-danger" disabled title="Select subscribers to delete">
                    <i class="ti ti-trash me-1"></i> Delete Selected
                </button>
                <a href="{{ route('admin.subscribers.export') }}" class="btn btn-primary">
                    <i class="ti ti-file-export me-1"></i> Export CSV
                </a>
            </div>
        </div>
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

            <form id="bulk-delete-form" action="{{ route('admin.subscribers.bulkDelete') }}" method="POST">
                @csrf
            <div class="table-responsive">
                <table class="table table-striped">
                <thead>
                    <tr>
                        <th style="width: 40px;">
                            <input type="checkbox" id="select-all" title="Select all">
                        </th>
                        <th>ID</th>
                        <th>Email</th>
                        <th>Name</th>
                        <th>City</th>
                        <th>Investment Type</th>
                        <th>Investment Criteria</th>
                        <th>Contact Preference</th>
                        <th>Mobile Number</th>
                        <th>Created At</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @foreach($subscribers as $subscriber)
                        <tr>
                            <td>
                                <input type="checkbox" class="subscriber-checkbox" name="ids[]" value="{{ $subscriber->id }}">
                            </td>
                            <td>{{ $subscriber->id }}</td>
                            <td>{{ $subscriber->email }}</td>
                            <td>
                                @if($subscriber->details)
                                    {{ $subscriber->details->first_name }} {{ $subscriber->details->last_name }}
                                @else
                                    -
                                @endif
                            </td>
                            <td>
                                @if($subscriber->details)
                                    {{ $subscriber->details->city }}
                                @else
                                    -
                                @endif
                            </td>
                            <td>
                                @if($subscriber->details)
                                    {{ implode(', ', array_filter($subscriber->details->investment_type, function($type) { return strtolower($type) !== 'all'; })) }}
                                @else
                                    -
                                @endif
                            </td>
                            <td>
                                @if($subscriber->details)
                                    ${{ $subscriber->details->investment_criteria }}
                                @else
                                    -
                                @endif
                            </td>
                            <td>
                                @if($subscriber->details)
                                    {{ ucfirst($subscriber->details->contact_preference) }}
                                @else
                                    -
                                @endif
                            </td>
                            <td>
                                @if($subscriber->details)
                                    {{ $subscriber->details->mobile_number ?? '-' }}
                                @else
                                    -
                                @endif
                            </td>
                            <td>{{ $subscriber->created_at->format('Y-m-d H:i:s') }}</td>
                            <td>
                                <div class="dropdown">
                                    <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="ti ti-dots-vertical"></i></button>
                                    <div class="dropdown-menu">
                                        <form id="form-{{ $subscriber->id }}" action="{{ route('admin.subscribers.destroy', $subscriber->id) }}" method="POST">
                                            @method('DELETE')
                                            @csrf
                                            <a class="dropdown-item delete-btn" onclick="confirmDelete({{ $subscriber->id }})" href="javascript:void(0)">
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
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('.table').DataTable();

        // Select all checkbox
        $('#select-all').on('change', function() {
            $('.subscriber-checkbox').prop('checked', this.checked);
            updateBulkDeleteButton();
        });

        // Individual checkboxes
        $(document).on('change', '.subscriber-checkbox', function() {
            updateBulkDeleteButton();
            $('#select-all').prop('checked', $('.subscriber-checkbox:checked').length === $('.subscriber-checkbox').length);
        });

        function updateBulkDeleteButton() {
            var checked = $('.subscriber-checkbox:checked');
            $('#bulk-delete-btn').prop('disabled', checked.length === 0);
        }

        // Bulk delete
        $('#bulk-delete-btn').on('click', function() {
            var checked = $('.subscriber-checkbox:checked');
            if (checked.length === 0) return;
            if (!confirm('Are you sure you want to delete ' + checked.length + ' selected subscriber(s)?')) return;
            $('#bulk-delete-form').submit();
        });
    });

    function confirmDelete(id) {
        if (confirm('Are you sure you want to delete this subscriber?')) {
            document.getElementById('form-' + id).submit();
        }
    }
</script>
@endpush