@extends('layouts.app')

@section('content')
<div class="shop-page">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-title">Shop</h1>
            </div>
        </div>
        
        <div class="row">
            @foreach($categories as $category)
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="category-card">
                        <h2 class="category-title">{{ $category->name }}</h2>
                        @if($category->description)
                            <p class="category-description">{{ $category->description }}</p>
                        @endif
                        
                        @if($category->subCategories->count() > 0)
                            <ul class="subcategory-list">
                                @foreach($category->subCategories as $subCategory)
                                    <li>
                                        <a href="{{ route('shop.subcategory', $subCategory->slug) }}">
                                            {{ $subCategory->name }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection 