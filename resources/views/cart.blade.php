@php
$sliders = \App\Models\HomeSlider::orderBy('order')->get();
@endphp
@extends('layouts.app')

@section('content')

{{-- Reusable Slider Component --}}
<x-mainslider :sliders="$sliders" height="30vh" autoplay="true" />

<x-page-header 
    title="Shopping Cart" 
    :breadcrumbs="[
        ['label' => 'Home', 'url' => '/home'],
        ['label' => 'Shop', 'url' => '/thumbs'],
        ['label' => 'Shopping Cart']
    ]" 
/>

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
                                                <span class="price item-price" data-original-price="{{ $item['price'] }}">
                                                    @if($item['pricing_type'] === 'fixed')
                                                        ${{ number_format($item['price'], 2) }}
                                                    @else
                                                        ${{ number_format($item['price'], 2) }}
                                                    @endif
                                                </span>
                                                @if(
                                                    (isset($item['use_tier_pricing']) && $item['use_tier_pricing']) ||
                                                    (isset($item['use_spot_tier_pricing']) && $item['use_spot_tier_pricing'])
                                                )
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
                                                    <input type="number" class="qty" step="1" min="1" max="{{ isset($item['inventory_type']) && $item['inventory_type'] === 'limited' && isset($item['quantity_available']) ? $item['quantity_available'] : '' }}" name="quantity" value="{{ $item['quantity'] }}" title="Qty" size="4" placeholder="" inputmode="numeric" autocomplete="off" data-product-id="{{ $item['id'] }}" data-max-quantity="{{ isset($item['inventory_type']) && $item['inventory_type'] === 'limited' && isset($item['quantity_available']) ? $item['quantity_available'] : '' }}" data-inventory-type="{{ $item['inventory_type'] ?? 'unlimited' }}">
                                                    <button type="button" class="quantity-button plus">+</button>
                                                </div>
                                                @if(isset($item['inventory_type']) && $item['inventory_type'] === 'limited' && isset($item['quantity_available']))
                                                    <p style="font-size: 12px; color: #666; margin-top: 5px;">
                                                        Available: {{ $item['quantity_available'] }}
                                                    </p>
                                                @endif
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
                                @if(auth()->check())
                                        
                                    @else
                                        <div class="alert alert-info" style="
                                            background-color: #fff3cd;
                                            color: #856404;
                                            padding: 15px;
                                            margin-bottom: 15px;
                                            border-radius: 8px;
                                            border-left: 5px solid #ffc107;
                                            text-align: center;
                                            font-weight: 500;
                                        ">
                                            <i class="fa fa-info-circle" style="margin-right: 8px;"></i>
                                            You must <a href="{{ route('login') }}" style="color: #856404; text-decoration: underline; font-weight: bold;">register or login</a> to finalize your purchase.
                                        </div>
                                    @endif
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

@push('styles')
<style>
    /* Ensure remove button and icon are clickable */
    .remove-item {
        cursor: pointer;
        display: inline-block;
        position: relative;
    }
    .remove-item i {
        pointer-events: none; /* Allow clicks to pass through icon to parent link */
    }
    .remove-item:hover {
        opacity: 0.7;
    }
    .remove-item:active {
        opacity: 0.5;
    }
    
    /* Fix quickview popup blocking clicks when hidden */
    .quickview-popup {
        pointer-events: none; /* Allow clicks to pass through when hidden */
    }
    .quickview-popup.show {
        pointer-events: auto; /* Enable clicks when shown */
    }
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    // Cookie helper functions
    function getCookie(name) {
        const nameEQ = name + "=";
        const ca = document.cookie.split(';');
        for (let i = 0; i < ca.length; i++) {
            let c = ca[i];
            while (c.charAt(0) === ' ') c = c.substring(1, c.length);
            if (c.indexOf(nameEQ) === 0) {
                try {
                    return JSON.parse(c.substring(nameEQ.length, c.length));
                } catch (e) {
                    return null;
                }
            }
        }
        return null;
    }

    // Function to get spot prices from cookies
    function getSpotPricesFromCookies() {
        const cachedLatest = getCookie('metalPricesLatest');
        const spotPrices = {};
        
        if (cachedLatest && cachedLatest.data && cachedLatest.data.rates) {
            const rates = cachedLatest.data.rates;
            const baseCurrency = 'USD';
            
            // Map metal codes to product types
            // XAU-ASK = Gold, XAG-ASK = Silver, XPT-ASK = Platinum, XPD-ASK = Palladium
            if (rates[baseCurrency + 'XAU-ASK']) {
                spotPrices['gold'] = rates[baseCurrency + 'XAU-ASK'];
            }
            if (rates[baseCurrency + 'XAG-ASK']) {
                spotPrices['silver'] = rates[baseCurrency + 'XAG-ASK'];
            }
            if (rates[baseCurrency + 'XPT-ASK']) {
                spotPrices['platinum'] = rates[baseCurrency + 'XPT-ASK'];
            }
            if (rates[baseCurrency + 'XPD-ASK']) {
                spotPrices['palladium'] = rates[baseCurrency + 'XPD-ASK'];
            }
        }
        
        return Object.keys(spotPrices).length > 0 ? spotPrices : null;
    }

    // Handle quantity changes
    $('.quantity-button').on('click', function(e) {
        e.preventDefault();
        var input = $(this).siblings('input.qty');
        var currentVal = parseInt(input.val()) || 1;
        var productId = $(this).closest('tr').data('product-id');
        var maxQty = input.data('max-quantity');
        var inventoryType = input.data('inventory-type');
        
        if ($(this).hasClass('plus')) {
            // Check if limited inventory and max quantity is set
            if (inventoryType === 'limited' && maxQty) {
                if (currentVal < maxQty) {
                    input.val(currentVal + 1);
                    updateCart(productId, input.val());
                }
                // Don't show alert, just don't increment if at max
            } else {
                input.val(currentVal + 1);
                updateCart(productId, input.val());
            }
        } else if ($(this).hasClass('minus') && currentVal > 1) {
            input.val(currentVal - 1);
            updateCart(productId, input.val());
        }
    });

    // Handle direct quantity input
    $('.qty').on('change', function() {
        var productId = $(this).closest('tr').data('product-id');
        var quantity = parseInt($(this).val()) || 1;
        var maxQty = $(this).data('max-quantity');
        var inventoryType = $(this).data('inventory-type');
        
        if (quantity < 1) {
            quantity = 1;
            $(this).val(1);
        }
        
        // Validate quantity for limited inventory - no alert, just correct the value
        if (inventoryType === 'limited' && maxQty) {
            if (quantity > maxQty) {
                // Don't show alert, just limit the input to max quantity
                $(this).val(maxQty);
                quantity = maxQty;
            }
        }
        
        updateCart(productId, quantity);
    });

    // Function to update cart
    function updateCart(productId, quantity) {
        // Get spot prices from cookies
        var spotPrices = getSpotPricesFromCookies();
        
        var requestData = {
            _token: '{{ csrf_token() }}',
            product_id: productId,
            quantity: quantity
        };
        
        // Add spot prices if available from cookies
        if (spotPrices) {
            requestData.spot_prices = spotPrices;
        }
        
        $.ajax({
            url: '{{ route("cart.update") }}',
            method: 'POST',
            data: requestData,
            success: function(response) {
                if (response.success) {
                    // Update cart count
                    $('.cart-count').text(response.cart_count);
                    
                    // Update item price and total
                    var itemElement = $('tr[data-product-id="' + productId + '"]');
                    var newPrice = parseFloat(response.item_price);
                    
                    // Round price to 2 decimal places consistently
                    newPrice = Math.round(newPrice * 100) / 100;
                    
                    // Update the displayed price (handles tier pricing changes)
                    // Backend calculates the correct price based on quantity and tier pricing
                    itemElement.find('.item-price').text('$' + newPrice.toFixed(2));
                    // Update the data attribute with the new price
                    itemElement.find('.item-price').data('original-price', newPrice);
                    
                    // Calculate subtotal using the price from backend (which includes tier pricing)
                    var itemTotal = newPrice * quantity;
                    itemTotal = Math.round(itemTotal * 100) / 100;
                    itemElement.find('.item-total').text('$' + itemTotal.toFixed(2));
                    
                    // Update cart totals from backend response (round for consistency)
                    var subtotal = Math.round(parseFloat(response.subtotal) * 100) / 100;
                    var total = Math.round(parseFloat(response.total) * 100) / 100;
                    $('.sub-total-price').text('$' + subtotal.toFixed(2));
                    $('.cart-total').text('$' + total.toFixed(2));
                }
            },
            error: function(xhr) {
                console.error('Error updating cart:', xhr);
            }
        });
    }

    // Handle remove item - handle clicks on both the link and the icon
    $(document).on('click', '.remove-item, .remove-item i', function(e) {
        e.preventDefault();
        e.stopPropagation();
        
        // Get productId from the closest .remove-item element (in case icon was clicked)
        var $removeLink = $(this).closest('.remove-item').length ? $(this).closest('.remove-item') : $(this);
        var productId = $removeLink.data('product-id');
        
        // If still no productId, try getting it from the table row
        if (!productId) {
            productId = $removeLink.closest('tr').data('product-id');
        }
        
        // Ensure productId is treated as integer
        productId = parseInt(productId);
        
        if (!productId || isNaN(productId)) {
            console.error('Invalid product ID:', productId);
            alert('Invalid product ID. Please refresh the page and try again.');
            return false;
        }
        
        // Disable the button to prevent multiple clicks
        $removeLink.css('pointer-events', 'none').css('opacity', '0.5');
        
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
                    var $itemRow = $('tr[data-product-id="' + productId + '"]');
                    $itemRow.fadeOut(300, function() {
                        $(this).remove();
                        
                        // Update cart totals (round for consistency)
                        var subtotal = Math.round(parseFloat(response.subtotal) * 100) / 100;
                        var total = Math.round(parseFloat(response.total) * 100) / 100;
                        $('.sub-total-price').text('$' + subtotal.toFixed(2));
                        $('.cart-total').text('$' + total.toFixed(2));
                        $('.cart-count').text(response.cart_count);
                        
                        // Show empty cart if no items left
                        if (response.cart_count === 0) {
                            setTimeout(function() {
                                location.reload(); // Reload to show empty cart message properly
                            }, 300);
                        }
                    });
                } else {
                    // Re-enable the button on error
                    $removeLink.css('pointer-events', 'auto').css('opacity', '1');
                    alert(response.message || 'Error removing item from cart');
                }
            },
            error: function(xhr) {
                // Re-enable the button on error
                $removeLink.css('pointer-events', 'auto').css('opacity', '1');
                
                var errorMsg = 'Error removing item from cart';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMsg = xhr.responseJSON.message;
                } else if (xhr.status === 404) {
                    errorMsg = 'Item not found in cart. Please refresh the page.';
                } else if (xhr.status === 500) {
                    errorMsg = 'Server error. Please try again or refresh the page.';
                }
                alert(errorMsg);
                console.error('Error removing item:', xhr);
            }
        });
        
        return false;
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