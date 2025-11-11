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
                            @php
                                $basePrice = (float) $product->current_price; // or whatever your price variable is
                                // Round base price to 2 decimal places for consistency
                                $basePrice = round($basePrice, 2);
                                
                                $percentageToAdd = (float) ($credit_card_percentage ?? 0);
                                $creditCardPrice = round($basePrice * (1 + ($percentageToAdd / 100)), 2);
                            @endphp

                            <div style="display: flex; gap: 10px;">
                                <div style="background: #cb8161; color: #fff; padding: 4px 10px; text-align: center;">
                                    <div style="font-weight: bold; letter-spacing: 1px;">CHECK / WIRE</div>
                                    <div style="background: #f3f3f3; color: #222; font-size: 1.5rem;">
                                        ${{ number_format($basePrice, 2) }}
                                    </div>
                                </div>
                                <div style="background: #cb8161; color: #fff; padding: 4px 10px; text-align: center;">
                                    <div style="font-weight: bold; letter-spacing: 1px;">CREDIT CARD / PAYPAL</div>
                                    <div style="background: #f3f3f3; color: #222; font-size: 1.5rem;">
                                        ${{ number_format($creditCardPrice, 2) }}
                                    </div>
                                </div>
                            </div>
                            {{-- <span class="price">
                                @if($product->pricing_type === 'fixed')
                                    {{ $product->formatted_price }}
                                @else
                                    Starting from {{ $product->formatted_price }}
                                @endif
                            </span> --}}
                            @if($product->use_tier_pricing || $product->use_spot_tier_pricing)
                                <div class="mt-2">
                                    @include('_tier_price_table', ['product' => $product, 'credit_card_percentage' => $credit_card_percentage])
                                </div>
                            @endif
                            @if($product->inventory_type === 'limited' && $product->quantity_available !== null)
                                @php
                                    $availableQty = $product->quantity_available;
                                    $isInStock = $availableQty > 0;
                                @endphp
                                <p class="stock {{ $isInStock ? 'in-stock' : 'out-of-stock' }}">
                                    Availability: 
                                    <span>
                                        @if($isInStock)
                                            In stock ({{ $availableQty }} available)
                                        @else
                                            Out of stock
                                        @endif
                                    </span>
                                </p>
                            @else
                                <p class="stock in-stock">Availability: <span>In stock</span></p>
                            @endif
                            <div class="description">
                                {!! $product->description !!}
                            </div>
                            <div class="buttons">
                                <div class="add-to-cart-wrap">
                                    <div class="quantity">
                                        <button type="button" class="plus">+</button>
                                        <input type="number" class="qty quantity-input" step="1" min="1" max="{{ $product->inventory_type === 'limited' && $product->quantity_available !== null ? $product->quantity_available : '' }}" name="quantity" value="1" title="Qty" size="4" placeholder="" inputmode="numeric" autocomplete="off" data-max-quantity="{{ $product->inventory_type === 'limited' && $product->quantity_available !== null ? $product->quantity_available : '' }}" data-inventory-type="{{ $product->inventory_type }}">
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
        var value = parseInt(input.val()) || 1;
        var maxQty = input.data('max-quantity');
        var inventoryType = input.data('inventory-type');
        
        // Check if limited inventory and max quantity is set
        if (inventoryType === 'limited' && maxQty) {
            if (value < maxQty) {
                input.val(value + 1);
            } else {
                alert('Maximum available quantity is ' + maxQty);
            }
        } else {
            input.val(value + 1);
        }
    });

    $('.minus').click(function() {
        var input = $(this).siblings('.qty');
        var value = parseInt(input.val()) || 1;
        if (value > 1) {
            input.val(value - 1);
        }
    });

    // Add to cart
    $('.btn-add-to-cart a').on('click', function(e) {
        e.preventDefault();
        var btn_atc = $(this);
        var productId = btn_atc.data('product-id');
        var quantity = parseInt($('.qty').val()) || 1;
        var maxQty = $('.qty').data('max-quantity');
        var inventoryType = $('.qty').data('inventory-type');
        
        // Validate quantity for limited inventory
        if (inventoryType === 'limited' && maxQty) {
            if (quantity > maxQty) {
                alert('You cannot order more than ' + maxQty + ' items. Only ' + maxQty + ' available in stock.');
                return false;
            }
            if (quantity <= 0) {
                alert('Please enter a valid quantity.');
                return false;
            }
        }
        
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
                btn_atc.removeClass('loading');
                var errorMsg = 'Error adding product to cart';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMsg = xhr.responseJSON.message;
                }
                alert(errorMsg);
            }
        });
    });

    // // Function to update price based on quantity
    // function updatePrice(quantity) {
    //     var productId = '{{ $product->id }}';
        
    //     $.ajax({
    //         url: `/api/products/${productId}/tier-price`,
    //         method: 'GET',
    //         data: { quantity: quantity },
    //         success: function(response) {
    //             // Update the price display
    //             $('.product-price').text('$' + response.price.toFixed(2));
                
    //             // If you have a total price element, update it too
    //             if($('.product-total-price').length) {
    //                 var total = response.price * quantity;
    //                 $('.product-total-price').text('$' + total.toFixed(2));
    //             }
    //         },
    //         error: function(xhr) {
    //             console.error('Error fetching tier price:', xhr);
    //         }
    //     });
    // }

    // // Listen for quantity changes
    // $('.quantity-input').on('change', function() {
    //     var quantity = parseInt($(this).val());
    //     if(quantity > 0) {
    //         updatePrice(quantity);
    //     }
    // });

    // // Initial price update
    // var initialQuantity = parseInt($('.quantity-input').val());
    // if(initialQuantity > 0) {
    //     updatePrice(initialQuantity);
    // }
});
</script>
@endpush