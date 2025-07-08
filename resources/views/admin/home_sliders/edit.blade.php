@extends('admin.layouts.app')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="card">
        <div class="card-header">
            <h5>Edit Slider</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.home-sliders.update', $slider->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label for="image" class="form-label">Image</label><br>
                    <img src="{{ asset('public/storage/' . $slider->image_path) }}" width="180" class="mb-2" />
                    <input type="file" class="form-control" name="image" id="image">
                </div>
                <div class="mb-3">
                    <label for="title" class="form-label">Title</label>
                    <input type="text" class="form-control" name="title" id="title" value="{{ $slider->title }}">
                </div>
                <div class="mb-3">
                    <label for="subtitle" class="form-label">Subtitle</label>
                    <input type="text" class="form-control" name="subtitle" id="subtitle" value="{{ $slider->subtitle }}">
                </div>
                <button type="submit" class="btn btn-primary">Update Slider</button>
                <a href="{{ route('admin.home-sliders.index') }}" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
</div>
@endsection 