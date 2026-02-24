@extends('admin.layouts.app')

@section('page-title', 'Categories')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="card">
        <div class="row">
            <div class="col-md-6">
                <h5 class="card-header">Categories</h5>
            </div>
            <div class="col-md-6">
                <div style="text-align: end;">
                    <a href="{{ route('admin.categories.create') }}" class="btn btn-primary mt-3">Add Category</a>
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
                            <a href="{{ route('admin.categories.index', ['sort_by' => 'sort_order', 'sort_dir' => $nextDir]) }}" class="text-dark text-decoration-none">
                                Sort Order {!! $icon !!}
                            </a>
                        </th>
                        <th>
                            @php
                                $nextDir = ($sortBy === 'name' && $sortDir === 'asc') ? 'desc' : 'asc';
                                $icon = $sortBy === 'name' ? ($sortDir === 'asc' ? '↑' : '↓') : '↕';
                            @endphp
                            <a href="{{ route('admin.categories.index', ['sort_by' => 'name', 'sort_dir' => $nextDir]) }}" class="text-dark text-decoration-none">
                                Name {!! $icon !!}
                            </a>
                        </th>
                        <th>Slug</th>
                        <th>Description</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($categories as $category)
                        <tr>
                            <td>{{ $category->sort_order ?? 0 }}</td>
                            <td>{{ $category->name }}</td>
                            <td>{{ $category->slug }}</td>
                            <td>{{ $category->description }}</td>
                            <td>
                                <a href="{{ route('admin.categories.edit', $category->id) }}" class="btn btn-sm btn-primary">Edit</a>
                                <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST" style="display:inline;">
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
