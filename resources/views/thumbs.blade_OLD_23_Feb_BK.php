@extends('layouts.app')

@section('content')


<div id="title" class="page-title">
    <div class="section-container">
        <div class="content-title-heading">
            <h1 class="text-title-heading">
                @if(isset($subcategory))
                    {{ $subcategory->name }}
                @elseif(isset($category))
                    {{ $category->name }}
                @else
                    Oasis Mint - Gold, Silver, and Investment Treasures
                @endif
            </h1>
        </div>
        <div class="breadcrumbs">
            <a href="{{ route('home') }}">Home</a><span class="delimiter"></span>
            <a href="{{ route('shop.index') }}">Shop</a>
            @if(isset($category))
                <span class="delimiter"></span>{{ $category->name }}
            @elseif(isset($subcategory))
                <span class="delimiter"></span>{{ $subcategory->category->name }}<span class="delimiter"></span>{{ $subcategory->name }}
            @endif
        </div>
    </div>
</div>

<div id="content" class="site-content" role="main">
    <div class="section-padding">
        <div class="section-container p-l-r">
            <div class="row">
                <div class="col-xl-3 col-lg-3 col-md-12 col-12 sidebar left-sidebar md-b-50 p-t-10">
                    <!-- Block Product Categories -->
                    <div class="block block-product-cats">
                        <div class="block-title"><h2>Categories</h2></div>
                        <div class="block-content">
                            <div class="product-cats-list">
                                <ul>
                                    @foreach($categories as $cat)
                                        <li class="{{ isset($category) && $category->id == $cat->id ? 'current' : '' }}">
                                            <h4><strong><a style="color:#cb8161;" href="{{ route('shop.category', $cat->slug) }}">{{ $cat->name }}</a></strong></h4>
                                            @if($cat->subCategories->count() > 0)
                                                <ul class="children">
                                                    @foreach($cat->subCategories as $subCat)
                                                        <li class="{{ isset($subcategory) && $subcategory->id == $subCat->id ? 'current' : '' }}">
                                                            <a href="{{ route('shop.subcategory', $subCat->slug) }}">
                                                                {{ $subCat->name }}
                                                                <span class="count">{{ $subCat->products_count }}</span>
                                                            </a>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            @endif
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-9 col-lg-9 col-md-12 col-12">
                    <div class="products-topbar clearfix">
                        <div class="products-topbar-left">
                            <div class="products-count">
                                @if(isset($category))
                                    Showing {{ $products->sum(function($group) { return $group->count(); }) }} results
                                @else
                                    Showing {{ $products->count() ?? 0 }} results
                                @endif
                            </div>
                        </div>
                        <div class="products-topbar-right">
                            <div class="products-sort dropdown">
                                @php
                                    $sort = request('sort', 'default');
                                    $sortLabel = [
                                        'default' => 'Default sorting',
                                        'latest' => 'By Latest',
                                        'price_asc' => 'Sort by price: low to high',
                                        'price_desc' => 'Sort by price: high to low',
                                    ][$sort] ?? 'Default sorting';
                                @endphp
                                <span class="sort-toggle dropdown-toggle" data-toggle="dropdown" aria-expanded="true">{{ $sortLabel }}</span>
                                <ul class="sort-list dropdown-menu" x-placement="bottom-start">
                                    <li class="{{ $sort == 'default' ? 'active' : '' }}"><a href="#" data-sort="default">Default sorting</a></li>
                                    <li class="{{ $sort == 'latest' ? 'active' : '' }}"><a href="#" data-sort="latest">By Latest</a></li>
                                    <li class="{{ $sort == 'price_asc' ? 'active' : '' }}"><a href="#" data-sort="price_asc">Sort by price: low to high</a></li>
                                    <li class="{{ $sort == 'price_desc' ? 'active' : '' }}"><a href="#" data-sort="price_desc">Sort by price: high to low</a></li>
                                </ul>
                                <form id="sortForm" method="get" style="display:none;">
                                    <input type="hidden" name="sort" id="sortInput" value="{{ $sort }}">
                                </form>
                            </div>
                            <ul class="layout-toggle nav nav-tabs">
                                <li class="nav-item">
                                    <a class="layout-grid nav-link active" data-toggle="tab" href="#layout-grid" role="tab"><span class="icon-column"><span class="layer first"><span></span><span></span><span></span></span><span class="layer middle"><span></span><span></span><span></span></span><span class="layer last"><span></span><span></span><span></span></span></span></a>
                                </li>
                                <li class="nav-item">
                                    <a class="layout-list nav-link" data-toggle="tab" href="#layout-list" role="tab"><span class="icon-column"><span class="layer first"><span></span><span></span></span><span class="layer middle"><span></span><span></span></span><span class="layer last"><span></span><span></span></span></span></a>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <div class="tab-content">
                        <div class="tab-pane fade show active" id="layout-grid" role="tabpanel">
                            <div class="products-list grid">
                                @if(isset($category))
                                    @foreach($products as $subCategoryId => $subCategoryProducts)
                                        @php
                                            $subCategory = $subCategoryProducts->first()->subCategory;
                                        @endphp
                                        <h3 class="subcategory-title">{{ $subCategory->name }}</h3>
                                        <div class="row">
                                            @foreach($subCategoryProducts as $product)
                                                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-6 col-6">
                                                    <div class="products-entry clearfix product-wapper">
                                                        <div class="products-thumb">
                                                            @if($product->images->count() > 0)
                                                                <div class="product-thumb-hover">
                                                                    <a href="{{ route('shop.product', $product->slug) }}">
                                                                        <img width="600" height="600" 
                                                                             src="{{ asset('storage/app/public/' . $product->images->first()->image_path) }}" 
                                                                             class="post-image" alt="{{ $product->name }}">
                                                                        <img width="600" height="600" 
                                                                             src="{{ asset('storage/app/public/' . $product->images->first()->image_path) }}" 
                                                                             class="hover-image back" alt="{{ $product->name }}">
                                                                    </a>
                                                                </div>
                                                            @endif
                                                            <div class="product-button">
                                                                <div class="btn-add-to-cart" data-title="Add to cart" >
                                                                    <a rel="nofollow" href="#" class="product-btn button" data-product-id="{{ $product->id }}" data-inventory-type="{{ $product->inventory_type }}" data-quantity-available="{{ $product->inventory_type === 'limited' && $product->quantity_available !== null ? $product->quantity_available : '' }}">Add to cart</a>
                                                                </div>
                                                                <span class="product-quickview" data-title="Quick View">
                                                                    <a href="#" class="quickview quickview-button" data-product-id="{{ $product->id }}">
                                                                        Quick View <i class="icon-search"></i>
                                                                    </a>
                                                                </span>
                                                            </div>
                                                        </div>
                                                        <div class="products-content">
                                                            <div class="contents text-center">
                                                                <h3 class="product-title">
                                                                    <a href="{{ route('shop.product', $product->slug) }}">
                                                                        {{ $product->name }}
                                                                    </a>
                                                                </h3>
                                                                <span class="price">
                                                                    @if($product->use_tier_pricing || $product->use_spot_tier_pricing)
                                                                        As low as {{ $product->formatted_lowest_price }}
                                                                    @elseif($product->pricing_type === 'fixed')
                                                                        {{ $product->formatted_price }}
                                                                    @else
                                                                        {{ $product->formatted_price }}
                                                                    @endif
                                                                </span>
                                                            @if($product->inventory_type === 'limited' && $product->quantity_available !== null)
                                                                <p style="font-size: 12px; color: #666; margin-top: 5px;">
                                                                    @if($product->quantity_available > 0)
                                                                       {{--  Available: {{ $product->quantity_available }} --}}
                                                                    @else
                                                                        <span style="color: #dc3545;">Out of stock</span>
                                                                    @endif
                                                                </p>
                                                            @endif
                                                            @if($product->use_tier_pricing)
                                                                
                                                            @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endforeach
                                @else
                                    <div class="row">
                                        @forelse($products ?? [] as $product)
                                            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-6">
                                                <div class="products-entry clearfix product-wapper">
                                                    <div class="products-thumb">
                                                        @if($product->images->count() > 0)
                                                            <div class="product-thumb-hover">
                                                                <a href="{{ route('shop.product', $product->slug) }}">
                                                                    <img width="600" height="600" 
                                                                         src="{{ asset('storage/app/public/' . $product->images->first()->image_path) }}" 
                                                                         class="post-image" alt="{{ $product->name }}">

                                                                    @if(isset($product->images[1]))
                                                                         <img width="600" height="600" 
                                                                              src="{{ asset('storage/app/public/' . $product->images[1]->image_path) }}" 
                                                                              class="hover-image back" alt="{{ $product->name }}">
                                                                    @else 
                                                                        <img width="600" height="600" 
                                                                        src="{{ asset('storage/app/public/' . $product->images->first()->image_path) }}" 
                                                                        class="hover-image back" alt="{{ $product->name }}">
                                                                     @endif
                                                                </a>
                                                            </div>
                                                        @endif
                                                        <div class="product-button">
                                                            <div class="btn-add-to-cart" data-title="Add to cart" >
                                                                <a rel="nofollow" href="#" class="product-btn button" data-product-id="{{ $product->id }}">Add to cart</a>
                                                            </div>
                                                            <span class="product-quickview" data-title="Quick View">
                                                                <a href="#" class="quickview quickview-button" data-product-id="{{ $product->id }}">
                                                                    Quick View <i class="icon-search"></i>
                                                                </a>
                                                            </span>
                                                        </div>
                                                    </div>
                                                    <div class="products-content">
                                                        <div class="contents text-center">
                                                            <h3 class="product-title">
                                                                <a href="{{ route('shop.product', $product->slug) }}">
                                                                    {{ $product->name }}
                                                                </a>
                                                            </h3>
                                                            <span class="price">
                                                                @if($product->use_tier_pricing || $product->use_spot_tier_pricing)
                                                                    As low as {{ $product->formatted_lowest_price }}
                                                                @elseif($product->pricing_type === 'fixed')
                                                                    {{ $product->formatted_price }}
                                                                @else
                                                                    {{ $product->formatted_price }}
                                                                @endif
                                                            </span>
                                                            @if($product->use_tier_pricing)
                                                                
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                           </div>
                                        @empty
                                            <div class="col-12 text-center">
                                                <p class="text-center">Coming Soon.</p><br>
                                                <a  href="{{ route('contact') }}">Can't find what you are looking for? Contact us today!</a>
                                            </div>
                                        @endforelse
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="tab-pane fade" id="layout-list" role="tabpanel">
                            <div class="products-list list">
                                @if(isset($category))
                                    @foreach($products as $subCategoryId => $subCategoryProducts)
                                        @php
                                            $subCategory = $subCategoryProducts->first()->subCategory;
                                        @endphp
                                        <h3 class="subcategory-title">{{ $subCategory->name }}</h3>
                                        @foreach($subCategoryProducts as $product)
                                            <div class="products-entry clearfix product-wapper">
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="products-thumb">
                                                            @if($product->images->count() > 0)
                                                                <div class="product-thumb-hover">
                                                                    <a href="{{ route('shop.product', $product->slug) }}">
                                                                        <img width="600" height="600" 
                                                                             src="{{ asset('storage/app/public/' . $product->images->first()->image_path) }}" 
                                                                             class="post-image" alt="{{ $product->name }}">
                                                                        @if(isset($product->images[1]))
                                                                             <img width="600" height="600" 
                                                                                  src="{{ asset('storage/app/public/' . $product->images[1]->image_path) }}" 
                                                                                  class="hover-image back" alt="{{ $product->name }}">
                                                                        @else 
                                                                            <img width="600" height="600" 
                                                                            src="{{ asset('storage/app/public/' . $product->images->first()->image_path) }}" 
                                                                            class="hover-image back" alt="{{ $product->name }}">
                                                                         @endif
                                                                    </a>
                                                                </div>
                                                            @endif
                                                            <span class="product-quickview" data-title="Quick View">
                                                                <a href="#" class="quickview quickview-button" data-product-id="{{ $product->id }}">
                                                                    Quick View <i class="icon-search"></i>
                                                                </a>
                                                            </span>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-8">
                                                        <div class="products-content">
                                                            <div class="contents">
                                                                <h3 class="product-title">
                                                                    <a href="{{ route('shop.product', $product->slug) }}">
                                                                        {{ $product->name }}
                                                                    </a>
                                                                </h3>
                                                                <span class="price">
                                                                    @if($product->use_tier_pricing || $product->use_spot_tier_pricing)
                                                                        As low as {{ $product->formatted_lowest_price }}
                                                                    @elseif($product->pricing_type === 'fixed')
                                                                        {{ $product->formatted_price }}
                                                                    @else
                                                                        {{ $product->formatted_price }}
                                                                    @endif
                                                                </span>
                                                                @if($product->inventory_type === 'limited' && $product->quantity_available !== null)
                                                                    <p style="font-size: 12px; color: #666; margin-top: 5px;">
                                                                        @if($product->quantity_available > 0)
                                                                           {{--  Available: {{ $product->quantity_available }} --}}
                                                                        @else
                                                                            <span style="color: #dc3545;">Out of stock</span>
                                                                        @endif
                                                                    </p>
                                                                @endif
                                                                @if($product->use_tier_pricing)
                                                                    
                                                                @endif
                                                                
                                                                {{-- <div class="rating">
                                                                    <div class="star-rating" role="img" aria-label="Rated 5.00 out of 5">
                                                                        <span style="width:100%">Rated <strong class="rating">5.00</strong> out of 5</span>
                                                                    </div>
                                                                </div> --}}
                                                                <div class="product-button">
                                                                    <div class="btn-add-to-cart" data-title="Add to cart" >
                                                                        <a rel="nofollow" href="#" class="product-btn button" data-product-id="{{ $product->id }}">Add to cart</a>
                                                                    </div>
                                                                    {{-- <span class="product-quickview" data-title="Quick View">
                                                                        <a href="#" class="quickview quickview-button" data-product-id="{{ $product->id }}">
                                                                            Quick View <i class="icon-search"></i>
                                                                        </a>
                                                                    </span> --}}
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    @endforeach
                                @else
                                    @forelse($products ?? [] as $product)
                                        <div class="products-entry clearfix product-wapper">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="products-thumb">
                                                        @if($product->images->count() > 0)
                                                            <div class="product-thumb-hover">
                                                                <a href="{{ route('shop.product', $product->slug) }}">
                                                                    <img width="600" height="600" 
                                                                         src="{{ asset('storage/app/public/' . $product->images->first()->image_path) }}" 
                                                                         class="post-image" alt="{{ $product->name }}">
                                                                    @if(isset($product->images[1]))
                                                                         <img width="600" height="600" 
                                                                              src="{{ asset('storage/app/public/' . $product->images[1]->image_path) }}" 
                                                                              class="hover-image back" alt="{{ $product->name }}">
                                                                    @else 
                                                                        <img width="600" height="600" 
                                                                        src="{{ asset('storage/app/public/' . $product->images->first()->image_path) }}" 
                                                                        class="hover-image back" alt="{{ $product->name }}">
                                                                     @endif
                                                                </a>
                                                            </div>
                                                        @endif
                                                        <span class="product-quickview" data-title="Quick View">
                                                            <a href="#" class="quickview quickview-button" data-product-id="{{ $product->id }}">
                                                                Quick View <i class="icon-search"></i>
                                                            </a>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="col-md-8">
                                                    <div class="products-content">
                                                        <div class="contents">
                                                            <h3 class="product-title">
                                                                <a href="{{ route('shop.product', $product->slug) }}">
                                                                    {{ $product->name }}
                                                                </a>
                                                            </h3>
                                                            <span class="price">
                                                                @if($product->use_tier_pricing || $product->use_spot_tier_pricing)
                                                                    As low as {{ $product->formatted_lowest_price }}
                                                                @elseif($product->pricing_type === 'fixed')
                                                                    {{ $product->formatted_price }}
                                                                @else
                                                                    {{ $product->formatted_price }}
                                                                @endif
                                                            </span>
                                                            @if($product->inventory_type === 'limited' && $product->quantity_available !== null)
                                                                <p style="font-size: 12px; color: #666; margin-top: 5px;">
                                                                    @if($product->quantity_available > 0)
                                                                        {{--  Available: {{ $product->quantity_available }} --}}
                                                                    @else
                                                                        <span style="color: #dc3545;">Out of stock</span>
                                                                    @endif
                                                                </p>
                                                            @endif
                                                            @if($product->use_tier_pricing || $product->use_spot_tier_pricing)
                                                                <div class="mt-2">
                                                                    @include('_tier_price_table', ['product' => $product, 'credit_card_percentage' => $credit_card_percentage ?? 0])
                                                                </div>
                                                            @endif
                                                            
                                                            <div class="description">
                                                                @php
                                                                    $plainDesc = strip_tags($product->description);
                                                                    $limit = 180;
                                                                    $isLong = mb_strlen($plainDesc) > $limit;
                                                                @endphp
                                                                <span class="desc-short">{!! \Illuminate\Support\Str::limit($plainDesc, $limit) !!}</span>
                                                                @if($isLong)
                                                                    <span class="desc-ellipsis">...</span>
                                                                    <a href="#" class="desc-toggle" data-product-id="{{ $product->id }}">See more</a>
                                                                    <div class="desc-full" style="display:none; visibility:hidden; height:0; overflow:hidden;">{!! $product->description !!}</div>
                                                                @endif
                                                            </div>
                                                            {{-- <div class="rating">
                                                                <div class="star-rating" role="img" aria-label="Rated 5.00 out of 5">
                                                                    <span style="width:100%">Rated <strong class="rating">5.00</strong> out of 5</span>
                                                                </div>
                                                            </div> --}}
                                                            <div class="product-button">
                                                                <div class="btn-add-to-cart" data-title="Add to cart" >
                                                                    <a rel="nofollow" href="#" class="product-btn button" data-product-id="{{ $product->id }}" data-inventory-type="{{ $product->inventory_type }}" data-quantity-available="{{ $product->inventory_type === 'limited' && $product->quantity_available !== null ? $product->quantity_available : '' }}">Add to cart</a>
                                                                </div>
                                                                {{-- <span class="product-quickview" data-title="Quick View">
                                                                    <a href="#" class="quickview quickview-button" data-product-id="{{ $product->id }}">
                                                                        Quick View <i class="icon-search"></i>
                                                                    </a>
                                                                </span> --}}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @empty
                                        <div class="col-12 text-center">
                                            <p class="text-center">Coming Soon.</p><br>
                                            <a  href="{{ route('contact') }}">Can't find what you are looking for? Contact us today!</a>
                                        </div>
                                    @endforelse
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<script>
$(document).ready(function() {
    // Add to cart from thumbs page
    $('.btn-add-to-cart a').on('click', function(e) {
        e.preventDefault();
        var btn_atc = $(this);
        var productId = btn_atc.data('product-id');
        var inventoryType = btn_atc.data('inventory-type');
        var quantityAvailable = btn_atc.data('quantity-available');
        
        // Check inventory for limited products
        if (inventoryType === 'limited' && quantityAvailable !== '' && quantityAvailable !== undefined) {
            if (parseInt(quantityAvailable) <= 0) {
                alert('This product is out of stock.');
                return false;
            }
        }
        
        btn_atc.addClass('loading');
        
        $.ajax({
            url: '{{ route("cart.add") }}',
            method: 'POST',
            data: {
                product_id: productId,
                quantity: 1,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    setTimeout(function(){ 
                        // Update cart count in header
                        $('.cart-count').text(response.cart_count);
                        btn_atc.removeClass('loading');
                        // btn_atc.addClass('added');
                        
                        // Display message
                        $('body').append('<div class="cart-product-added"><div class="added-message">' + response.message + '</div>');
                        setTimeout(function() {
                            $('.cart-product-added').remove();
                        }, 2000)
                    }, 1000);
                }
            },
            error: function(xhr) {
                btn_atc.removeClass('loading');
                var errorMsg = 'Error adding product to cart';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMsg = xhr.responseJSON.message;
                }
                alert(errorMsg);
            }
        });
    });

    // Quick view functionality
    $('.quickview-button').on('click', function(e) {
        e.preventDefault();
        var productId = $(this).data('product-id');
        
        if (!productId) {
            console.error('Product ID not found');
            alert('Error: Product ID not found');
            return;
        }

        console.log('Loading product:', productId);
        
        // Load product data
        $.ajax({
            url: "{{ route('shop.quick-view', ['id' => ':id']) }}".replace(':id', productId),
            method: 'GET',
            success: function(response) {
                console.log('Quick view response:', response);
                if (response.success) {
                    var product = response.product;
                    
                    // Update quick view content
                    var imageSlider = $('.quickview-popup .slick-sliders');
                    
                    // Destroy existing slider if it exists
                    if (imageSlider.hasClass('slick-initialized')) {
                        imageSlider.slick('unslick');
                    }
                    
                    imageSlider.empty();
                    
                    // Add all product images to slider
                    if (product.images && product.images.length > 0) {
                        product.images.forEach(function(image) {
                            imageSlider.append(`
                                <div class="img-thumbnail slick-slide"> 
                                    <a href="{{ asset('storage/app/public/') }}/${image.image_path}" class="image-scroll" title="${product.name}">
                                        <img width="900" height="900" src="{{ asset('storage/app/public/') }}/${image.image_path}" alt="${product.name}">
                                    </a> 
                                </div>
                            `);
                        });
                    }
                    
                    // Update product details
                    $('.quickview-popup .product-title').text(product.name);
                    var price = '';
                     if (product.use_tier_pricing || product.use_spot_tier_pricing) {
                        price = 'As low as ' + product.formatted_lowest_price;
                    } else if (product.pricing_type === 'fixed') {
                        price = product.formatted_price;
                    } else {
                        price = product.formatted_price;
                    }
                    $('.quickview-popup .price').html(`<span>${price}</span>`);
                    
                    // Handle tier pricing table (both fixed and spot tier pricing)
                    var tierPricingContainer = $('.quickview-popup .quickview-tier-pricing');
                    // Only apply credit card fee for gold, silver, and platinum products
                    var isGoldSilverOrPlatinum = product.product_type === 'gold' || product.product_type === 'silver' || product.product_type === 'platinum';
                    var creditCardPercentage = (isGoldSilverOrPlatinum ? (response.credit_card_percentage || 0) : 0);
                    var tablesHtml = '';
                    
                    // Fixed tier pricing table
                    var tierPrices = product.tier_prices || product.tierPrices || [];
                    if (product.use_tier_pricing && tierPrices && tierPrices.length > 0) {
                        tablesHtml += '<div style="overflow-x: auto;"><table class="table table-bordered table-striped" style="margin-top: 12px; width: 100%;">';
                        tablesHtml += '<thead><tr><th>Qty</th><th>Wire/Check</th><th>CC/Paypal</th></tr></thead>';
                        tablesHtml += '<tbody>';
                        
                        // Sort tier prices by tier_start
                        var sortedTierPrices = tierPrices.slice().sort(function(a, b) {
                            // Handle both possible property names
                            var rangeA = a.price_tier_range || a.priceTierRange;
                            var rangeB = b.price_tier_range || b.priceTierRange;
                            var startA = rangeA ? rangeA.tier_start : 0;
                            var startB = rangeB ? rangeB.tier_start : 0;
                            return startA - startB;
                        });
                        
                        sortedTierPrices.forEach(function(tierPrice) {
                            // Handle both possible property names
                            var tierRange = tierPrice.price_tier_range || tierPrice.priceTierRange;
                            if (!tierRange) return;
                            var qtyLabel = tierRange.tier_start;
                            if (tierRange.tier_end) {
                                qtyLabel += ' - ' + tierRange.tier_end;
                            } else {
                                qtyLabel += '+';
                            }
                            var wirePrice = parseFloat(tierPrice.price);
                            var ccPrice = Math.round(wirePrice * (1 + (creditCardPercentage / 100)) * 100) / 100;
                            tablesHtml += '<tr>';
                            tablesHtml += '<td>' + qtyLabel + '</td>';
                            tablesHtml += '<td>$' + wirePrice.toFixed(2) + '</td>';
                            tablesHtml += '<td>$' + ccPrice.toFixed(2) + '</td>';
                            tablesHtml += '</tr>';
                        });
                        
                        tablesHtml += '</tbody></table></div>';
                    }
                    
                    // Spot tier pricing table
                    var spotTierPrices = product.spot_tier_prices || product.spotTierPrices || [];
                    if (product.use_spot_tier_pricing && spotTierPrices && spotTierPrices.length > 0 && response.spot_price !== null) {
                        var baseSpotPrice = parseFloat(response.spot_price);
                        
                        tablesHtml += '<div style="overflow-x: auto; margin-top: ' + (product.use_tier_pricing && tierPrices && tierPrices.length > 0 ? '20px' : '12px') + ';"><table class="table table-bordered table-striped" style="width: 100%;">';
                        tablesHtml += '<thead><tr><th>Qty</th><th>Wire/Check</th><th>CC/Paypal</th></tr></thead>';
                        tablesHtml += '<tbody>';
                        
                        // Sort spot tier prices by tier_start
                        var sortedSpotTierPrices = spotTierPrices.slice().sort(function(a, b) {
                            // Handle both possible property names
                            var spotTierA = a.spot_tier_price || a.spotTierPrice;
                            var spotTierB = b.spot_tier_price || b.spotTierPrice;
                            var startA = spotTierA ? spotTierA.tier_start : 0;
                            var startB = spotTierB ? spotTierB.tier_start : 0;
                            return startA - startB;
                        });
                        
                        sortedSpotTierPrices.forEach(function(spotTierPrice) {
                            // Handle both possible property names
                            var spotTier = spotTierPrice.spot_tier_price || spotTierPrice.spotTierPrice;
                            if (!spotTier) return;
                            
                            var qtyLabel = spotTier.tier_start;
                            if (spotTier.tier_end) {
                                qtyLabel += ' - ' + spotTier.tier_end;
                            } else {
                                qtyLabel += '+';
                            }
                            
                            // Get type and value from product override or from spot tier
                            var tierType = spotTierPrice.type || spotTier.type || 'percentage';
                            var tierValue = parseFloat(spotTierPrice.value || spotTier.value || 0);
                            
                            // Calculate wire price based on type
                            // Note: baseSpotPrice is already (rawSpotPrice * spot_percentage)
                            var wirePrice;
                            if (tierType === 'percentage') {
                                // Percentage type: replaces blanket markup percentage
                                // Formula: baseSpotPrice * (1 + tierPercentage / 100)
                                wirePrice = baseSpotPrice * (1 + (tierValue / 100));
                            } else { // fixed
                                // Fixed type: overrides spot price completely with fixed amount
                                wirePrice = tierValue;
                            }
                            wirePrice = Math.round(wirePrice * 100) / 100;
                            
                            // Calculate CC price
                            var ccPrice = Math.round(wirePrice * (1 + (creditCardPercentage / 100)) * 100) / 100;
                            
                            tablesHtml += '<tr>';
                            tablesHtml += '<td>' + qtyLabel + '</td>';
                            tablesHtml += '<td>$' + wirePrice.toFixed(2) + '</td>';
                            tablesHtml += '<td>$' + ccPrice.toFixed(2) + '</td>';
                            tablesHtml += '</tr>';
                        });
                        
                        tablesHtml += '</tbody></table></div>';
                    }
                    
                    if (tablesHtml) {
                        tierPricingContainer.html(tablesHtml).show();
                    } else {
                        tierPricingContainer.hide().empty();
                    }
                    
                    $('.quickview-popup .description p').html(product.description);
                    $('.quickview-popup .single-add-to-cart-button').data('product-id', product.id);
                    
                    // Update inventory information
                    var inventoryInfo = '';
                    var qtyInput = $('.quickview-popup .qty');
                    var addToCartBtn = $('.quickview-popup .single-add-to-cart-button');
                    
                    if (product.inventory_type === 'limited' && product.quantity_available !== null) {
                        qtyInput.attr('max', product.quantity_available);
                        qtyInput.data('max-quantity', product.quantity_available);
                        qtyInput.data('inventory-type', product.inventory_type);
                        
                        if (product.quantity_available > 0) {
                            inventoryInfo = '<p style="font-size: 14px; color: #666; margin-top: 10px;"><strong>Available:</strong> ' + product.quantity_available + '</p>';
                            // Check initial quantity and disable button if needed
                            var initialQty = parseInt(qtyInput.val()) || 1;
                            if (initialQty > product.quantity_available) {
                                addToCartBtn.prop('disabled', true);
                            } else {
                                addToCartBtn.prop('disabled', false);
                            }
                        } else {
                            inventoryInfo = '<p style="font-size: 14px; color: #dc3545; margin-top: 10px;"><strong>Out of stock</strong></p>';
                            qtyInput.prop('disabled', true);
                            addToCartBtn.prop('disabled', true);
                        }
                    } else {
                        qtyInput.removeAttr('max');
                        qtyInput.data('inventory-type', 'unlimited');
                        addToCartBtn.prop('disabled', false);
                    }
                    
                    // Remove existing inventory info and add new one
                    $('.quickview-popup .inventory-info').remove();
                    if (inventoryInfo) {
                        $('.quickview-popup .price-single').after('<div class="inventory-info">' + inventoryInfo + '</div>');
                    }
                    
                    // Initialize slick slider
                    imageSlider.slick({
                        dots: true,
                        infinite: true,
                        speed: 300,
                        slidesToShow: 1,
                        adaptiveHeight: true,
                        arrows: true,
                        autoplay: false,
                        fade: true,
                        cssEase: 'linear'
                    });
                    
                    // Show quick view
                    $('.quickview-popup').addClass('show');
                } else {
                    console.error('Invalid response format:', response);
                    alert('Error: Invalid response from server');
                }
            },
            error: function(xhr, status, error) {
                console.error('Quick view error:', {
                    status: status,
                    error: error,
                    response: xhr.responseText
                });
                alert('Error loading product details. Please try again.');
            }
        });
    });

    // Toggle long descriptions in list/grid
    $(document).on('click', '.desc-toggle', function(e) {
        e.preventDefault();
        var $container = $(this).closest('.description');
        var expanded = $container.data('expanded') === true;
        if (!expanded) {
            $container.find('.desc-short, .desc-ellipsis').hide();
            var $full = $container.find('.desc-full');
            $full.css({ display: 'block', visibility: 'visible', height: 'auto', overflow: 'visible' });
            $(this).text('See less');
            $container.data('expanded', true);
        } else {
            var $full = $container.find('.desc-full');
            $full.css({ display: 'none', visibility: 'hidden', height: 0, overflow: 'hidden' });
            $container.find('.desc-short, .desc-ellipsis').show();
            $(this).text('See more');
            $container.data('expanded', false);
        }
    });

    // Close quick view
    $('.quickview-close').on('click', function(e) {
        e.preventDefault();
        
        // Clear tier pricing table
        $('.quickview-popup .quickview-tier-pricing').hide().empty();
        
        // Reset quantity input
        $('.quickview-popup .qty').val(1).removeAttr('max').removeData('max-quantity').removeData('inventory-type').prop('disabled', false);
        $('.quickview-popup .single-add-to-cart-button').prop('disabled', false);
        $('.quickview-popup .inventory-info').remove();
        
        $('.quickview-popup').removeClass('show');
    });
    
    // Handle manual quantity input change in quick view
    $(document).on('change', '.quickview-popup .qty', function() {
        var input = $(this);
        var quantity = parseInt(input.val()) || 1;
        var maxQty = input.data('max-quantity');
        var inventoryType = input.data('inventory-type');
        var addToCartBtn = $('.quickview-popup .single-add-to-cart-button');
        
        if (quantity < 1) {
            quantity = 1;
            input.val(1);
        }
        
        // Validate quantity for limited inventory and disable/enable button
        if (inventoryType === 'limited' && maxQty) {
            if (quantity > maxQty) {
                // Don't show alert, just disable button and limit the input
                input.val(maxQty);
                quantity = maxQty;
            }
            // Disable button only if quantity exceeds max (not equals)
            if (quantity > maxQty) {
                addToCartBtn.prop('disabled', true);
            } else {
                addToCartBtn.prop('disabled', false);
            }
        } else {
            addToCartBtn.prop('disabled', false);
        }
    });

    // Quick view quantity buttons
    $(document).on('click', '.quickview-popup .plus', function() {
        var input = $(this).siblings('.qty');
        var value = parseInt(input.val()) || 1;
        var maxQty = input.data('max-quantity');
        var inventoryType = input.data('inventory-type');
        var addToCartBtn = $('.quickview-popup .single-add-to-cart-button');
        
        // Check if limited inventory and max quantity is set
        if (inventoryType === 'limited' && maxQty) {
            if (value < maxQty) {
                var newValue = value + 1;
                input.val(newValue);
                // Disable button only if new value exceeds max (not equals)
                if (newValue > maxQty) {
                    addToCartBtn.prop('disabled', true);
                } else {
                    addToCartBtn.prop('disabled', false);
                }
            } else {
                // Already at max, button should be enabled (can order exactly max quantity)
                addToCartBtn.prop('disabled', false);
            }
        } else {
            input.val(value + 1);
            addToCartBtn.prop('disabled', false);
        }
    });

    $(document).on('click', '.quickview-popup .minus', function() {
        var input = $(this).siblings('.qty');
        var value = parseInt(input.val()) || 1;
        var maxQty = input.data('max-quantity');
        var inventoryType = input.data('inventory-type');
        var addToCartBtn = $('.quickview-popup .single-add-to-cart-button');
        
        if (value > 1) {
            var newValue = value - 1;
            input.val(newValue);
            // Enable button when quantity decreases (it will be within limit)
            if (inventoryType === 'limited' && maxQty) {
                if (newValue <= maxQty) {
                    addToCartBtn.prop('disabled', false);
                }
            } else {
                addToCartBtn.prop('disabled', false);
            }
        }
    });

    // Add to cart from quick view
    $(document).on('click', '.quickview-popup .single-add-to-cart-button', function(e) {
        e.preventDefault();
        var btn_atc = $(this);
        var productId = btn_atc.data('product-id');
        var quantity = parseInt($('.quickview-popup .qty').val()) || 1;
        var maxQty = $('.quickview-popup .qty').data('max-quantity');
        var inventoryType = $('.quickview-popup .qty').data('inventory-type');
        
        // Validate quantity for limited inventory
        if (inventoryType === 'limited' && maxQty) {
            if (quantity > maxQty) {
                // Button should already be disabled, but double check
                return false;
            }
            if (quantity <= 0) {
                return false;
            }
        }
        
        // btn_atc.addClass('loading');
        
        $.ajax({
            url: '{{ route("cart.add") }}',
            method: 'POST',
            data: {
                product_id: productId,
                quantity: quantity,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    // Update cart count in header
                    $('.cart-count').text(response.cart_count);
                    // btn_atc.removeClass('loading');
                    // btn_atc.addClass('added');
                    
                    // Display message
                    $('body').append('<div class="cart-product-added"><div class="added-message">' + response.message + '</div>');
                    
                    // Remove message after delay
                    setTimeout(function() {
                        // Close quick view immediately
                        $('.quickview-popup').removeClass('show');
                        $('.cart-product-added').remove();
                    }, 2000);
                }
            },
            error: function(xhr) {
                var errorMsg = 'Error adding product to cart';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMsg = xhr.responseJSON.message;
                }
                alert(errorMsg);
                btn_atc.removeClass('loading');
            }
        });
    });

    $('.sort-list a').on('click', function(e) {
        e.preventDefault();
        var sort = $(this).data('sort');
        $('#sortInput').val(sort);
        $('#sortForm').submit();
    });
});
</script>
@endsection