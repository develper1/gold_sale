@extends('admin.layouts.app')

@php
    $addEdit = isset($subCategory) ? 'Edit' : 'Add';
    $addUpdate = isset($subCategory) ? 'Update' : 'Add';
@endphp
@section('page-title', $addEdit . ' Sub Category')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="card">
        <div class="row">
            <div class="col-md-6">
                <h5 class="card-header">{{ $addEdit }} Sub Category</h5>
            </div>
            <div class="col-md-6">
                <div style="text-align: end;">
                    <a href="{{ route('admin.sub-categories.index') }}" class="btn btn-primary mt-3">All Sub Categories</a>
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
            @if ($subCategory)
                <form action="{{ route('admin.sub-categories.update', $subCategory->id) }}" method="POST">
                    @csrf
                    @method('PUT')
            @else
                <form action="{{ route('admin.sub-categories.store') }}" method="POST">
                    @csrf
            @endif
                <div class="row push">
                    <div class="col-lg-12">
                        <div class="row mb-4">
                            <!-- Name -->
                            <div class="col-lg-6 col-md-6 col-sm-12">
                                <label class="form-label" for="name">Name<span class="text-danger">*</span></label>
                                <input name="name" class="form-control" required value="{{ $subCategory->name ?? '' }}">
                            </div>
                            <!-- Slug -->
                            <div class="col-lg-6 col-md-6 col-sm-12">
                                <label class="form-label" for="slug">Slug<span class="text-danger">*</span></label>
                                <input name="slug" class="form-control" required value="{{ $subCategory->slug ?? '' }}">
                            </div>
                            <!-- Category -->
                            <div class="col-lg-6 col-md-6 col-sm-12">
                                <label class="form-label" for="category_id">Category<span class="text-danger">*</span></label>
                                <select name="category_id" class="form-control" required>
                                    <option value="">-- Select Category --</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ (isset($subCategory) && $subCategory->category_id == $category->id) ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <!-- Description -->
                            <div class="col-md-12 mt-2">
                                <label class="form-label" for="description">Description</label>
                                <textarea name="description" rows="5" class="form-control">{{ $subCategory->description ?? '' }}</textarea>
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