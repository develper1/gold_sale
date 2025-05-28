@extends('admin.layouts.app')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <!-- Subscribers List Table -->
    <div class="card">
        <div class="row">
            <div class="col-md-6">
                <h5 class="card-header">Subscribers</h5>
            </div>
            
        </div>
        
        <div class="table-responsive text-nowrap">
            <table class="table">
                <thead>
                    <tr>
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
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('.table').DataTable();
    });

    function confirmDelete(id) {
        if (confirm('Are you sure you want to delete this subscriber?')) {
            document.getElementById('form-' + id).submit();
        }
    }
</script>
@endpush