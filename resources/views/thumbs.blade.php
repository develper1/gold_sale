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
                    All Products
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
                            {{-- <div class="products-sort dropdown">
                                <span class="sort-toggle dropdown-toggle" data-toggle="dropdown" aria-expanded="true">Default sorting</span>
                                <ul class="sort-list dropdown-menu" x-placement="bottom-start">
                                    <li class="active"><a href="#">Default sorting</a></li>
                                    <li><a href="#">Sort by price: low to high</a></li>
                                    <li><a href="#">Sort by price: high to low</a></li>
                                </ul>
                            </div> --}}
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
                                                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-6">
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
                                                                    @if($product->pricing_type === 'fixed')
                                                                        {{ $product->formatted_price }}
                                                                    @else
                                                                        Starting from {{ $product->formatted_price }}
                                                                    @endif
                                                                </span>
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
                                                                @if($product->pricing_type === 'fixed')
                                                                    {{ $product->formatted_price }}
                                                                @else
                                                                    Starting from {{ $product->formatted_price }}
                                                                @endif
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @empty
                                            <div class="col-12">
                                                <p class="text-center">No products found.</p>
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
                                                            <h3 class="product-title"><a href="{{ route('shop.product', $product->slug) }}">{{ $product->name }}</a></h3>
                                                            <span class="price">
                                                                @if($product->pricing_type === 'fixed')
                                                                    {{ $product->formatted_price }}
                                                                @else
                                                                    Starting from {{ $product->formatted_price }}
                                                                @endif
                                                            </span>
                                                            
                                                            <div class="product-button">
                                                                <div class="btn-add-to-cart" data-title="Add to cart" >
                                                                    <a rel="nofollow" href="#" class="product-btn button" data-product-id="{{ $product->id }}">Add to cart</a>
                                                                </div>
                                                            </div>
                                                            <div class="product-description">{!! $product->description !!}</div>			
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
                                                        <h3 class="product-title"><a href="{{ route('shop.product', $product->slug) }}">{{ $product->name }}</a></h3>
                                                        <span class="price">
                                                            @if($product->pricing_type === 'fixed')
                                                                {{ $product->formatted_price }}
                                                            @else
                                                                Starting from {{ $product->formatted_price }}
                                                            @endif
                                                        </span>
                                                        
                                                        <div class="product-button">
                                                            <div class="btn-add-to-cart" data-title="Add to cart" >
                                                                <a rel="nofollow" href="#" class="product-btn button" data-product-id="{{ $product->id }}">Add to cart</a>
                                                            </div>
                                                        </div>
                                                        <div class="product-description">{!! $product->description !!}</div>			
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @empty
                                        <div class="products-entry clearfix product-wapper">
                                            <p class="text-center">No products found.</p>
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
                        btn_atc.addClass('added');
                        
                        // Display message
                        $('body').append('<div class="cart-product-added"><div class="added-message">' + response.message + '</div>');
                        setTimeout(function() {
                            $('.cart-product-added').remove();
                        }, 2000)
                    }, 1000);
                }
            },
            error: function(xhr) {
                alert('Error adding product to cart');
                btn_atc.removeClass('loading');
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
                    const price = product.pricing_type === 'fixed' ? 
                        product.formatted_price : 
                        'Starting from ' + product.formatted_price;
                    $('.quickview-popup .price').html(`<span>${price}</span>`);
                    $('.quickview-popup .description p').html(product.description);
                    $('.quickview-popup .single-add-to-cart-button').data('product-id', product.id);
                    
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

    // Close quick view
    $('.quickview-close').on('click', function(e) {
        e.preventDefault();

             $('.quickview-popup').removeClass('active');
    });

    // Quick view quantity buttons
    $('.quickview-popup .plus').click(function() {
        var input = $(this).siblings('.qty');
        var value = parseInt(input.val());
        input.val(value + 1);
    });

    $('.quickview-popup .minus').click(function() {
        var input = $(this).siblings('.qty');
        var value = parseInt(input.val());
        if (value > 1) {
            input.val(value - 1);
        }
    });

    // Add to cart from quick view
    $('.quickview-popup .single-add-to-cart-button').on('click', function(e) {
        e.preventDefault();
        var btn_atc = $(this);
        var productId = btn_atc.data('product-id');
        var quantity = $('.quickview-popup .qty').val();
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
                    btn_atc.addClass('added');
                    
                    // Display message
                    $('body').append('<div class="cart-product-added"><div class="added-message">' + response.message + '</div>');
                    
                    // Close quick view immediately
                    $('.quickview-popup').removeClass('show');
                    
                    // Remove message after delay
                    setTimeout(function() {
                        $('.cart-product-added').remove();
                    }, 2000);
                }
            },
            error: function(xhr) {
                alert('Error adding product to cart');
                btn_atc.removeClass('loading');
            }
        });
    });
});
</script>
@endsection