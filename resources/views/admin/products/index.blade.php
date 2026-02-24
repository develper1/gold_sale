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
        
        <!-- Filter Section -->
        <div class="card-body" style="padding: 1rem 1.5rem 0.5rem;">
            <form method="GET" action="{{ route('admin.products.index') }}" class="d-flex align-items-center gap-2 mb-2">
                {{-- Preserve current sort state when filter changes --}}
                <input type="hidden" name="sort_by"  value="{{ $sortBy }}">
                <input type="hidden" name="sort_dir" value="{{ $sortDir }}">

                <label for="sub_category_id" class="form-label mb-0" style="margin-right: 0.5rem; white-space: nowrap;">Filter by Subcategory:</label>
                <select name="sub_category_id" id="sub_category_id" class="form-select" style="width: auto; max-width: 300px;" onchange="this.form.submit()">
                    <option value="">All Subcategories</option>
                    @foreach($subCategories as $subCategory)
                        <option value="{{ $subCategory->id }}" {{ request('sub_category_id') == $subCategory->id ? 'selected' : '' }}>
                            {{ $subCategory->category->name ?? 'N/A' }} - {{ $subCategory->name }}
                        </option>
                    @endforeach
                </select>
                @if(request('sub_category_id'))
                    <a href="{{ route('admin.products.index') }}" class="btn btn-secondary btn-sm">Clear</a>
                @endif
            </form>
        </div>
        
        @php
            /* Helper: build a sort URL toggling direction for the given column */
            $sortUrl = function(string $col) use ($sortBy, $sortDir) {
                $dir = ($sortBy === $col && $sortDir === 'asc') ? 'desc' : 'asc';
                return request()->fullUrlWithQuery(['sort_by' => $col, 'sort_dir' => $dir]);
            };
            /* Helper: render a sort indicator arrow */
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
                        <th>ID</th>
                        <th>
                            <a href="{{ $sortUrl('name') }}" class="text-body text-decoration-none">
                                Name {!! $sortIcon('name') !!}
                            </a>
                        </th>
                        <th>
                            <a href="{{ $sortUrl('category') }}" class="text-body text-decoration-none">
                                Category {!! $sortIcon('category') !!}
                            </a>
                        </th>
                        <th>
                            <a href="{{ $sortUrl('subcategory') }}" class="text-body text-decoration-none">
                                Subcategory {!! $sortIcon('subcategory') !!}
                            </a>
                        </th>
                        <th>
                            <a href="{{ $sortUrl('sort_id') }}" class="text-body text-decoration-none">
                                Sort ID {!! $sortIcon('sort_id') !!}
                            </a>
                        </th>
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
                          <td>{{ $data->sortID }}</td>
                          <td>
                            @if($data->images->count() > 0)
                                <img src="{{ asset('storage/app/public/' . $data->images->first()->image_path) }}" alt="{{ $data->name }}" width="100">
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