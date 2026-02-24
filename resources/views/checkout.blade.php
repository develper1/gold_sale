@php
$sliders = \App\Models\HomeSlider::orderBy('order')->get();
@endphp
@extends('layouts.app')

@section('content')
<style>
    .is-valid {
        border-color: #28a745 !important;
    }
    .is-invalid {
        border-color: #dc3545 !important;
    }
</style>
{{-- Reusable Slider Component --}}
<x-mainslider :sliders="$sliders" height="30vh" autoplay="true" />
<x-page-header 
    title="Checkout" 
    :breadcrumbs="[
        ['label' => 'Home', 'url' => '/home'],
        ['label' => 'Shop', 'url' => '/thumbs'],
        ['label' => 'Checkout']
    ]" 
/>

<div id="content" class="site-content" role="main">
    <div class="section-padding">
        <div class="section-container p-l-r">
            <div class="shop-checkout">
                <form name="checkout" method="post" class="checkout" action="{{ route('checkout.store') }}" autocomplete="on">
                    @csrf
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <div class="row">
                        <div class="col-xl-8 col-lg-7 col-md-12 col-12">
                            <div class="customer-details">
                                <div class="billing-fields">
                                    <h3>Billing Details</h3>
                                    <div class="billing-fields-wrapper">
                                        <p class="form-row form-row-first validate-required">
                                            <label>First name <span class="required" title="required">*</span></label>
                                            <span class="input-wrapper"><input type="text" class="input-text" name="billing_first_name" value="" autocomplete="given-name"></span>
                                        </p>
                                        <p class="form-row form-row-last validate-required">
                                            <label>Last name <span class="required" title="required">*</span></label>
                                            <span class="input-wrapper"><input type="text" class="input-text" name="billing_last_name" value=""></span>
                                        </p>
                                        <p class="form-row form-row-wide">
                                            <label>Company name <span class="optional">(optional)</span></label>
                                            <span class="input-wrapper"><input type="text" class="input-text" name="billing_company" value="" autocomplete="organization"></span>
                                        </p>
                                        <p class="form-row form-row-wide validate-required">
                                            <label>Country / Region <span class="required" title="required">*</span></label>
                                            <span class="input-wrapper">
                                                <select id="billing_country" name="billing_country" class="country-select custom-select"></select>
                                            </span>
                                        </p>
                                        <p class="form-row address-field validate-required form-row-wide">
                                            <label>Street address <span class="required" title="required">*</span></label>
                                            <span class="input-wrapper">
                                                <input type="text" class="input-text" name="billing_address_1" placeholder="House number and street name" value="" autocomplete="address-line1">
                                            </span>
                                        </p>
                                        <p class="form-row address-field form-row-wide">
                                            <label>Apartment, suite, unit, etc.&nbsp;<span class="optional">(optional)</span></label>
                                            <span class="input-wrapper">
                                                <input type="text" class="input-text" name="billing_address_2" placeholder="Apartment, suite, unit, etc. (optional)" value="" autocomplete="address-line2">
                                            </span>
                                        </p>
                                        <p class="form-row address-field validate-required form-row-wide">
                                            <label for="billing_city" class="">Town / City <span class="required" title="required">*</span></label>
                                            <span class="input-wrapper">
                                                <input type="text" class="input-text" name="billing_city" value="" autocomplete="address-level2">
                                            </span>
                                        </p>
                                        <p class="form-row address-field validate-required validate-state form-row-wide">
                                            <label>State / County <span class="required" title="required">*</span></label>
                                            <span class="input-wrapper">
                                                <select id="billing_state" name="billing_state" class="state-select custom-select"></select>
                                            </span>
                                        </p>
                                        <p class="form-row address-field validate-required validate-postcode form-row-wide">
                                            <label>Postcode / ZIP <span class="required" title="required">*</span></label>
                                            <span class="input-wrapper">
                                                <input type="text" class="input-text" name="billing_postcode" value="" autocomplete="postal-code">
                                            </span>
                                        </p>
                                        <p class="form-row form-row-wide validate-required validate-phone">
                                            <label>Phone <span class="required" title="required">*</span></label>
                                            <span class="input-wrapper">
                                                <input type="tel" class="input-text" name="billing_phone" value="" autocomplete="tel">
                                            </span>
                                        </p>
                                        <p class="form-row form-row-wide validate-required validate-email">
                                            <label>Email address <span class="required" title="required">*</span></label>
                                            <span class="input-wrapper">
                                                <input type="email" class="input-text" name="billing_email" value="" autocomplete="off" autocomplete="email">
                                            </span>
                                        </p>
                                    </div>
                                </div>
                                {{-- <div class="account-fields">
                                    <p class="form-row form-row-wide">
                                        <label class="checkbox">
                                            <input class="input-checkbox" type="checkbox" name="createaccount" value="1">
                                            <span>Create an account?</span>
                                        </label>
                                    </p>
                                    <div class="create-account">
                                        <p class="form-row validate-required">
                                            <label>Create account password <span class="required" title="required">*</span></label>
                                            <span class="input-wrapper password-input">
                                                <input type="password" class="input-text" name="account_password" value="" autocomplete="off">
                                                <span class="show-password-input"></span>
                                            </span>
                                        </p>
                                        <div class="clear"></div>
                                    </div>
                                </div> --}}
                            </div>
                            <div class="shipping-fields">
                                @php
                                    // Always read fresh from DB so admin permission changes take effect immediately
                                    // without requiring the user to log out and back in.
                                    $canShipDifferent = auth()->check() && \App\Models\User::find(auth()->id())?->allow_different_shipping;
                                @endphp

                                @if($canShipDifferent)
                                    {{-- Only users approved by admin can ship to a different address --}}
                                    <p class="form-row form-row-wide ship-to-different-address">
                                        <label class="checkbox">
                                            <input class="input-checkbox" type="checkbox" name="ship_to_different_address" id="ship_to_different_address" value="1">
                                            <span>Ship to a different address?</span>
                                        </label>
                                    </p>
                                    <div class="shipping-address" id="shipping-address-fields" style="display:none;">
                                        <p class="form-row form-row-first">
                                            <label>First name <span class="required" title="required">*</span></label>
                                            <span class="input-wrapper">
                                                <input type="text" class="input-text" name="shipping_first_name" value="">
                                            </span>
                                        </p>
                                        <p class="form-row form-row-last">
                                            <label>Last name <span class="required" title="required">*</span></label>
                                            <span class="input-wrapper">
                                                <input type="text" class="input-text" name="shipping_last_name" value="">
                                            </span>
                                        </p>
                                        <p class="form-row form-row-wide">
                                            <label>Company name <span class="optional">(optional)</span></label>
                                            <span class="input-wrapper">
                                                <input type="text" class="input-text" name="shipping_company" value="">
                                            </span>
                                        </p>
                                        <p class="form-row form-row-wide address-field">
                                            <label for="shipping_country" class="">Country / Region <span class="required" title="required">*</span></label>
                                            <span class="input-wrapper">
                                                <select id="shipping_country" name="shipping_country" class="country-select custom-select"></select>
                                            </span>
                                        </p>
                                        <p class="form-row address-field form-row-wide">
                                            <label>Street address <span class="required" title="required">*</span></label>
                                            <span class="input-wrapper">
                                                <input type="text" class="input-text" name="shipping_address_1" placeholder="House number and street name" value="">
                                            </span>
                                        </p>
                                        <p class="form-row address-field form-row-wide">
                                            <label>Apartment, suite, unit, etc. <span class="optional">(optional)</span></label>
                                            <span class="input-wrapper">
                                                <input type="text" class="input-text" name="shipping_address_2" placeholder="Apartment, suite, unit, etc. (optional)" value="">
                                            </span>
                                        </p>
                                        <p class="form-row address-field form-row-wide">
                                            <label>Town / City <span class="required" title="required">*</span></label>
                                            <span class="input-wrapper"><input type="text" class="input-text" name="shipping_city" value=""></span>
                                        </p>
                                        <p class="form-row address-field validate-state form-row-wide">
                                            <label for="shipping_state" class="">State / County <span class="required" title="required">*</span></label>
                                            <span class="input-wrapper">
                                                <select id="shipping_state" name="shipping_state" class="state-select custom-select"></select>
                                            </span>
                                        </p>
                                        <p class="form-row address-field validate-postcode form-row-wide">
                                            <label>Postcode / ZIP <span class="required" title="required">*</span></label>
                                            <span class="input-wrapper">
                                                <input type="text" class="input-text" name="shipping_postcode" value="">
                                            </span>
                                        </p>
                                    </div>
                                @else
                                    {{-- Shipping address will be copied from billing on the backend --}}
                                    <p class="form-row form-row-wide" style="margin-top: 0.5rem;">
                                        <small class="text-muted" style="font-size: 14px;">
                                            <i class="fa fa-lock" style="margin-right:4px;"></i>
                                            For security purposes, your order will be shipped to your billing address.
                                            If you need to ship to a different address, please <a href="/contact">contact us</a>.
                                        </small>
                                    </p>
                                @endif
                            </div>
                            <div class="additional-fields">
                                <p class="form-row notes">
                                    <label>Order notes <span class="optional">(optional)</span></label>
                                    <span class="input-wrapper">
                                        <textarea name="order_comments" class="input-text" placeholder="Notes about your order, e.g. special notes for delivery." rows="2" cols="5"></textarea>
                                    </span>
                                </p>
                            </div>
                        </div>
                        <div class="col-xl-4 col-lg-5 col-md-12 col-12">

                            <div class="checkout-review-order">
                                <div class="checkout-review-order-table">
                                    <h3 class="review-order-title">Product</h3>
                                    <div class="cart-items">
                                        @forelse($cart as $item)
                                        <div class="cart-item">
                                            <div class="info-product">
                                                <div class="product-thumbnail">
                                                    <img width="60" height="60" src="{{ asset('storage/app/public/' . $item['image']) }}" alt="{{ $item['name'] }}">
                                                </div>
                                                <div class="product-name">
                                                    {{ $item['name'] }}
                                                    <strong class="product-quantity">QTY : {{ $item['quantity'] }}</strong>
                                                </div>
                                            </div>
                                            <div class="product-total">
                                                <span>${{ number_format($item['price'] * $item['quantity'], 2) }}</span>
                                            </div>
                                        </div>
                                        @empty
                                        <div class="cart-item">
                                            <div class="info-product">
                                                <div class="product-name">Your cart is empty</div>
                                            </div>
                                        </div>
                                        @endforelse
                                    </div>
                                    <div class="cart-subtotal">
                                        <h2>Subtotal</h2>
                                        <div class="subtotal-price">
                                            <span>${{ number_format($total, 2) }}</span>
                                        </div>
                                    </div>

                                    <div class="shipping-totals shipping">
                                        <h2>Shipping</h2>
                                        <div class="shipping-fee-amount">$0.00</div>
                                    </div>
                                    <div class="state-fee">
                                        <h2>State Fee</h2>
                                        <div class="state-fee-amount">$0.00</div>
                                    </div>
                                    <div class="service-fee">
                                        <h2>Service Fee</h2>
                                        <div class="service-fee-amount">$0.00</div>
                                    </div>
                                    <div class="credit-card-fee" style="display:none;">
                                        <h2>Credit Card Fee ({{ $creditCardPercentage }}%)</h2>
                                        <div class="credit-card-fee-amount">$0.00</div>
                                    </div>

                                    <div class="coupon-code">
                                        <label for="checkout-coupon-code">Have a coupon?</label>
                                        <div class="coupon" id="coupon-input-group">
                                            <input type="text" name="coupon_code" class="input-text" id="checkout-coupon-code" value="" placeholder="Coupon code">
                                            <button type="button" name="apply_coupon" id="apply-coupon-btn" class="coupon-button" value="Apply coupon">Apply coupon</button>
                                        </div>
                                        <div id="coupon-applied-group" style="display:none;">
                                            <span id="coupon-description" class="text-success"></span>
                                            <button type="button" id="remove-coupon-btn" class="btn btn-link text-danger p-0 ms-2">Remove</button>
                                        </div>
                                        <div id="coupon-feedback" class="mt-2 text-danger" style="display:none;"></div>
                                    </div>
                                    <div class="order-total">
                                        <h2>Total</h2>
                                        <div class="total-price">
                                            <strong>
                                                <span class="cart-total">${{ number_format($total, 2) }}</span>
                                            </strong>
                                        </div>
                                    </div>
                                </div>
                                <div id="payment" class="checkout-payment">
                                    <ul class="payment-methods methods custom-radio">

                                        <li class="payment-method">
                                            <input type="radio" class="input-radio" name="payment_method" value="cheque" id="payment_method_cheque" checked>
                                            <label for="payment_method_cheque">Check payments</label>
                                            {{-- <div class="payment-box">
                                                <p>Please send a check to Store Name, Store Street, Store Town, Store State / County, Store Postcode.</p>
                                            </div> --}}
                                        </li>
                                        {{-- <li class="payment-method">
                                            <input type="radio" class="input-radio" name="payment_method" value="cod" id="payment_method_cod">
                                            <label for="payment_method_cod">Cash on delivery</label>
                                             <div class="payment-box">
                                                <p>Pay with cash upon delivery.</p>
                                            </div>
                                        </li> 
                                        <li class="payment-method">
                                            <input type="radio" class="input-radio" name="payment_method" value="credit_card" id="payment_method_credit_card">
                                            <label for="payment_method_credit_card">Credit Card</label>
                                        </li>--}}
                                        <li class="payment-method">
                                            <input type="radio" class="input-radio" name="payment_method" value="paypal" id="payment_method_paypal">
                                            <label for="payment_method_paypal">PayPal / Credit Card</label>
                                            <div class="payment-box">
                                                <p>To pay via credit card, please use the paypal option.</p>
                                            </div>
                                            {{-- <div class="payment-box">
                                                <p>Pay via PayPal; you can pay with your credit card if you don’t have a PayPal account.</p>
                                            </div> --}}
                                        </li>
                                         <li class="payment-method">
                                             <input type="radio" class="input-radio" name="payment_method" value="bank_wire" id="payment_method_bank_wire">
                                             <label for="payment_method_bank_wire">Bank Wire</label>
                                         </li>
                                         <li class="payment-method">
                                             <input type="radio" class="input-radio" name="payment_method" value="ach" id="payment_method_ach">
                                             <label for="payment_method_ach">ACH / Echeck</label>
                                         </li>
                                         <li class="payment-method">
                                            <input type="radio" class="input-radio" name="payment_method" value="zelle" id="payment_method_zelle">
                                            <label for="payment_method_zelle">Zelle</label>
                                         </li>
                                    </ul>
                                    <div id="credit-card-fields" style="display:none; margin-top: 20px;">
                                       <div class="payment-form px-3 py-3 row m-0">
                                            <div class="col-12">
                                                <div class="form-group  ">
                                                    <input type="tel" class="form-control" name="cc_no" id="cc_no"
                                                        maxlength="16"
                                                        onkeyup="javascript:this.value=this.value.replace(/[^0-9]/g,'');"
                                                        value="" placeholder="Card No">
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="form-group  ">
                                                    <select class="form-control" name="cc_month" id="cc_month" maxlength="2"
                                                        placeholder="Month(MM)">
                                                        <option value="" selected="selected">Month(MM)</option>
                                                        <?php for ($month = 01; $month < 13; $month = $month + 1) { ?>
                                                        <option value=<?= $month ?>><?= sprintf("%02d", $month); ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="form-group  ">
                                                    <select class="form-control" name="cc_year" id="cc_year" maxlength="4"
                                                        placeholder="Year(YYYY)">
                                                        <option value="" selected="selected">Year(YYYY)</option>
                                                        <?php $c_year = date('Y');
                                                        for ($year = $c_year; $year < ($c_year + 11); $year = $year + 1) { ?>
                                                        <option value=<?= $year ?>> <?= $year ?> </option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="form-group mb-0  ">
                                                    <input type="tel" class="form-control" name="CVV" id="cvv" maxlength="3"
                                                        onkeyup="javascript:this.value=this.value.replace(/[^0-9]/g,'');"
                                                        placeholder="CVV" value="">
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                    <div class="form-row place-order">
                                        <div class="terms-and-conditions-wrapper">
                                            <div class="privacy-policy-text"></div>
                                        </div>
                                        <div id="manual-payment-message" class="manual-payment-message">
                                            After placing order below, please call our office within 2 business days with your order number to make payment and complete your order.
                                        </div>
                                        <div id="checkout-errors" style="display:none;"></div>
                                        <button type="submit" name="checkout_place_order" id="place-order-btn" class="button alt">Place Order</button>
                                        <div id="checkout-loading" style="display:none;margin-top:10px;"><span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Processing...</div>
                                        <div id="paypal-button-container" class="mt-3" style="display:none;width: 100%;"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="shipping_fee" id="shipping_fee" value="0">
                    <input type="hidden" name="state_fee" id="state_fee" value="0">
                    <input type="hidden" name="service_fee" id="service_fee" value="0">
                </form>
            </div>
        </div>
    </div>
</div><!-- #content -->
@endsection

@push('scripts')

@if(env('PAYPAL_SANDBOX'))
    <script src="https://www.paypal.com/sdk/js?client-id={{ env('PAYPAL_SANDBOX_CLIENT_ID') }}&disable-funding=credit,card,paylater"></script>
@else
    <script src="https://www.paypal.com/sdk/js?client-id={{ env('PAYPAL_LIVE_CLIENT_ID') }}&disable-funding=credit,card,paylater"></script>
@endif
<script src="{{ asset('assets/vendor/libs/cleavejs/cleave.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.payment/3.0.0/jquery.payment.min.js"></script>
<script>
    $(document).ready(function() {
        // Format card number
        $('#cc_no').payment('formatCardNumber');

        // Format CVV
        $('#cvv').payment('formatCardCVC');

        // Detect card type and show icon
        $('#cc_no').on('input', function() {
            var cardNumber = $(this).val().replace(/\s+/g, '');
            var paymentCardType  = $.payment.cardType(cardNumber);
            var $cardBrand = $('.card-brand-icons');

            // Validate card number length
            if ($.payment.validateCardNumber(cardNumber)) {
                $(this).removeClass('is-invalid').addClass('is-valid');
            } else {
                $(this).removeClass('is-valid').addClass('is-invalid');
            }
        });

        // Validate expiry date
        $('#cc_month, #cc_year').on('change', function() {
            var month = $('#cc_month').val();
            var year = $('#cc_year').val();

            if (month && year) {
                if ($.payment.cardExpiryVal(month, year)) {
                    $('#cc_month, #cc_year').removeClass('is-invalid').addClass('is-valid');
                } else {
                    $('#cc_month, #cc_year').removeClass('is-valid').addClass('is-invalid');
                }
            }
        });

        // Validate CVV
        $('#cvv').on('input', function() {
            var cvv = $(this).val();
            var cardNumber = $('#cc_no').val().replace(/\s+/g, '');
            var cardType = $.payment.cardType(cardNumber);

            if ($.payment.validateCardCVC(cvv, cardType)) {
                $(this).removeClass('is-invalid').addClass('is-valid');
            } else {
                $(this).removeClass('is-valid').addClass('is-invalid');
            }
        });
    });

    function popop_cvv() {
        // Your existing CVV popup function
        alert("The CVV is the 3-digit code on the back of your card (4 digits for American Express).");
    }
    </script>
<script>
$(document).ready(function() {
    let countriesData = [];
    let shippingFee = 0;
    let stateFee = 0;
    let serviceFee = 0;
    let creditCardPercentage = {{ $creditCardPercentage ?? 0 }};
    let creditCardFee = 0;

    function updateShippingFee() {
        // Check if cart has any non-physical items
        var hasNonPhysicalOnly = true;
        @if(isset($cart))
            @foreach($cart as $item)
                @if(!($item['is_non_physical'] ?? false))
                    hasNonPhysicalOnly = false;
                    @break
                @endif
            @endforeach
        @endif
        
        // If all items are non-physical, shipping is 0
        if (hasNonPhysicalOnly) {
            shippingFee = 0;
            $('.shipping-fee-amount').text('$0.00');
            $('#shipping_fee').val(0);
            updateCreditCardFee();
            updateStateFee();
            return;
        }
        
        // Otherwise, calculate shipping normally
        var baseTotal = parseFloat($('.subtotal-price span').text().replace('$','').replace(/,/g, ''));
        $.ajax({
            url: 'shipping-fee/' + baseTotal,
            method: 'GET',
            success: function(response) {
                shippingFee = response.amount ? parseFloat(response.amount) : 0;
                // Round to 2 decimal places consistently
                shippingFee = Math.round(shippingFee * 100) / 100;
                $('.shipping-fee-amount').text(shippingFee > 0 ? '$' + shippingFee.toFixed(2) : '$0.00');
                $('#shipping_fee').val(shippingFee);
                updateCreditCardFee();
                // Update state fee when shipping fee changes (in case subtotal affects state fee)
                updateStateFee();
            },
            error: function() {
                shippingFee = 0;
                $('.shipping-fee-amount').text('$0.00');
                $('#shipping_fee').val(0);
                updateCreditCardFee();
                updateStateFee();
            }
        });
    }

    function updateServiceFee() {
        var baseTotal = parseFloat($('.subtotal-price span').text().replace('$','').replace(/,/g, ''));
        $.ajax({
            url: 'service-fee/' + baseTotal,
            method: 'GET',
            success: function(response) {
                serviceFee = response.amount ? parseFloat(response.amount) : 0;
                // Round to 2 decimal places consistently
                serviceFee = Math.round(serviceFee * 100) / 100;
                $('.service-fee-amount').text(serviceFee > 0 ? '$' + serviceFee.toFixed(2) : '$0.00');
                $('#service_fee').val(serviceFee);
                updateCreditCardFee();
                // Update state fee when service fee changes (in case subtotal affects state fee)
                updateStateFee();
            },
            error: function() {
                serviceFee = 0;
                $('.service-fee-amount').text('$0.00');
                $('#service_fee').val(0);
                updateCreditCardFee();
                updateStateFee();
            }
        });
    }

    function updateCreditCardFee() {
        var selected = $('input[name="payment_method"]:checked').val();

        // Calculate credit card fee only for gold/silver/platinum products subtotal + fees
        var goldSilverSubtotal = 0;
        @if(isset($cart))
            @foreach($cart as $item)
                @if(isset($item['product_type']) && in_array($item['product_type'], ['gold', 'silver', 'platinum']))
                    goldSilverSubtotal += parseFloat({{ ($item['price'] ?? 0) * ($item['quantity'] ?? 1) }});
                @endif
            @endforeach
        @endif

        // Only calculate credit card fee if payment method is credit_card or paypal
        // Apply fee to gold/silver/platinum subtotal + all fees (shipping, state, service)
        if (selected === 'credit_card' || selected === 'paypal') {
            var totalBeforeCreditCardFee = goldSilverSubtotal + shippingFee + stateFee + serviceFee;
            creditCardFee = (totalBeforeCreditCardFee) * (creditCardPercentage / 100);
            // Round to 2 decimal places consistently
            creditCardFee = Math.round(creditCardFee * 100) / 100;
        } else {
            creditCardFee = 0;
        }

        $('.credit-card-fee-amount').text(creditCardFee > 0 ? '$' + creditCardFee.toFixed(2) : '$0.00');
        updateOrderTotal();
    }

    function updateOrderTotal() {
        var baseTotal = parseFloat($('.subtotal-price span').text().replace('$','').replace(/,/g, ''));
        var total = baseTotal + shippingFee + stateFee + serviceFee + creditCardFee;
        // Round to 2 decimal places consistently
        total = Math.round(total * 100) / 100;
        $('.cart-total').text('$' + total.toFixed(2));
    }

    // --- Ship to different address toggle (only rendered for approved users) ---
    $('#ship_to_different_address').on('change', function() {
        if ($(this).is(':checked')) {
            $('#shipping-address-fields').slideDown(200);
        } else {
            $('#shipping-address-fields').slideUp(200);
        }
    });

    // Load countries and states from JSON
    $.getJSON('public/countries.json', function(data) {
        countriesData = data;
        let countryOptions = '<option value="">Select a country / region…</option>';
        data.forEach(function(country) {
            countryOptions += `<option value="${country.iso2}">${country.name}</option>`;
        });
        // Only populate shipping_country if the element exists (approved users only)
        if ($('#shipping_country').length) {
            $('#billing_country, #shipping_country').html(countryOptions);
        } else {
            $('#billing_country').html(countryOptions);
        }
    });

    // Billing country change
    $('#billing_country').on('change', function() {
        let selectedCountry = $(this).val();
        let states = [];
        countriesData.forEach(function(country) {
            if (country.iso2 === selectedCountry) {
                states = country.states;
            }
        });
        let stateOptions = '<option value="">Select a state / county…</option>';
        states.forEach(function(state) {
            stateOptions += `<option value="${state.state_code}">${state.name}</option>`;
        });
        $('#billing_state').html(stateOptions);
        // Reset state fee and total
        // updateStateFee();
    });

    // Billing state change
    $('#billing_state').on('change', function() {
        updateStateFee();
    });

    function updateStateFee() {
        var stateCode = $('#billing_state').val();
        if (!stateCode) {
            stateFee = 0;
            $('.state-fee-amount').text('$0.00');
            $('#state_fee').val(0);
            updateCreditCardFee();
            return;
        }

        var baseTotal = parseFloat($('.subtotal-price span').text().replace('$','').replace(/,/g, ''));

        $.ajax({
            url: '{{ url('/state-fee') }}/' + stateCode,
            method: 'GET',
            data: { subtotal: baseTotal },
            success: function(response) {
                stateFee = response.amount ? parseFloat(response.amount) : 0;
                // Round to 2 decimal places consistently
                stateFee = Math.round(stateFee * 100) / 100;

                // Update display with appropriate label
                if (response.fee_type === 'percentage' && response.percentage) {
                    $('.state-fee h2').text('State Fee (' + response.percentage + '%)');
                } else {
                    $('.state-fee h2').text('State Fee');
                }

                $('.state-fee-amount').text(stateFee > 0 ? '$' + stateFee.toFixed(2) : '$0.00');
                $('#state_fee').val(stateFee);
                updateCreditCardFee();
            },
            error: function() {
                stateFee = 0;
                $('.state-fee h2').text('State Fee');
                $('.state-fee-amount').text('$0.00');
                $('#state_fee').val(0);
                updateCreditCardFee();
            }
        });
    }

    // Call updateCreditCardFee after other fee updates
    updateShippingFee();
    updateServiceFee();
    updateCreditCardFee();

    // --- Credit Card fields toggle and validation ---
    function toggleCreditCardFields() {
        var selected = $('input[name="payment_method"]:checked').val();
        if (selected === 'credit_card') {
            $('#credit-card-fields').show();
        } else {
            $('#credit-card-fields').hide();
        }
    }

    function handleCreditCardFeeDisplay() {
        var selected = $('input[name="payment_method"]:checked').val();
        if (selected === 'credit_card' || selected === 'paypal') {
            $('.credit-card-fee').show();
        } else {
            $('.credit-card-fee').hide();
        }
        // updateCreditCardFee will handle the calculation based on payment method
        updateCreditCardFee();
    }

    // Initial setup
    toggleCreditCardFields();
    handleCreditCardFeeDisplay();

    // Single consolidated payment method change handler
    $('input[name="payment_method"]').on('change', function() {
        toggleCreditCardFields();
        togglePaymentButtons();
        handleCreditCardFeeDisplay();
    });


    // Extend validation for credit card fields on submit
    function isCheckoutFormValid() {
        let valid = true;
        let requiredFields = [
            'input[name="billing_first_name"]',
            'input[name="billing_last_name"]',
            'select[name="billing_country"]',
            'input[name="billing_address_1"]',
            'input[name="billing_city"]',
            'select[name="billing_state"]',
            'input[name="billing_postcode"]',
            'input[name="billing_phone"]',
            'input[name="billing_email"]'
        ];
        requiredFields.forEach(function(selector) {
            let $field = $(selector);
            if ($field.length && !$field.val()) {
                $field.addClass('is-invalid');
                valid = false;
            } else {
                $field.removeClass('is-invalid');
            }
        });

        return valid;
    }

    // --- Payment method toggle logic ---
    function togglePaymentButtons() {
        var selected = $('input[name="payment_method"]:checked').val();
        if (selected === 'paypal') {
            $('#paypal-button-container').show();
            $('#place-order-btn').hide();
            // Render PayPal button if not already rendered
            if (!$('#paypal-button-container').data('paypal-rendered') && typeof paypal !== 'undefined') {
                paypal.Buttons({
                    createOrder: function(data, actions) {
                        if (!isCheckoutFormValid()) {
                            alert('Please fill in all required fields.');
                            return actions.reject();
                        }
                        let total = $('.cart-total').text().replace('$', '').replace(/,/g, '');
                        return actions.order.create({
                            purchase_units: [{
                                amount: { value: total }
                            }]
                        });
                    },
                    onApprove: function(data, actions) {
                        return actions.order.capture().then(function(details) {
                            var formData = $('form.checkout').serializeArray();
                            formData.push({name: 'paypal_order_id', value: data.orderID});
                            formData.push({name: 'paypal_details', value: JSON.stringify(details)});
                            $.ajax({
                                url: $('form.checkout').attr('action'),
                                method: 'POST',
                                data: formData,
                                headers: { 'X-CSRF-TOKEN': $('input[name="_token"]').val() },
                                success: function(response) {
                                    if (response.redirect_url) {
                                        window.location.href = response.redirect_url;
                                    } else {
                                        window.location.href = '/order-confirmation';
                                    }
                                },
                                error: function(xhr) {
                                    alert('There was an error processing your order. Please contact support.');
                                }
                            });
                        });
                    }
                }).render('#paypal-button-container');
                $('#paypal-button-container').data('paypal-rendered', true);
            }
        } else {
            $('#paypal-button-container').hide();
            $('#place-order-btn').show();
        }
    }
    // Initial toggle
    togglePaymentButtons();
    // Listen for payment method change
    $('input[name="payment_method"]').on('change', togglePaymentButtons);
    // Place order button handler for non-PayPal
    $('#place-order-btn').on('click', function(e) {
        if (!isCheckoutFormValid()) {
            e.preventDefault();
            alert('Please fill in all required fields.');
            return false;
        }
        // Allow normal form submission
    });

    // --- Coupon application logic ---
    var originalShippingFee = null;
    var originalServiceFee = null;
    $('#apply-coupon-btn').on('click', function() {
        $('#coupon-feedback').text('');
        $('#coupon-feedback').hide();
        var code = $('#checkout-coupon-code').val().trim();
        if (!code) {
            $('#coupon-feedback').text('Please enter a coupon code.').show();
            return;
        }
        $.ajax({
            url: 'validate-coupon',
            method: 'POST',
            data: {
                coupon_code: code,
                _token: $('input[name="_token"]').val()
            },
            success: function(response) {
                if (response.valid) {
                    // $('#coupon-feedback').removeClass('text-danger').addClass('text-success').text('Coupon applied!').show();
                    // Save original fees if not already saved
                    if (originalShippingFee === null) originalShippingFee = shippingFee;
                    if (originalServiceFee === null) originalServiceFee = serviceFee;
                    // If free shipping, set shipping fee to 0
                    if (response.free_shipping) {
                        shippingFee = 0;
                        $('.shipping-fee-amount').text('$0.00');
                        $('#shipping_fee').val(0);
                    }
                    // If free service fee, set service fee to 0
                    if (response.free_service_fee) {
                        serviceFee = 0;
                        $('.service-fee-amount').text('$0.00');
                        $('#service_fee').val(0);
                    }
                    updateOrderTotal();
                    // Hide input, show description and remove button
                    $('#coupon-input-group').hide();
                    $('#coupon-applied-group').show();
                    $('#coupon-description').text('Coupon applied: ' + (response.description ? response.description : code));
                } else {
                    $('#coupon-feedback').removeClass('text-success').addClass('text-danger').text(response.message || 'Invalid or expired coupon.').show();
                }
            },
            error: function(xhr) {
                $('#coupon-feedback').removeClass('text-success').addClass('text-danger').text('Error validating coupon.').show();
            }
        });
    });
    // Remove coupon logic
    $('#remove-coupon-btn').on('click', function() {
        // Revert fees
        if (originalShippingFee !== null) {
            shippingFee = originalShippingFee;
            $('.shipping-fee-amount').text('$' + shippingFee.toFixed(2));
            $('#shipping_fee').val(shippingFee);
        }
        if (originalServiceFee !== null) {
            serviceFee = originalServiceFee;
            $('.service-fee-amount').text('$' + serviceFee.toFixed(2));
            $('#service_fee').val(serviceFee);
        }
        updateOrderTotal();
        // Remove coupon from session via AJAX (optional, for backend consistency)
        $.post('validate-coupon', { coupon_code: '', _token: $('input[name="_token"]').val() });
        // Show input, hide description and remove button
        $('#coupon-input-group').show();
        $('#coupon-applied-group').hide();
        $('#coupon-feedback').hide();
        $('#checkout-coupon-code').val('');
    });

    // Hide the manual place order button if it exists (for safety)

    // --- AJAX form submission for checkout ---
    $('form.checkout').on('submit', function(e) {
        var selected = $('input[name="payment_method"]:checked').val();
        if (selected === 'paypal') {
            // Let PayPal JS handle it
            return true;
        }
        e.preventDefault();
        $('#checkout-errors').hide().empty();
        if (!isCheckoutFormValid()) {
            $('#checkout-errors').html('<div class="alert alert-danger">Please fill in all required fields correctly.</div>').show();
            return false;
        }
        var form = $(this);
        var formData = form.serialize();
        // Show spinner and disable button
        $('#checkout-loading').show();
        $('#place-order-btn').prop('disabled', true);
        $.ajax({
            url: form.attr('action'),
            method: 'POST',
            data: formData,
            headers: { 'X-CSRF-TOKEN': $('input[name="_token"]').val() },
            success: function(response) {
                $('#checkout-loading').hide();
                $('#place-order-btn').prop('disabled', false);
                if (response.redirect_url) {
                    window.location.href = response.redirect_url;
                }
            },
            error: function(xhr, status, error) {
                // console.log('Status:', status); // e.g., "error"
                // console.log('HTTP Status:', xhr.status); // e.g., 500
                // console.log('Error Thrown:', error); // e.g., "Internal Server Error"
                // console.log('Response Text:', xhr.responseText); // full HTML / JSON from Laravel
                $('#checkout-loading').hide();
                $('#place-order-btn').prop('disabled', false);
                var msg = 'An error occurred. Please try again.';

                if (xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.errors) {
                    var errors = xhr.responseJSON.errors;
                    var html = '<div class="alert alert-danger text-danger"><ul>';
                    $.each(errors, function(key, val) {
                        if (Array.isArray(val)) {
                            val.forEach(function(v) { html += '<li>' + v + '</li>'; });
                        } else {
                            html += '<li>' + val + '</li>';
                        }
                    });
                    html += '</ul></div>';
                    $('#checkout-errors').html(html).show();
                } else if (xhr.responseJSON && xhr.responseJSON.message) {
                    $('#checkout-errors').html('<div class="alert alert-danger">' + xhr.responseJSON.message + '</div>').show();
                } else {
                    $('#checkout-errors').html('<div class="alert alert-danger">' + msg + '</div>').show();
                }
            }
        });
    });
    // On form submit, ensure credit_card_fee is only submitted if payment method is credit_card or paypal
    $('form.checkout').on('submit', function(e) {
        var selected = $('input[name="payment_method"]:checked').val();
        if (selected !== 'credit_card' && selected !== 'paypal') {
            // Remove credit_card_fee input if it exists
            if ($('input[name="credit_card_fee"]').length) {
                $('input[name="credit_card_fee"]').val(0);
            } else {
                $(this).append('<input type="hidden" name="credit_card_fee" value="0">');
            }
        } else {
            // Set the correct value
            if ($('input[name="credit_card_fee"]').length) {
                $('input[name="credit_card_fee"]').val(creditCardFee);
            } else {
                $(this).append('<input type="hidden" name="credit_card_fee" value="' + creditCardFee + '">');
            }
        }
    });
});
</script>

<style>
.is-invalid {
    /* border: 1px solid red !important; */
}
.spinner-border { vertical-align: middle; }
.manual-payment-message {
    background-color: #fff3cd; /* light yellow */
    border: 1px solid #ffeeba;
    color: #856404;
    padding: 12px 15px;
    margin-bottom: 10px;
    border-radius: 4px;
    font-size: 14px;
}
</style>
<script>
    $(document).ready(function(){
        function toggleManualPaymentMessage() {
            var selected = $('input[name="payment_method"]:checked').val();
            var manualMethods = ['bank_wire', 'ach', 'zelle', 'cheque'];

            if (manualMethods.includes(selected)) {
                $('#manual-payment-message').show();
            } else {
                $('#manual-payment-message').hide();
            }
        }
        toggleManualPaymentMessage();
        // Run whenever user selects a payment method
        $('input[name="payment_method"]').change(function(){
            toggleManualPaymentMessage();
        });
    });
</script>
@endpush
