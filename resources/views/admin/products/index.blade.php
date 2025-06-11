@extends('admin.layouts.app')

@section('content')


<div class="container-xxl flex-grow-1 container-p-y">
        
        


      <!-- Products List Table -->
      <div class="card">
        <div class="row">
            <div class="col-md-6">
                <h5 class="card-header">Products</h5>
            </div>
            <div class="col-md-6">
                <div style="text-align: end;">
                    <a href="{{ route('admin.products.create') }}" target="_blank" class="btn btn-primary mt-3" style="margin-right: 5px;">Create Product</a>
                </div>
            </div>
        </div>
        
        <div class="table-responsive text-nowrap">
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Category</th>
                        <th>Subcategory</th>
                        <th>Image</th>
                        <th>Active</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                  
                 
                  @foreach($products as  $data)
                      <tr >
                          <td>{{ $data->id }}</td>
                          <td>{{ $data->name }}</td>
                          <td>{{ $data->subCategory->category->name ?? 'N/A' }}</td>
                          <td>{{ $data->subCategory->name ?? 'N/A' }}</td>
                          <td>
                            @if($data->images->count() > 0)
                                <img src="{{ asset('public/storage/' . $data->images->first()->image_path) }}" alt="{{ $data->name }}" width="100">
                            @else
                                No Image
                            @endif
                          </td>
                          <td>
                            @if($data->is_active)
                                <span class="badge bg-label-success">Yes</span>
                            @else
                                <span class='badge bg-label-danger'>No</span>
                            @endif
                            </td>
                            <td>
                                <div class="dropdown">
                                    <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="ti ti-dots-vertical"></i></button>
                                    <div class="dropdown-menu">
                                        <form id="form-{{ $data->id }}" action="{{ route('admin.products.destroy', $data->id) }}" method="POST">
                                            @method('DELETE')
                                            @csrf
                                            <a class="dropdown-item delete-btn" onclick="confirmDelete({{ $data->id }}, '{{ route('admin.products.destroy', $data->id) }}')" href="javascript:void(0)">
                                                <i class="fa-solid fa-trash me-1"></i> Delete
                                            </a>
                                        </form>
                                    <a class="dropdown-item "   href="{{ route('admin.products.edit', $data->id) }}"><i class="fa-solid fa-edit me-1"></i> Edit</a>
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
function confirmDelete(id, url) {
    if (confirm('Are you sure you want to delete this item?')) {
        document.getElementById(`form-${id}`).submit();
    }
}    
</script>
@endpush