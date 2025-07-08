@extends('admin.layouts.app')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Home Page Sliders</h5>
            <a href="{{ route('admin.home-sliders.create') }}" class="btn btn-primary">Add New Slider</a>
        </div>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Image</th>
                        <th>Title</th>
                        <th>Subtitle</th>
                        {{-- <th>Order</th> --}}
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($sliders as $slider)
                    <tr>
                        <td><img src="{{ asset('storage/app/public/' . $slider->image_path) }}" width="120" /></td>
                        <td>{{ $slider->title }}</td>
                        <td>{{ $slider->subtitle }}</td>
                        {{-- <td>{{ $slider->order }}</td> --}}
                        <td>
                            <a href="{{ route('admin.home-sliders.edit', $slider->id) }}" class="btn btn-sm btn-info">Edit</a>
                            <form action="{{ route('admin.home-sliders.destroy', $slider->id) }}" method="POST" style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this slider?')">Delete</button>
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