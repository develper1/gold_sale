@extends('admin.layouts.app')

@section('page-title', 'Sub Categories')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="card">
        <div class="row">
            <div class="col-md-6">
                <h5 class="card-header">Sub Categories</h5>
            </div>
            <div class="col-md-6">
                <div style="text-align: end;">
                    <a href="{{ route('admin.sub-categories.create') }}" class="btn btn-primary mt-3">Add Sub Category</a>
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
            <table class="table">
                <thead>
                    <tr>
                        <th>
                            @php
                                $nextDir = ($sortBy === 'sort_order' && $sortDir === 'asc') ? 'desc' : 'asc';
                                $icon = $sortBy === 'sort_order' ? ($sortDir === 'asc' ? '↑' : '↓') : '↕';
                            @endphp
                            <a href="{{ route('admin.sub-categories.index', ['sort_by' => 'sort_order', 'sort_dir' => $nextDir]) }}" class="text-dark text-decoration-none">
                                Sort Order {!! $icon !!}
                            </a>
                        </th>
                        <th>
                            @php
                                $nextDir = ($sortBy === 'name' && $sortDir === 'asc') ? 'desc' : 'asc';
                                $icon = $sortBy === 'name' ? ($sortDir === 'asc' ? '↑' : '↓') : '↕';
                            @endphp
                            <a href="{{ route('admin.sub-categories.index', ['sort_by' => 'name', 'sort_dir' => $nextDir]) }}" class="text-dark text-decoration-none">
                                Name {!! $icon !!}
                            </a>
                        </th>
                        <th>Slug</th>
                        <th>Description</th>
                        <th>
                            @php
                                $nextDir = ($sortBy === 'category' && $sortDir === 'asc') ? 'desc' : 'asc';
                                $icon = $sortBy === 'category' ? ($sortDir === 'asc' ? '↑' : '↓') : '↕';
                            @endphp
                            <a href="{{ route('admin.sub-categories.index', ['sort_by' => 'category', 'sort_dir' => $nextDir]) }}" class="text-dark text-decoration-none">
                                Category {!! $icon !!}
                            </a>
                        </th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($subCategories as $subCategory)
                        <tr>
                            <td>{{ $subCategory->sort_order ?? 0 }}</td>
                            <td>{{ $subCategory->name }}</td>
                            <td>{{ $subCategory->slug }}</td>
                            <td>{{ $subCategory->description }}</td>
                            <td>{{ $subCategory->category->name }}</td>
                            <td>
                                <a href="{{ route('admin.sub-categories.edit', $subCategory->id) }}" class="btn btn-sm btn-primary mb-1">Edit</a>
                                <form action="{{ route('admin.sub-categories.destroy', $subCategory->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
