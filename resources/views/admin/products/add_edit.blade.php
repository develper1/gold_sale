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
                            <!-- Category -->
                            <div class="col-lg-6 col-md-6 col-sm-12">
                                <label class="form-label" for="category_id">Category<span class="text-danger">*</span></label>
                                <select name="category_id" id="category_id" class="form-control" required>
                                    <option value="">-- Select Category --</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ (isset($product) && $product->category_id == $category->id) ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Sub Category -->
                            <div class="col-lg-6 col-md-6 col-sm-12">
                                <label class="form-label" for="sub_category_id">Sub Category<span class="text-danger">*</span></label>
                                <select name="sub_category_id" id="sub_category_id" class="form-control" required>
                                    <option value="">-- Select Sub Category --</option>
                                    @if(isset($product) && $product->sub_category_id)
                                        @foreach($subCategories as $subCategory)
                                            <option value="{{ $subCategory->id }}" {{ $product->sub_category_id == $subCategory->id ? 'selected' : '' }}>
                                                {{ $subCategory->name }}
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
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
                                <select name="product_type"  class="selectpicker w-100" data-style="btn-default" required>
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
                                <select name="pricing_type" id="pricing_type"  class="selectpicker w-100" data-style="btn-default" required>
                                    <option value="">--select price type--</option>
                                    <option value="spot" {{ $pricing_type == 'spot' ? 'selected' : '' }}>Spot</option>
                                    <option value="fixed" {{ $pricing_type == 'fixed' ? 'selected' : '' }}>Fixed</option>
                                </select>
                            </div>
                        
                            <!-- Fixed Price (conditionally shown) -->
                            <div class="col-lg-6 col-md-6 col-sm-12 fixed-price-field" style="display: {{ $pricing_type == 'fixed' ? 'block' : 'none' }};">
                                <label class="form-label" for="label">Fixed Price <span class="text-danger">*</span></label>
                                <input type="number" step="0.01" value="{{ $product->fixed_price ?? '' }}" class="form-control" id="fixed_price" name="fixed_price">
                            </div>

                            <!-- Spot Percentage (conditionally shown) -->
                            <div class="col-lg-6 col-md-6 col-sm-12 spot-price-field" style="display: {{ $pricing_type == 'spot' ? 'block' : 'none' }};">
                                <label class="form-label" for="label">Spot Percentage <span class="text-danger">*</span></label>
                                <input type="number" step="0.01" value="{{ $product->spot_percentage ?? '' }}" class="form-control" id="spot_percentage" name="spot_percentage">
                            </div>
                            
                        
                            <!-- Inventory Type (triggers quantity fields) -->
                            <div class="col-lg-6 col-md-6 col-sm-12">
                                <label class="form-label" for="label">Inventory Type<span class="text-danger">*</span></label>
                                @php 
                                    $inventory_type = $product->inventory_type ?? 'unlimited';
                                @endphp
                                <select name="inventory_type" id="inventory_type" class="selectpicker w-100" data-style="btn-default" required>
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
                            <div class="form-group mt-2">
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" id="is_featured" name="is_featured" value="1" {{ old('is_featured', $product->is_featured ?? false) ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="is_featured">Featured Item</label>
                                </div>
                                
                            </div>
                            <div class="form-group mt-2">
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" class="custom-control-input" id="is_best_seller" name="is_best_seller" value="1" {{ old('is_best_seller', $product->is_best_seller ?? false) ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="is_best_seller">Best Seller</label>
                                    </div>
                                
                            </div>
                        
                            
                            <div class="form-group mt-2 tier-pricing-section" style="display: none;">
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" id="use_tier_pricing" name="use_tier_pricing" value="1" {{ old('use_tier_pricing', $product->use_tier_pricing ?? false) ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="use_tier_pricing">Use Tier Pricing</label>
                                </div>
                            </div>
    
                            <div id="tier_pricing_section" style="display: none;">
                                <h4>Tier Pricing</h4>
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Quantity Range</th>
                                                <th>Price</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($priceTierRanges as $tierRange)
                                            <tr>
                                                <td>{{ $tierRange->tier_start }} - {{ $tierRange->tier_end }}</td>
                                                <td>
                                                    <input type="number" 
                                                           name="tier_prices[{{ $tierRange->id }}][price]" 
                                                           class="form-control" 
                                                           step="0.01" 
                                                           min="0"
                                                           value="{{ old('tier_prices.'.$tierRange->id.'.price', isset($product) ? $product->tierPrices->where('price_tier_range_id', $tierRange->id)->first()->price ?? $tierRange->tier_price : $tierRange->tier_price) }}"
                                                           required>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- Spot Tier Pricing (conditionally shown) -->
                            <div class="form-group mt-2 spot-tier-pricing-section" style="display: none;">
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" id="use_spot_tier_pricing" name="use_spot_tier_pricing" value="1" {{ old('use_spot_tier_pricing', $product->use_spot_tier_pricing ?? false) ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="use_spot_tier_pricing">Use Spot Tier Pricing
                                        <strong>
                                            <span id="spot-price-display">
                                                @if(isset($spotPrice) && $spotPrice && $product->pricing_type === 'spot')
                                                    <span style="color: #888;">(Spot Price: ${{ number_format($spotPrice, 2) }})</span>
                                                @endif
                                            </span>
                                        </strong>
                                    </label>
                                </div>
                            </div>
                            <div id="spot_tier_pricing_section" style="display: none;">
                                <h4>Spot Tier Pricing</h4>
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Quantity Range</th>
                                                <th>Type</th>
                                                <th>Value</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($spotTierPrices as $spotTier)
                                            @php
                                                $override = $productSpotTierPrices->where('spot_tier_price_id', $spotTier->id)->first();
                                                $type = old('spot_tier_prices.'.$spotTier->id.'.type', $override->type ?? $spotTier->type);
                                                $value = old('spot_tier_prices.'.$spotTier->id.'.value', $override->value ?? $spotTier->value);
                                            @endphp
                                            <tr>
                                                <td>{{ $spotTier->tier_start }} - {{ $spotTier->tier_end }}</td>
                                                <td>
                                                    <select name="spot_tier_prices[{{ $spotTier->id }}][type]" class="form-control">
                                                        <option value="percentage" {{ $type == 'percentage' ? 'selected' : '' }}>Percentage (%)</option>
                                                        <option value="fixed" {{ $type == 'fixed' ? 'selected' : '' }}>Fixed Amount</option>
                                                    </select>
                                                </td>
                                                <td>
                                                    <input type="number" step="0.01" name="spot_tier_prices[{{ $spotTier->id }}][value]" class="form-control" value="{{ $value }}">
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            
                        
                            <!-- Description -->
                            <div class="col-md-12 mt-2">
                                <label class="form-label" for="description">Description</label>
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

                <div class="d-flex justify-content-end mb-4 mt-2">

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
            pond.addFile("{{ asset('storage/app/public/' . $image->image_path) }}").then(file => {
                file.setMetadata('existing', true);
            });
        @endforeach
    @else
        @if($product && $product->image_path)
            pond.addFile("{{ asset('storage/app/public/' . $product->image_path) }}").then(file => {
                file.setMetadata('existing', true);
            });    
        @endif
    @endif

    $(document).ready(function() {
    // Pricing Type - Fixed Price toggle
    $('#pricing_type').change(function() {
        if ($(this).val() === 'fixed') {
            $('.fixed-price-field').show();
            $('.tier-pricing-section').show();
            $('#fixed_price').attr('required', true);
            $('.spot-price-field').hide();
            $('#spot_percentage').removeAttr('required');
        } else {
            $('.fixed-price-field').hide();
            $('.tier-pricing-section').hide();
            $('#tier_pricing_section').hide();
            $('#use_tier_pricing').prop('checked', false);
            $('#fixed_price').removeAttr('required');
            $('.spot-price-field').show();
            $('#spot_percentage').attr('required', true);
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



    // Trigger change events on page load to set initial state
    $('#pricing_type, #inventory_type').trigger('change');


    $('#category_id').change(function() {
        var categoryId = $(this).val();
        if (categoryId) {
            $.ajax({
                url: '{{ route('admin.sub-categories.getSubCategoriesByCategory', ['categoryId' => ':categoryId']) }}'.replace(':categoryId', categoryId),
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    $('#sub_category_id').empty();
                    $('#sub_category_id').append('<option value="">-- Select Sub Category --</option>');
                    $.each(data, function(key, value) {
                        $('#sub_category_id').append('<option value="' + value.id + '">' + value.name + '</option>');
                    });
                }
            });
        } else {
            $('#sub_category_id').empty();
            $('#sub_category_id').append('<option value="">-- Select Sub Category --</option>');
        }
    });

    // Tier Pricing Toggle
    function toggleTierPricing() {
        if ($('#use_tier_pricing').is(':checked')) {
            $('#tier_pricing_section').show();
        } else {
            $('#tier_pricing_section').hide();
        }
    }

    $('#use_tier_pricing').on('change', function() {
        toggleTierPricing();
    });

    // Initial state
    if ($('#pricing_type').val() === 'fixed') {
        $('.tier-pricing-section').show();
        toggleTierPricing();
    }

    // Spot Tier Pricing Toggle
    function toggleSpotTierPricing() {
        if ($('#use_spot_tier_pricing').is(':checked')) {
            $('#spot_tier_pricing_section').show();
        } else {
            $('#spot_tier_pricing_section').hide();
        }
    }
    $('#use_spot_tier_pricing').on('change', function() {
        toggleSpotTierPricing();
    });
    // Pricing Type - Spot Tier Pricing toggle
    $('#pricing_type').change(function() {
        if ($(this).val() === 'spot') {
            $('.spot-tier-pricing-section').show();
            toggleSpotTierPricing();
        } else {
            $('.spot-tier-pricing-section').hide();
            $('#spot_tier_pricing_section').hide();
            $('#use_spot_tier_pricing').prop('checked', false);
        }
    });
    // Initial state for spot tier pricing
    if ($('#pricing_type').val() === 'spot') {
        $('.spot-tier-pricing-section').show();
        toggleSpotTierPricing();
    }

    function updateSpotPriceDisplay() {
        var productType = $('select[name="product_type"]').val();
        var pricingType = $('#pricing_type').val();
        if (pricingType === 'spot' && productType) {
            $.ajax({
                url: '{{ route('admin.products.spot_price') }}',
                method: 'GET',
                data: { type: productType },
                success: function(response) {
                    if (response.success && response.spot_price) {
                        $('#spot-price-display').html('<span style="color: #888;">(Spot Price: $' + parseFloat(response.spot_price).toFixed(2) + ')</span>');
                    } else {
                        $('#spot-price-display').html('');
                    }
                },
                error: function() {
                    $('#spot-price-display').html('');
                }
            });
        } else {
            $('#spot-price-display').html('');
        }
    }
    $('select[name="product_type"], #pricing_type').on('change', function() {
        updateSpotPriceDisplay();
    });
    // Initial call
    updateSpotPriceDisplay();


});
</script>
    
@endpush