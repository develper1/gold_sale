@extends('layouts.app')

@section('content')
<div id="title" class="page-title">
    <div class="section-container">
        <div class="content-title-heading">
            <h1 class="text-title-heading">{{ $product->name }}</h1>
        </div>
        <div class="breadcrumbs">
            <a href="{{ route('home') }}">Home</a><span class="delimiter"></span>
            <a href="{{ route('shop.index') }}">Shop</a>
            <span class="delimiter"></span>
            <a href="{{ route('shop.category', $product->subCategory->category->slug) }}">{{ $product->subCategory->category->name }}</a>
            <span class="delimiter"></span>
            <a href="{{ route('shop.subcategory', $product->subCategory->slug) }}">{{ $product->subCategory->name }}</a>
            <span class="delimiter"></span>{{ $product->name }}
        </div>
    </div>
</div>

<div id="content" class="site-content" role="main">
    <div class="shop-details zoom" data-product_layout_thumb="scroll" data-zoom_scroll="true" data-zoom_contain_lens="true" data-zoomtype="inner" data-lenssize="200" data-lensshape="square" data-lensborder="" data-bordersize="2" data-bordercolour="#f9b61e" data-popup="false">	
        <div class="product-top-info">
            <div class="section-padding">
                <div class="section-container p-l-r">
                    <div class="row">
                        <div class="product-images col-lg-7 col-md-12 col-12">
                            <div class="row">
                                <div class="col-md-2">
                                    <div class="content-thumbnail-scroll">
                                        <div class="image-thumbnail slick-carousel slick-vertical" data-asnavfor=".image-additional" data-centermode="true" data-focusonselect="true" data-columns4="5" data-columns3="4" data-columns2="4" data-columns1="4" data-columns="4" data-nav="true" data-vertical="&quot;true&quot;" data-verticalswiping="&quot;true&quot;">
                                            @foreach($product->images as $image)
                                            <div class="img-item slick-slide">
                                                <span class="img-thumbnail-scroll">
                                                    <img width="600" height="600" src="{{ asset('storage/app/public/' . $image->image_path) }}" alt="{{ $product->name }}">
                                                </span>
                                            </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-10">
                                    <div class="scroll-image main-image">
                                        <div class="image-additional slick-carousel" data-asnavfor=".image-thumbnail" data-fade="true" data-columns4="1" data-columns3="1" data-columns2="1" data-columns1="1" data-columns="1" data-nav="true">
                                            @foreach($product->images as $image)
                                            <div class="img-item slick-slide">
                                                <img width="900" height="900" src="{{ asset('storage/app/public/' . $image->image_path) }}" alt="{{ $product->name }}" title="{{ $product->name }}">
                                            </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="product-info col-lg-5 col-md-12 col-12">
                            <h1 class="title">{{ $product->name }}</h1>
                            <span class="price">
                                @if($product->pricing_type === 'fixed')
                                    ${{ number_format($product->fixed_price, 2) }}
                                @else
                                    Starting from ${{ number_format($product->fixed_price, 2) }}
                                @endif
                            </span>
                            <div class="description">
                                {!! $product->description !!}
                            </div>
                            <div class="buttons">
                                <div class="add-to-cart-wrap">
                                    <div class="quantity">
                                        <button type="button" class="plus">+</button>
                                        <input type="number" class="qty" step="1" min="1" max="" name="quantity" value="1" title="Qty" size="4" placeholder="" inputmode="numeric" autocomplete="off">
                                        <button type="button" class="minus">-</button>	
                                    </div>
                                    <div class="btn-add-to-cart">
                                        <a href="#" class="add-to-cart-btn" data-product-id="{{ $product->id }}" tabindex="0">Add to cart</a>
                                    </div>
                                </div>
                                {{-- <div class="btn-quick-buy" data-title="Wishlist">
                                    <button class="product-btn">Buy It Now</button>
                                </div> --}}
                                {{-- <div class="btn-wishlist" data-title="Wishlist">
                                    <button class="product-btn">Add to wishlist</button>
                                </div>
                                <div class="btn-compare" data-title="Compare">
                                    <button class="product-btn">Compare</button>
                                </div> --}}
                            </div>
                            <div class="product-meta">
                                <span class="posted-in">Category: <a href="{{ route('shop.category', $product->subCategory->category->slug) }}" rel="tag">{{ $product->subCategory->category->name }}</a></span>
                                <span class="tagged-as">Subcategory: <a href="{{ route('shop.subcategory', $product->subCategory->slug) }}" rel="tag">{{ $product->subCategory->name }}</a></span>
                            </div>
                            {{-- <div class="social-share">
                                <a href="#" title="Facebook" class="share-facebook" target="_blank"><i class="fa fa-facebook"></i>Facebook</a>
                                <a href="#" title="Twitter" class="share-twitter"><i class="fa fa-twitter"></i>Twitter</a>
                                <a href="#" title="Pinterest" class="share-pinterest"><i class="fa fa-pinterest"></i>Pinterest</a>
                            </div> --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="product-tabs">
            <div class="section-padding">
                <div class="section-container p-l-r">
                    <div class="product-tabs-wrap">
                        <ul class="nav nav-tabs" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" data-toggle="tab" href="#description" role="tab">Description</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#additional-information" role="tab">Additional information</a>
                            </li>
                            {{-- <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#reviews" role="tab">Reviews (0)</a>
                            </li> --}}
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane fade show active" id="description" role="tabpanel">
                                {!! $product->description !!}
                            </div>
                            <div class="tab-pane fade" id="additional-information" role="tabpanel">
                                <table class="product-attributes">
                                    <tbody>
                                        <tr class="attribute-item">
                                            <th class="attribute-label">Category</th>
                                            <td class="attribute-value">{{ $product->subCategory->category->name }}</td>
                                        </tr>
                                        <tr class="attribute-item">
                                            <th class="attribute-label">Subcategory</th>
                                            <td class="attribute-value">{{ $product->subCategory->name }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="tab-pane fade" id="reviews" role="tabpanel">
                                <div id="reviews" class="product-reviews">
                                    <div id="comments">
                                        <h2 class="reviews-title">0 reviews for <span>{{ $product->name }}</span></h2>
                                        <p class="woocommerce-noreviews">There are no reviews yet.</p>
                                    </div>
                                    <div id="review-form">
                                        <div id="respond" class="comment-respond">
                                            <span id="reply-title" class="comment-reply-title">Add a review</span>
                                            <form action="" method="post" id="comment-form" class="comment-form">
                                                <p class="comment-notes">
                                                    <span id="email-notes">Your email address will not be published.</span> Required fields are marked <span class="required">*</span>
                                                </p>
                                                <div class="comment-form-rating">
                                                    <label for="rating">Your rating</label>
                                                    <p class="stars">
                                                        <span>
                                                            <a class="star-1" href="#">1</a><a class="star-2" href="#">2</a><a class="star-3" href="#">3</a><a class="star-4" href="#">4</a><a class="star-5" href="#">5</a>						
                                                        </span>					
                                                    </p>
                                                </div>
                                                <p class="comment-form-comment">
                                                    <textarea id="comment" name="comment" placeholder="Your Reviews *" cols="45" rows="8" aria-required="true" required=""></textarea>
                                                </p>
                                                <div class="content-info-reviews">
                                                    <p class="comment-form-author">
                                                        <input id="author" name="author" placeholder="Name *" type="text" value="" size="30" aria-required="true" required="">
                                                    </p>
                                                    <p class="comment-form-email">
                                                        <input id="email" name="email" placeholder="Email *" type="email" value="" size="30" aria-required="true" required="">
                                                    </p>
                                                    <p class="form-submit">
                                                        <input name="submit" type="submit" id="submit" class="submit" value="Submit"> 
                                                    </p>	
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                    <div class="clear"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Quantity buttons
    $('.plus').click(function() {
        var input = $(this).siblings('.qty');
        var value = parseInt(input.val());
        input.val(value + 1);
    });

    $('.minus').click(function() {
        var input = $(this).siblings('.qty');
        var value = parseInt(input.val());
        if (value > 1) {
            input.val(value - 1);
        }
    });

    // Add to cart
    $('.btn-add-to-cart a').on('click', function(e) {
        e.preventDefault();
        var btn_atc = $(this);
        var productId = btn_atc.data('product-id');
        var quantity = $('.qty').val();
        btn_atc.addClass('loading');
        
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
                    setTimeout(function(){ 
                        // Update cart count in header
                        $('.cart-count').text(response.cart_count);
                        btn_atc.removeClass('loading');
                        btn_atc.addClass('added');
                        btn_atc.closest('div').append('<a href="{{ route("cart.view") }}" class="added-to-cart product-btn" title="View cart" tabindex="0">View cart</a>'); 
                        
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
});
</script>
@endpush