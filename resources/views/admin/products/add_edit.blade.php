@extends('admin.layouts.app')

@php
    $addEdit = isset($product) ? 'Edit' : 'Add';
    $addUpdate = isset($product) ? 'Update' : 'Add';
@endphp
@section('page-title', $addEdit . ' product')

@push('styles')

<link href="{{ asset('assets/plugins/filepond/css/filepond.css') }}" rel="stylesheet">
<link href="{{ asset('assets/plugins/filepond/css/filepond-plugin-image-preview.css') }}" rel="stylesheet">

<style>
    .ck-editor__editable {
    min-height: 300px; /* Adjust this value as needed */
}
    .filepond--item {
        width: calc(30% - 0.5em);
        margin-right: 0.5em;
        margin-bottom: 0.5em;
        /* height: 100px; Set a fixed height */
    }
    .filepond--item:nth-child(5n) {
        margin-right: 0;
    }
    .filepond--item img {
        width: 100%;
        height: 100%;
        object-fit: cover; /* Ensure the image covers the container */
    }
    .filepond--credits {
        display: none; /* Hide FilePond credits if you want */
    }
</style>
@endpush
@section('content')

<div class="container-xxl flex-grow-1 container-p-y">
    <div class="card">
        <div class="row">
            <div class="col-md-6">
                <h5 class="card-header">{{ $addEdit }} Product</h5>
            </div>
            <div class="col-md-6">
                <div style="text-align: end;">
                    <a href="{{ route('admin.products.index') }}" target="_blank" class="btn btn-primary mt-3" style="margin-right: 5px;">All Products</a>
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
            @if ($product)
                    <form action="{{ route('admin.products.update', $product->id) }}" method="POST"
                        enctype="multipart/form-data">

                        @csrf
                        @method('PUT')
                    @else
                        <form action="{{ route('admin.products.store') }}" method="POST"
                            enctype="multipart/form-data">

                            @csrf
                @endif


                <div class="row push">

                    <div class="col-lg-12 ">

                        <div class="row mb-4">
                            <!-- Name -->
                            <div class="col-lg-6 col-md-6 col-sm-12">
                                <label class="form-label" for="label">Name<span class="text-danger">*</span></label>
                                <input name="name" class="form-control" required value="{{ $product->name ?? '' }}">
                            </div>
                        
                            <!-- Slug -->
                            <div class="col-lg-6 col-md-6 col-sm-12">
                                <label class="form-label" for="label">Slug<span class="text-danger">*</span></label>
                                <input name="slug" class="form-control" required value="{{ $product->slug ?? '' }}">
                            </div>
                        
                            <!-- Product Type -->
                            <div class="col-lg-6 col-md-6 col-sm-12">
                                <label class="form-label" for="label">Product Type<span class="text-danger">*</span></label>
                                @php 
                                    $product_type = $product->product_type ?? 'gold';
                                @endphp
                                <select name="product_type" class="form-control" required>
                                    <option value="">--select type--</option>
                                    <option value="gold" {{ $product_type == 'gold' ? 'selected' : '' }}>Gold</option>
                                    <option value="silver" {{ $product_type == 'silver' ? 'selected' : '' }}>Silver</option>
                                </select>
                            </div>
                        
                            <!-- Pricing Type (triggers fixed price visibility) -->
                            <div class="col-lg-6 col-md-6 col-sm-12">
                                <label class="form-label" for="label">Pricing Type<span class="text-danger">*</span></label>
                                @php 
                                    $pricing_type = $product->pricing_type ?? 'spot';
                                @endphp
                                <select name="pricing_type" id="pricing_type" class="form-control" required>
                                    <option value="">--select price type--</option>
                                    <option value="spot" {{ $pricing_type == 'spot' ? 'selected' : '' }}>Spot</option>
                                    <option value="fixed" {{ $pricing_type == 'fixed' ? 'selected' : '' }}>Fixed</option>
                                </select>
                            </div>
                        
                            <!-- Fixed Price (conditionally shown) -->
                            <div class="col-lg-6 col-md-6 col-sm-12 fixed-price-field" style="display: {{ $pricing_type == 'fixed' ? 'block' : 'none' }};">
                                <label class="form-label" for="label">Fixed Price</label>
                                <input type="number" step="0.01" value="{{ $product->fixed_price ?? '' }}" class="form-control" id="fixed_price" name="fixed_price">
                            </div>
                        
                            <!-- Inventory Type (triggers quantity fields) -->
                            <div class="col-lg-6 col-md-6 col-sm-12">
                                <label class="form-label" for="label">Inventory Type<span class="text-danger">*</span></label>
                                @php 
                                    $inventory_type = $product->inventory_type ?? 'unlimited';
                                @endphp
                                <select name="inventory_type" id="inventory_type" class="form-control" required>
                                    <option value="">--select inventory type--</option>
                                    <option value="limited" {{ $inventory_type == 'limited' ? 'selected' : '' }}>Limited</option>
                                    <option value="unlimited" {{ $inventory_type == 'unlimited' ? 'selected' : '' }}>Unlimited</option>
                                </select>
                            </div>
                        
                            <!-- Quantity Available (conditionally shown) -->
                            <div class="col-lg-6 col-md-6 col-sm-12 inventory-fields" style="display: {{ $inventory_type == 'limited' ? 'block' : 'none' }};">
                                <label class="form-label" for="label">Quantity Available</label>
                                <input type="number" step="1" value="{{ $product->quantity_available ?? '' }}" class="form-control" id="quantity_available" name="quantity_available">
                            </div>
                        
                            <!-- Low Inventory Threshold (conditionally shown) -->
                            <div class="col-lg-6 col-md-6 col-sm-12 inventory-fields" style="display: {{ $inventory_type == 'limited' ? 'block' : 'none' }};">
                                <label class="form-label" for="label">Low Inventory Threshold</label>
                                <input type="number" step="1" value="{{ $product->low_inventory_threshold ?? '' }}" class="form-control" id="low_inventory_threshold" name="low_inventory_threshold">
                            </div>
                        
                            <!-- Blanket Markup Percentage -->
                            <div class="col-lg-6 col-md-6 col-sm-12">
                                <label class="form-label" for="label">Blanket Markup Percentage<span class="text-danger">*</span></label>
                                <input type="number" step="0.01" value="{{ $product->blanket_markup_percentage ?? '' }}" class="form-control" id="blanket_markup_percentage" name="blanket_markup_percentage">
                            </div>
                        
                            <!-- Override Markup Toggle -->
                            <div class="col-lg-6 col-md-6 col-sm-12">
                                <div class="form-check form-switch mt-4">
                                    <input class="form-check-input" type="checkbox" id="use_override_markup" name="use_override_markup" {{ isset($product->use_override_markup) && $product->use_override_markup ? 'checked' : '' }}>
                                    <label class="form-check-label" for="use_override_markup">Override Markup</label>
                                </div>
                            </div>
                        
                            <!-- Override Markup Percentage (conditionally shown) -->
                            <div class="col-lg-6 col-md-6 col-sm-12 override-markup-field" style="display: {{ (isset($product->use_override_markup) && $product->use_override_markup) ? 'block' : 'none' }};">
                                <label class="form-label" for="label">Override Markup Percentage</label>
                                <input type="number" step="0.01" value="{{ $product->override_markup_percentage ?? '' }}" class="form-control" id="override_markup_percentage" name="override_markup_percentage">
                            </div>
                        
                            <!-- Description -->
                            <div class="col-md-12 mt-2">
                                <label class="form-label" for="label">Description</label>
                                <textarea name="description" id="editor" rows="10" class="form-control">{{ $product->description ?? '' }}</textarea>
                            </div>
                        
                            <!-- Image Upload -->
                            <div class="col-md-12 mt-2">
                                <label class="form-label" for="label">Image <span class="text-danger">*</span></label>
                                <input type="file" class="filepond" name="images[]" multiple>
                                <div id="dropzone" style="display:none; border: 2px dashed #ccc; padding: 20px; text-align: center;">
                                    Drop your files here
                                </div> 
                            </div>
                        </div>



                    </div>


                </div>

                <div class="d-flex justify-content-end mb-4">

                    <button type="submit" id="submitBtn" class="btn btn-primary  border">{{ $addUpdate }}</button>

                </div>




                </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<!-- Include FilePond library and plugins -->
<script src="{{ asset('assets/plugins/filepond/js/filepond.js') }}"></script>
<script src="{{ asset('assets/plugins/filepond/js/filepond-plugin-file-encode.js') }}"></script>
<script src="{{ asset('assets/plugins/filepond/js/filepond-plugin-file-validate-size.js') }}"></script>
<script src="{{ asset('assets/plugins/filepond/js/filepond-plugin-image-exif-orientation.js') }}"></script>
<script src="{{ asset('assets/plugins/filepond/js/filepond-plugin-image-preview.js') }}"></script>

<script src="https://cdn.ckeditor.com/ckeditor5/26.0.0/classic/ckeditor.js"></script>

<script>
    ClassicEditor
    .create(document.querySelector('#editor'), {
        ckfinder: {
            uploadUrl: 'ckeditor-upload.php', // Your upload route
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            },
        },
        // Set minimum height
        minHeight: '300px',
        // Do not override the toolbar to retain all default options
    })
    .catch(error => {
        console.error(error);
    });

     // Register FilePond plugins
     FilePond.registerPlugin(
        FilePondPluginFileEncode,
        FilePondPluginFileValidateSize,
        FilePondPluginImageExifOrientation,
        FilePondPluginImagePreview
    );

        // Turn all file input elements into ponds
    const pond = FilePond.create(document.querySelector('input.filepond'), {
        allowMultiple: true,
        allowReorder: true, // Enable reordering
    });
    @if ($product && $product->images && count($product->images) > 0)
        @foreach ($product->images->reverse() as $image)
            pond.addFile("{{ asset('storage/' . $image->image_path) }}").then(file => {
                file.setMetadata('existing', true);
            });
        @endforeach
    @else
        @if($product && $product->image_path)
            pond.addFile("{{ asset('storage/' . $product->image_path) }}").then(file => {
                file.setMetadata('existing', true);
            });    
        @endif
    @endif

    $(document).ready(function() {
    // Pricing Type - Fixed Price toggle
    $('#pricing_type').change(function() {
        if ($(this).val() === 'fixed') {
            $('.fixed-price-field').show();
            $('#fixed_price').attr('required', true);
        } else {
            $('.fixed-price-field').hide();
            $('#fixed_price').removeAttr('required');
        }
    });

    // Inventory Type - Quantity fields toggle
    $('#inventory_type').change(function() {
        if ($(this).val() === 'limited') {
            $('.inventory-fields').show();
            $('#quantity_available, #low_inventory_threshold').attr('required', true);
        } else {
            $('.inventory-fields').hide();
            $('#quantity_available, #low_inventory_threshold').removeAttr('required');
        }
    });

    // Override Markup toggle
    $('#use_override_markup').change(function() {
        if ($(this).is(':checked')) {
            $('.override-markup-field').show();
            $('#override_markup_percentage').attr('required', true);
        } else {
            $('.override-markup-field').hide();
            $('#override_markup_percentage').removeAttr('required');
        }
    });

    // Trigger change events on page load to set initial state
    $('#pricing_type, #inventory_type').trigger('change');
    if ($('#use_override_markup').is(':checked')) {
        $('.override-markup-field').show();
    }
});
</script>
    
@endpush