@extends('admin.layouts.app')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="card">
        <div class="card-header">
            <h5>Add New Slider</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.home-sliders.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label for="image" class="form-label">Image <span class="text-danger">*</span></label>
                    <input type="file" class="form-control" name="image" id="image" required>
                </div>
                <div class="mb-3">
                    <label for="title" class="form-label">Title</label>
                    <input type="text" class="form-control" name="title" id="title">
                </div>
                <div class="mb-3">
                    <label for="subtitle" class="form-label">Subtitle</label>
                    <input type="text" class="form-control" name="subtitle" id="subtitle">
                </div>
                <div class="mb-3">
                    <label for="button_name" class="form-label">Button Name</label>
                    <input type="text" class="form-control" name="button_name" id="button_name">
                </div>
                <div class="mb-3">
                    <label for="button_link" class="form-label">Button Link</label>
                    <input type="text" class="form-control" name="button_link" id="button_link">
                </div>
                <button type="submit" class="btn btn-primary">Add Slider</button>
                <a href="{{ route('admin.home-sliders.index') }}" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
</div>
@endsection 