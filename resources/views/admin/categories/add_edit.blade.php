@extends('admin.layouts.app')

@php
    $addEdit = isset($category) ? 'Edit' : 'Add';
    $addUpdate = isset($category) ? 'Update' : 'Add';
@endphp
@section('page-title', $addEdit . ' Category')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="card">
        <div class="row">
            <div class="col-md-6">
                <h5 class="card-header">{{ $addEdit }} Category</h5>
            </div>
            <div class="col-md-6">
                <div style="text-align: end;">
                    <a href="{{ route('admin.categories.index') }}" class="btn btn-primary mt-3">All Categories</a>
                </div>
            </div>
        </div>
        <hr class="my-0">
        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            @if ($category)
                <form action="{{ route('admin.categories.update', $category->id) }}" method="POST">
                    @csrf
                    @method('PUT')
            @else
                <form action="{{ route('admin.categories.store') }}" method="POST">
                    @csrf
            @endif
                <div class="row push">
                    <div class="col-lg-12">
                        <div class="row mb-4">
                            <!-- Name -->
                            <div class="col-lg-6 col-md-6 col-sm-12">
                                <label class="form-label" for="name">Name<span class="text-danger">*</span></label>
                                <input name="name" class="form-control" required value="{{ $category->name ?? '' }}">
                            </div>
                            <!-- Slug -->
                            <div class="col-lg-6 col-md-6 col-sm-12">
                                <label class="form-label" for="slug">Slug<span class="text-danger">*</span></label>
                                <input name="slug" class="form-control" required value="{{ $category->slug ?? '' }}">
                            </div>
                            <!-- Sort Order -->
                            <div class="col-lg-6 col-md-6 col-sm-12 mt-2">
                                <label class="form-label" for="sort_order">Sort Order</label>
                                <input type="number" name="sort_order" class="form-control" min="0" value="{{ $category->sort_order ?? 0 }}">
                                <small class="text-muted">Lower numbers appear first. Leave 0 for default ordering.</small>
                            </div>
                            <!-- Description -->
                            <div class="col-md-12 mt-2">
                                <label class="form-label" for="description">Description</label>
                                <textarea name="description" rows="5" class="form-control">{{ $category->description ?? '' }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="d-flex justify-content-end mb-4">
                    <button type="submit" class="btn btn-primary border">{{ $addUpdate }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection 