<header id="site-header" class="site-header header-v4">
    <div class="header-mobile">
        <div class="section-padding">
            <div class="section-container">
                <div class="row">
                    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-3 col-3 header-left">
                        <div class="navbar-header">
                            <button type="button" id="show-megamenu" class="navbar-toggle"></button>
                        </div>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-6 col-6 header-center">
                        <div class="site-logo">
                            <a href="{{ route('home') }}">
                                <img width="400" height="79" src="{{ asset('assets/media/logo.png') }}" alt="Oasis Mint">
                            </a>
                        </div>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-3 col-3 header-right">
                        <div class="mojuri-topcart dropdown">
                            <div class="dropdown mini-cart top-cart">
                                <div class="remove-cart-shadow"></div>
                                <a class="cart-icon" href="{{ route('cart.view') }}" >
                                    <div class="icons-cart"><i class="icon-large-paper-bag"></i><span class="cart-count">0</span></div>
                                </a>
                                <div class="dropdown-menu cart-popup">
                                    <div class="cart-empty-wrap" style="display: none;">
                                        <ul class="cart-list">
                                            <li class="empty">
                                                <span>No products in the cart.</span>
                                                <a class="go-shop" href="{{ route('shop.index') }}">GO TO SHOP<i aria-hidden="true" class="arrow_right"></i></a>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="cart-list-wrap">
                                        <ul class="cart-list">
                                            @php
                                                $cart = session()->get('cart', []);
                                                $total = 0;
                                                $hasItems = !empty($cart);
                                            @endphp
                                            @if($hasItems)
                                                @foreach($cart as $item)
                                                    <li class="mini-cart-item">
                                                        <a href="#" class="remove" title="Remove this item" data-product-id="{{ $item['id'] }}"><i class="icon_close"></i></a>
                                                        <a href="{{ route('shop.product', $item['slug']) }}" class="product-image">
                                                            <img width="600" height="600" src="{{ asset('storage/app/public/' . $item['image']) }}" alt="{{ $item['name'] }}">
                                                        </a>
                                                        <a href="{{ route('shop.product', $item['slug']) }}" class="product-name">{{ $item['name'] }}</a>		
                                                        <div class="quantity">Qty: {{ $item['quantity'] }}</div>
                                                        <div class="price">
                                                            @if($item['pricing_type'] === 'fixed')
                                                                ${{ number_format($item['price'], 2) }}
                                                            @else
                                                                Starting from ${{ number_format($item['price'], 2) }}
                                                            @endif
                                                        </div>
                                                        @php
                                                            $total += $item['price'] * $item['quantity'];
                                                        @endphp
                                                    </li>
                                                @endforeach
                                            @else
                                                <li class="empty">
                                                    <span>No products in the cart.</span>
                                                    <a class="go-shop" href="{{ route('shop.index') }}">GO TO SHOP<i aria-hidden="true" class="arrow_right"></i></a>
                                                </li>
                                            @endif
                                        </ul>
                                        @if($hasItems)
                                            <div class="total-cart">
                                                <div class="title-total">Total: </div>
                                                <div class="total-price"><span>${{ number_format($total, 2) }}</span></div>
                                            </div>
                                            {{-- <div class="free-ship">
                                                <div class="title-ship">Buy <strong>$400</strong> more to enjoy <strong>FREE Shipping</strong></div>
                                                <div class="total-percent"><div class="percent" style="width:{{ min(($total/400) * 100, 100) }}%"></div></div>
                                            </div> --}}
                                            <div class="buttons">
                                                <a href="{{ route('cart.view') }}" class="button btn view-cart btn-primary">View cart</a>
                                                <a href="#" class="button btn checkout btn-default">Check out</a>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="header-mobile-fixed">
            <!-- Shop -->
            <div class="shop-page">
                <a href="{{ route('shop.index') }}"><i class="wpb-icon-shop"></i></a>
            </div>

            <!-- Login -->
            <div class="my-account">
                <div class="login-header">
                    <a href="{{ route('account') }}"><i class="wpb-icon-user"></i></a>
                </div>
            </div>

            <!-- Search -->
            {{-- <div class="search-box">
                <div class="search-toggle"><i class="wpb-icon-magnifying-glass"></i></div>
            </div> --}}

            <!-- Wishlist -->
            {{-- <div class="wishlist-box">
                <a href="shop-wishlist.html"><i class="wpb-icon-heart"></i></a>
            </div> --}}
        </div>
    </div>

    <div class="header-desktop">
        <div class="header-wrapper">
            <div class="section-padding">
                <div class="section-container large p-l-r">
                    <div class="row">
                        <div class="col-xl-3 col-lg-3 col-md-12 col-sm-12 col-12 header-left">
                            <div class="site-logo">
                                <a href="{{ route('home') }}">
                                    <img width="400" height="140" src="{{ asset('assets/media/logo.png') }}" alt="Oasis Mint">
                                </a>
                            </div>
                        </div>

                        <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12 text-center header-center">
                            <div class="site-navigation">
                                <nav id="main-navigation">
                                    <ul id="menu-main-menu" class="menu">
                                        <li class="level-0 menu-item">
                                            <a href="{{ route('home') }}"><span class="menu-item-text">Home</span></a>
                                        </li>
                                        <li class="level-0 menu-item menu-item-has-children">
                                            <a href="{{ route('shop.index') }}"><span class="menu-item-text">Shop</span></a>
                                            <ul class="sub-menu">
                                                @foreach($categories as $category)
                                                    <li class="menu-item-has-children">
                                                        <a href="{{ route('shop.category', $category->slug) }}"><span class="menu-item-text">{{ $category->name }}</span></a>
                                                        <ul class="sub-menu">
                                                            @foreach($category->subCategories as $subCategory)
                                                                <li>
                                                                    <a href="{{ route('shop.subcategory', $subCategory->slug) }}">
                                                                        <span class="menu-item-text">{{ $subCategory->name }}</span>
                                                                    </a>
                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </li>
                                        <li class="level-0 menu-item">
                                            <a href="{{ route('about') }}"><span class="menu-item-text">About</span></a>
                                        </li>
                                        <li class="level-0 menu-item">
                                            <a href="{{ route('faq') }}"><span class="menu-item-text">FAQs</span></a>
                                        </li>
                                        <li class="level-0 menu-item menu-item-has-children">
                                            <a href="#"><span class="menu-item-text">Account</span></a>
                                            <ul class="sub-menu">
                                                <li>
                                                    <a href="{{ route('account') }}"><span class="menu-item-text">My Account</span></a>
                                                </li>
                                                <li>
                                                    <a href="{{ route('login') }}"><span class="menu-item-text">Login / Register</span></a>
                                                </li>
                                                {{-- <li>
                                                    <a href="page-forgot-password.html"><span class="menu-item-text">Forgot Password</span></a>
                                                </li> --}}
                                            </ul>
                                        </li>
                                        <li class="level-0 menu-item">
                                            <a href="{{ route('contact') }}"><span class="menu-item-text">Contact</span></a>
                                        </li>
                                    </ul>
                                </nav>
                            </div>
                        </div>

                        <div class="col-xl-3 col-lg-3 col-md-12 col-sm-12 col-12 header-right">
                            <div class="header-page-link">
                                <!-- Search -->
                                {{-- <div class="search-box">
                                    <div class="search-toggle"><i class="icon-search"></i></div>
                                </div> --}}

                                <!-- Login -->
                                <div class="login-header icon">
                                    <a  href="{{ route('login') }}"><i class="icon-user"></i></a>
                                  
                                </div>
                                

                                <!-- Wishlist -->
                                {{-- <div class="wishlist-box">
                                    <a href="shop-wishlist.html"><i class="icon-heart"></i></a>
                                    <span class="count-wishlist">1</span>
                                </div> --}}
                                
                                <!-- Cart -->
                                <div class="mojuri-topcart dropdown light">
                                    <div class="dropdown mini-cart top-cart">
                                        <div class="remove-cart-shadow"></div>
                                        <a class="cart-icon" href="{{ route('cart.view') }}" >
                                            <div class="icons-cart"><i class="icon-large-paper-bag"></i><span class="cart-count">0</span></div>
                                        </a>
                                        <div class="dropdown-menu cart-popup">
                                            <div class="cart-empty-wrap" style="display: none;">
                                                <ul class="cart-list">
                                                    <li class="empty">
                                                        <span>No products in the cart.</span>
                                                        <a class="go-shop" href="{{ route('shop.index') }}">GO TO SHOP<i aria-hidden="true" class="arrow_right"></i></a>
                                                    </li>
                                                </ul>
                                            </div>
                                            <div class="cart-list-wrap">
                                                <ul class="cart-list">
                                                    @php
                                                        $cart = session()->get('cart', []);
                                                        $total = 0;
                                                        $hasItems = !empty($cart);
                                                    @endphp
                                                    @if($hasItems)
                                                        @foreach($cart as $item)
                                                            <li class="mini-cart-item">
                                                                <a href="#" class="remove" title="Remove this item" data-product-id="{{ $item['id'] }}"><i class="icon_close"></i></a>
                                                                <a href="{{ route('shop.product', $item['slug']) }}" class="product-image">
                                                                    <img width="600" height="600" src="{{ asset('storage/app/public/' . $item['image']) }}" alt="{{ $item['name'] }}">
                                                                </a>
                                                                <a href="{{ route('shop.product', $item['slug']) }}" class="product-name">{{ $item['name'] }}</a>		
                                                                <div class="quantity">Qty: {{ $item['quantity'] }}</div>
                                                                <div class="price">
                                                                    @if($item['pricing_type'] === 'fixed')
                                                                        ${{ number_format($item['price'], 2) }}
                                                                    @else
                                                                        Starting from ${{ number_format($item['price'], 2) }}
                                                                    @endif
                                                                </div>
                                                                @php
                                                                    $total += $item['price'] * $item['quantity'];
                                                                @endphp
                                                            </li>
                                                        @endforeach
                                                    @else
                                                        <li class="empty">
                                                            <span>No products in the cart.</span>
                                                            <a class="go-shop" href="{{ route('shop.index') }}">GO TO SHOP<i aria-hidden="true" class="arrow_right"></i></a>
                                                        </li>
                                                    @endif
                                                </ul>
                                                @if($hasItems)
                                                    <div class="total-cart">
                                                        <div class="title-total">Total: </div>
                                                        <div class="total-price"><span>${{ number_format($total, 2) }}</span></div>
                                                    </div>
                                                    {{-- <div class="free-ship">
                                                        <div class="title-ship">Buy <strong>$400</strong> more to enjoy <strong>FREE Shipping</strong></div>
                                                        <div class="total-percent"><div class="percent" style="width:{{ min(($total/400) * 100, 100) }}%"></div></div>
                                                    </div> --}}
                                                    <div class="buttons">
                                                        <a href="{{ route('cart.view') }}" class="button btn view-cart btn-primary">View cart</a>
                                                        <a href="#" class="button btn checkout btn-default">Check out</a>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>

@push('scripts')
<script>
$(document).ready(function() {
    // Load initial cart count
    $.get('{{ route("cart.count") }}', function(response) {
        $('.cart-count').text(response.count);
    });

    // Handle cart popup
    $('.dropdown-toggle.cart-icon').on('click', function(e) {
        e.preventDefault();
        var cartPopup = $(this).next('.cart-popup');
        cartPopup.toggleClass('show');
    });

    // Close cart popup when clicking outside
    $(document).on('click', function(e) {
        if (!$(e.target).closest('.dropdown.mini-cart').length) {
            $('.cart-popup').removeClass('show');
        }
    });

    // Remove item from cart popup
    $('.mini-cart-item .remove').on('click', function(e) {
        e.preventDefault();
        var btn = $(this);
        var productId = btn.data('product-id');
        
        $.ajax({
            url: '{{ route("cart.remove") }}',
            method: 'POST',
            data: {
                product_id: productId,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    btn.closest('li').remove();
                    $('.cart-count').text(response.cart_count);
                    
                    // Update total
                    $('.total-price span').text('$' + response.total.toFixed(2));
                    
                    // Update free shipping progress
                    var percent = Math.min((response.total/400) * 100, 100);
                    $('.percent').css('width', percent + '%');
                    
                    // Show empty cart if no items left
                    if (response.cart_count === 0) {
                        $('.cart-empty-wrap').show();
                        $('.cart-list-wrap').hide();
                    }
                }
            },
            error: function(xhr) {
                alert('Error removing item from cart');
            }
        });
    });
});
</script>
@endpush