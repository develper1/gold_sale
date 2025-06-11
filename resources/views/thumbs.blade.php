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
                                            <a href="{{ route('shop.category', $cat->slug) }}">{{ $cat->name }}</a>
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
                                                                             src="{{ asset('public/storage/' . $product->images->first()->image_path) }}" 
                                                                             class="post-image" alt="{{ $product->name }}">
                                                                        <img width="600" height="600" 
                                                                             src="{{ asset('public/storage/' . $product->images->first()->image_path) }}" 
                                                                             class="hover-image back" alt="{{ $product->name }}">
                                                                    </a>
                                                                </div>
                                                            @endif
                                                            <div class="product-button">
                                                                <div class="btn-add-to-cart" data-title="Add to cart">
                                                                    <a rel="nofollow" href="#" class="product-btn button">Add to cart</a>
                                                                </div>
                                                                <span class="product-quickview" data-title="Quick View">
                                                                    <a href="#" class="quickview quickview-button">Quick View <i class="icon-search"></i></a>
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
                                                                        ${{ number_format($product->fixed_price, 2) }}
                                                                    @else
                                                                        Starting from ${{ number_format($product->fixed_price, 2) }}
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
                                                                         src="{{ asset('public/storage/' . $product->images->first()->image_path) }}" 
                                                                         class="post-image" alt="{{ $product->name }}">
                                                                    <img width="600" height="600" 
                                                                         src="{{ asset('public/storage/' . $product->images->first()->image_path) }}" 
                                                                         class="hover-image back" alt="{{ $product->name }}">
                                                                </a>
                                                            </div>
                                                        @endif
                                                        <div class="product-button">
                                                            <div class="btn-add-to-cart" data-title="Add to cart">
                                                                <a rel="nofollow" href="#" class="product-btn button">Add to cart</a>
                                                            </div>
                                                            <span class="product-quickview" data-title="Quick View">
                                                                <a href="#" class="quickview quickview-button">Quick View <i class="icon-search"></i></a>
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
                                                                    ${{ number_format($product->fixed_price, 2) }}
                                                                @else
                                                                    Starting from ${{ number_format($product->fixed_price, 2) }}
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
                                                                             src="{{ asset('public/storage/' . $product->images->first()->image_path) }}" 
                                                                             class="post-image" alt="{{ $product->name }}">
                                                                        <img width="600" height="600" 
                                                                             src="{{ asset('public/storage/' . $product->images->first()->image_path) }}" 
                                                                             class="hover-image back" alt="{{ $product->name }}">
                                                                    </a>
                                                                </div>
                                                            @endif
                                                            <span class="product-quickview" data-title="Quick View">
                                                                <a href="#" class="quickview quickview-button">Quick View <i class="icon-search"></i></a>
                                                            </span>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-8">
                                                        <div class="products-content">
                                                            <h3 class="product-title"><a href="{{ route('shop.product', $product->slug) }}">{{ $product->name }}</a></h3>
                                                            <span class="price">
                                                                @if($product->pricing_type === 'fixed')
                                                                    ${{ number_format($product->fixed_price, 2) }}
                                                                @else
                                                                    Starting from ${{ number_format($product->fixed_price, 2) }}
                                                                @endif
                                                            </span>
                                                            
                                                            <div class="product-button">
                                                                <div class="btn-add-to-cart" data-title="Add to cart">
                                                                    <a rel="nofollow" href="#" class="product-btn button">Add to cart</a>
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
                                                                         src="{{ asset('public/storage/' . $product->images->first()->image_path) }}" 
                                                                         class="post-image" alt="{{ $product->name }}">
                                                                    <img width="600" height="600" 
                                                                         src="{{ asset('public/storage/' . $product->images->first()->image_path) }}" 
                                                                         class="hover-image back" alt="{{ $product->name }}">
                                                                </a>
                                                            </div>
                                                        @endif
                                                        <span class="product-quickview" data-title="Quick View">
                                                            <a href="#" class="quickview quickview-button">Quick View <i class="icon-search"></i></a>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="col-md-8">
                                                    <div class="products-content">
                                                        <h3 class="product-title"><a href="{{ route('shop.product', $product->slug) }}">{{ $product->name }}</a></h3>
                                                        <span class="price">
                                                            @if($product->pricing_type === 'fixed')
                                                                ${{ number_format($product->fixed_price, 2) }}
                                                            @else
                                                                Starting from ${{ number_format($product->fixed_price, 2) }}
                                                            @endif
                                                        </span>
                                                        
                                                        <div class="product-button">
                                                            <div class="btn-add-to-cart" data-title="Add to cart">
                                                                <a rel="nofollow" href="#" class="product-btn button">Add to cart</a>
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
@endsection