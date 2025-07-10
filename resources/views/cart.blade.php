@extends('layouts.app')

@section('content')

<div id="title" class="page-title">
    <div class="section-container">
        <div class="content-title-heading">
            <h1 class="text-title-heading">
                Shopping Cart
            </h1>
        </div>
        <div class="breadcrumbs">
            <a href="{{ route('home') }}">Home</a><span class="delimiter"></span>
            <a href="{{ route('shop.index') }}">Shop</a><span class="delimiter"></span>Shopping Cart
        </div>
    </div>
</div>

<div id="content" class="site-content" role="main">
    <div class="section-padding">
        <div class="section-container p-l-r">
            @if(count($cart) > 0)
            <div class="shop-cart">	
                <div class="row">
                    <div class="col-xl-8 col-lg-12 col-md-12 col-12">
                        <div class="table-responsive">
                            <table class="cart-items table" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th class="product-thumbnail">Product</th>
                                        <th class="product-price">Price</th>
                                        <th class="product-quantity">Quantity</th>
                                        <th class="product-subtotal">Subtotal</th>
                                        <th class="product-remove">&nbsp;</th>
                                    </tr>
                                </thead>
                                <tbody class="cart-items">
                                    @php
                                        $total = 0;
                                    @endphp
                                    @forelse($cart as $item)
                                        <tr data-product-id="{{ $item['id'] }}">
                                            
                                            <td class="product-thumbnail">
                                                <a href="{{ route('shop.product', $item['slug']) }}">
                                                    <img width="600" height="600" src="{{ asset('storage/app/public/' . $item['image']) }}" class="product-image" alt="{{ $item['name'] }}">
                                                </a>
                                                <a href="{{ route('shop.product', $item['slug']) }}">{{ $item['name'] }}</a>
                                            </td>
                                            {{-- <td class="product-name">
                                                
                                            </td> --}}
                                            <td class="product-price">
                                                <span class="price item-price">
                                                    @if($item['pricing_type'] === 'fixed')
                                                        ${{ number_format($item['price'], 2) }}
                                                    @else
                                                        ${{ number_format($item['price'], 2) }}
                                                    @endif
                                                </span>
                                                @if(isset($item['use_tier_pricing']) && $item['use_tier_pricing'])
                                                    <p>
                                                        <a href="#" class="view-tier-prices-link" data-product-id="{{ $item['id'] }}">
                                                            View Tier Prices
                                                        </a>
                                                    </p>
                                                @endif
                                            </td>
                                            <td class="product-quantity">
                                                <div class="quantity">
                                                    <button type="button" class="quantity-button minus">-</button>	
                                                    <input type="number" class="qty" step="1" min="1" max="" name="quantity" value="{{ $item['quantity'] }}" title="Qty" size="4" placeholder="" inputmode="numeric" autocomplete="off" data-product-id="{{ $item['id'] }}">
                                                    <button type="button" class="quantity-button plus">+</button>
                                                </div>
                                            </td>
                                            <td class="product-subtotal">
                                                <span class="price item-total">${{ number_format($item['price'] * $item['quantity'], 2) }}</span>
                                            </td>
                                            <td class="product-remove">
                                                <a href="#" class="remove-item" data-product-id="{{ $item['id'] }}"><i class="icon_close"></i></a>
                                            </td>
                                        </tr>
                                        @php
                                            $total += $item['price'] * $item['quantity'];
                                        @endphp
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center">Your cart is empty</td>
                                        </tr>
                                    @endforelse
                                    <tr>
                                        <td colspan="6" class="actions">
                                            <div class="bottom-cart">
                                                {{-- Coupon field removed, now on checkout page --}}
                                                <h2><a href="{{ route('shop.index') }}">Continue Shopping</a></h2>
                                                {{-- <button type="submit" name="update_cart" class="button" value="Update cart">Update cart</button> --}}
                                            </div>	
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-xl-4 col-lg-12 col-md-12 col-12">
                        <div class="cart-totals">
                            <h2>Cart totals</h2>
                            <div>
                                <div class="cart-subtotal">
                                    <div class="title">Subtotal</div>
                                    <div><span class="sub-total-price">${{ number_format($total, 2) }}</span></div>
                                </div>
                                <div class="shipping-totals">
                                    <div class="title">Shipping</div>
                                    <div>
                                        {{-- <ul class="shipping-methods custom-radio">
                                            <li>
                                                <input type="radio" name="shipping_method" data-index="0" value="free_shipping" class="shipping_method" checked="checked"><label>Free shipping</label>
                                            </li>
                                            <li>
                                                <input type="radio" name="shipping_method" data-index="0" value="flat_rate" class="shipping_method"><label>Flat rate</label>					
                                            </li>
                                        </ul> --}}
                                        <p class="shipping-desc">
                                            Shipping options will be updated during checkout.				
                                        </p>
                                    </div>
                                </div>
                                <div class="order-total">
                                    <div class="title">Total</div>
                                    <div><span class="cart-total">${{ number_format($total, 2) }}</span></div>
                                </div>
                            </div>
                            <div class="proceed-to-checkout">		
                                <a href="{{ route('checkout') }}" class="checkout-button button">
                                    Proceed to checkout
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @else
            <div class="shop-cart-empty">
                <div class="notices-wrapper">
                    <p class="cart-empty">Your cart is currently empty.</p>
                </div>	
                <div class="return-to-shop">
                    <a class="button" href="{{ route('shop.index') }}">
                        Return to shop		
                    </a>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    // Handle quantity changes
    $('.quantity-button').on('click', function(e) {
        e.preventDefault();
        var input = $(this).siblings('input.qty');
        var currentVal = parseInt(input.val());
        var productId = $(this).closest('tr').data('product-id');
        
        if ($(this).hasClass('plus')) {
            input.val(currentVal + 1);
        } else if ($(this).hasClass('minus') && currentVal > 1) {
            input.val(currentVal - 1);
        }
        
        // Update cart after quantity change
        updateCart(productId, input.val());
    });

    // Handle direct quantity input
    $('.qty').on('change', function() {
        var productId = $(this).closest('tr').data('product-id');
        var quantity = $(this).val();
        
        if (quantity < 1) {
            quantity = 1;
            $(this).val(1);
        }
        
        updateCart(productId, quantity);
    });

    // Function to update cart
    function updateCart(productId, quantity) {
        $.ajax({
            url: '{{ route("cart.update") }}',
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                product_id: productId,
                quantity: quantity
            },
            success: function(response) {
                if (response.success) {
                    // Update cart count
                    $('.cart-count').text(response.cart_count);
                    
                    // Update item price and total
                    var itemElement = $('tr[data-product-id="' + productId + '"]');
                    var itemPrice = parseFloat(response.item_price);
                    itemElement.find('.item-price').text('$' + itemPrice.toFixed(2));
                    var itemTotal = itemPrice * quantity;
                    itemElement.find('.item-total').text('$' + itemTotal.toFixed(2));
                    
                    // Update cart totals
                    $('.sub-total-price').text('$' + parseFloat(response.subtotal).toFixed(2));
                    $('.cart-total').text('$' + parseFloat(response.total).toFixed(2));
                }
            },
            error: function(xhr) {
                console.error('Error updating cart:', xhr);
            }
        });
    }

    // Handle remove item
    $('.remove-item').on('click', function(e) {
        e.preventDefault();
        var productId = $(this).data('product-id');
        
        $.ajax({
            url: '{{ route("cart.remove") }}',
            method: 'POST',
            data: {
                product_id: productId,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    // Remove item row
                    $('tr[data-product-id="' + productId + '"]').remove();
                    
                    // Update cart totals
                    $('.sub-total-price').text('$' + response.subtotal.toFixed(2));
                    $('.cart-total').text('$' + response.total.toFixed(2));
                    $('.cart-count').text(response.cart_count);
                    
                    // Show empty cart if no items left
                    if (response.cart_count === 0) {
                        $('.cart-items').html('<tr><td colspan="6" class="text-center">Your cart is empty</td></tr>');
                    }
                }
            },
            error: function(xhr) {
                alert('Error removing item from cart');
            }
        });
    });




    // Function to update cart total
    function updateCartTotal() {
        var total = 0;
        $('.item-total').each(function() {
            total += parseFloat($(this).text().replace('$', ''));
        });
        $('.cart-total').text('$' + total.toFixed(2));
    }


});
</script>
@endpush

@endsection