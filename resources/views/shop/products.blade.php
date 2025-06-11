@extends('layouts.app')

@section('content')
<div class="shop-page">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-title">{{ $subcategory->name }}</h1>
                @if($subcategory->description)
                    <p class="subcategory-description">{{ $subcategory->description }}</p>
                @endif
            </div>
        </div>
        
        <div class="row">
            @forelse($products as $product)
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="product-card">
                        @if($product->images->count() > 0)
                            <div class="product-image">
                                <a href="{{ route('product.show', $product->slug) }}">
                                    <img src="{{ asset('storage/' . $product->images->first()->image_path) }}" 
                                         alt="{{ $product->name }}" class="img-fluid">
                                </a>
                            </div>
                        @endif
                        
                        <div class="product-info">
                            <h3 class="product-title">
                                <a href="{{ route('product.show', $product->slug) }}">{{ $product->name }}</a>
                            </h3>
                            
                            <div class="product-price">
                                @if($product->pricing_type === 'fixed')
                                    ${{ number_format($product->fixed_price, 2) }}
                                @else
                                    Starting from ${{ number_format($product->fixed_price, 2) }}
                                @endif
                            </div>
                            
                            <div class="product-actions">
                                <a href="{{ route('product.show', $product->slug) }}" class="btn btn-primary">View Details</a>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <p class="text-center">No products found in this category.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection 