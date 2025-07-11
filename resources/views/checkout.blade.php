@extends('layouts.app')

@section('content')

<div id="title" class="page-title">
    <div class="section-container">
        <div class="content-title-heading">
            <h1 class="text-title-heading">
                Checkout
            </h1>
        </div>
        <div class="breadcrumbs">
            <a href="index.html">Home</a><span class="delimiter"></span><a href="shop-grid-left.html">Shop</a><span class="delimiter"></span>Checkout
        </div>
    </div>
</div>

<div id="content" class="site-content" role="main">
    <div class="section-padding">
        <div class="section-container p-l-r">
            <div class="shop-checkout">
                <form name="checkout" method="post" class="checkout" action="{{ route('checkout.store') }}" autocomplete="off">
                    @csrf
                    <div class="row">
                        <div class="col-xl-8 col-lg-7 col-md-12 col-12">
                            <div class="customer-details">
                                <div class="billing-fields">
                                    <h3>Billing Details</h3>
                                    <div class="billing-fields-wrapper">
                                        <p class="form-row form-row-first validate-required">
                                            <label>First name <span class="required" title="required">*</span></label>
                                            <span class="input-wrapper"><input type="text" class="input-text" name="billing_first_name" value=""></span>
                                        </p>
                                        <p class="form-row form-row-last validate-required">
                                            <label>Last name <span class="required" title="required">*</span></label>
                                            <span class="input-wrapper"><input type="text" class="input-text" name="billing_last_name" value=""></span>
                                        </p>
                                        <p class="form-row form-row-wide">
                                            <label>Company name <span class="optional">(optional)</span></label>
                                            <span class="input-wrapper"><input type="text" class="input-text" name="billing_company" value=""></span>
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
                                                <input type="text" class="input-text" name="billing_address_1" placeholder="House number and street name" value="">
                                            </span>
                                        </p>
                                        <p class="form-row address-field form-row-wide">
                                            <label>Apartment, suite, unit, etc.&nbsp;<span class="optional">(optional)</span></label>
                                            <span class="input-wrapper">
                                                <input type="text" class="input-text" name="billing_address_2" placeholder="Apartment, suite, unit, etc. (optional)" value="">
                                            </span>
                                        </p>
                                        <p class="form-row address-field validate-required form-row-wide">
                                            <label for="billing_city" class="">Town / City <span class="required" title="required">*</span></label>
                                            <span class="input-wrapper">
                                                <input type="text" class="input-text" name="billing_city" value="">
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
                                                <input type="text" class="input-text" name="billing_postcode" value="">
                                            </span>
                                        </p>
                                        <p class="form-row form-row-wide validate-required validate-phone">
                                            <label>Phone <span class="required" title="required">*</span></label>
                                            <span class="input-wrapper">
                                                <input type="tel" class="input-text" name="billing_phone" value="">
                                            </span>
                                        </p>
                                        <p class="form-row form-row-wide validate-required validate-email">
                                            <label>Email address <span class="required" title="required">*</span></label>
                                            <span class="input-wrapper">
                                                <input type="email" class="input-text" name="billing_email" value="" autocomplete="off">
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
                                <p class="form-row form-row-wide ship-to-different-address">
                                    <label class="checkbox">
                                        <input class="input-checkbox" type="checkbox" name="ship_to_different_address" value="1"> 
                                        <span>Ship to a different address?</span>
                                    </label>
                                </p>
                                <div class="shipping-address">
                                    <p class="form-row form-row-first validate-required">
                                        <label>First name <span class="required" title="required">*</span></label>
                                        <span class="input-wrapper">
                                            <input type="text" class="input-text" name="shipping_first_name" value="">
                                        </span>
                                    </p>
                                    <p class="form-row form-row-last validate-required">
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
                                    <p class="form-row form-row-wide address-field validate-required">
                                        <label for="shipping_country" class="">Country / Region <span class="required" title="required">*</span></label>
                                        <span class="input-wrapper">
                                            <select id="shipping_country" name="shipping_country" class="country-select custom-select"></select>
                                        </span>
                                    </p>
                                    <p class="form-row address-field validate-required form-row-wide">
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
                                    <p class="form-row address-field validate-required form-row-wide">
                                        <label>Town / City <span class="required" title="required">*</span></label>
                                        <span class="input-wrapper"><input type="text" class="input-text" name="shipping_city" value=""></span>
                                    </p>
                                    <p class="form-row address-field validate-required validate-state form-row-wide">
                                        <label for="shipping_state" class="">State / County <span class="required" title="required">*</span></label>
                                        <span class="input-wrapper">
                                            <select id="shipping_state" name="shipping_state" class="state-select custom-select"></select>
                                        </span>
                                    </p>
                                    <p class="form-row address-field validate-required validate-postcode form-row-wide">
                                        <label>Postcode / ZIP <span class="required" title="required">*</span></label>
                                        <span class="input-wrapper">
                                            <input type="text" class="input-text" name="shipping_postcode" value="">
                                        </span>
                                    </p>
                                </div>
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
                                    <div class="credit-card-fee">
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
                                        <div id="paypal-button-container"></div>
                                    </ul>
                                    <div class="form-row place-order">
                                        <div class="terms-and-conditions-wrapper">
                                            <div class="privacy-policy-text"></div>
                                        </div>
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
<script src="https://www.paypal.com/sdk/js?client-id=AS1q2MeR_lXKqcjYgcZrVY1wRN4n1CLbgOz1p0dpaIFu-LsW2slgQtuiqq1anG2Yi-2eoc2ByMjcjf7U&disable-funding=credit,card,paylater"></script>
<script>
$(document).ready(function() {
    let countriesData = [];
    let shippingFee = 0;
    let stateFee = 0;
    let serviceFee = 0;
    let creditCardPercentage = {{ $creditCardPercentage ?? 0 }};
    let creditCardFee = 0;

    function updateShippingFee() {
        var baseTotal = parseFloat($('.subtotal-price span').text().replace('$','').replace(/,/g, ''));
        $.ajax({
            url: 'shipping-fee/' + baseTotal,
            method: 'GET',
            success: function(response) {
                shippingFee = response.amount ? parseFloat(response.amount) : 0;
                $('.shipping-fee-amount').text(shippingFee > 0 ? '$' + shippingFee.toFixed(2) : '$0.00');
                $('#shipping_fee').val(shippingFee);
                updateOrderTotal();
            },
            error: function() {
                shippingFee = 0;
                $('.shipping-fee-amount').text('$0.00');
                $('#shipping_fee').val(0);
                updateOrderTotal();
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
                $('.service-fee-amount').text(serviceFee > 0 ? '$' + serviceFee.toFixed(2) : '$0.00');
                $('#service_fee').val(serviceFee);
                updateOrderTotal();
            },
            error: function() {
                serviceFee = 0;
                $('.service-fee-amount').text('$0.00');
                $('#service_fee').val(0);
                updateOrderTotal();
            }
        });
    }

    function updateCreditCardFee() {
        var baseTotal = parseFloat($('.subtotal-price span').text().replace('$','').replace(/,/g, ''));
        creditCardFee = (baseTotal) * (creditCardPercentage / 100);
        $('.credit-card-fee-amount').text(creditCardFee > 0 ? '$' + creditCardFee.toFixed(2) : '$0.00');
        updateOrderTotal();
    }

    function updateOrderTotal() {
        var baseTotal = parseFloat($('.subtotal-price span').text().replace('$','').replace(/,/g, ''));
        var total = baseTotal + shippingFee + stateFee + serviceFee + creditCardFee;
        $('.cart-total').text('$' + total.toFixed(2));
    }

    // Load countries and states from JSON
    $.getJSON('public/countries.json', function(data) {
        countriesData = data;
        let countryOptions = '<option value="">Select a country / region…</option>';
        data.forEach(function(country) {
            countryOptions += `<option value="${country.iso2}">${country.name}</option>`;
        });
        $('#billing_country, #shipping_country').html(countryOptions);
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
        var stateCode = $(this).val();
        $.ajax({
            url: '{{ url('/state-fee') }}/' + stateCode,
            method: 'GET',
            success: function(response) {
                stateFee = response.amount ? parseFloat(response.amount) : 0;
                $('.state-fee-amount').text(stateFee > 0 ? '$' + stateFee.toFixed(2) : '$0.00');
                $('#state_fee').val(stateFee);
                updateOrderTotal();
            },
            error: function() {
                stateFee = 0;
                $('.state-fee-amount').text('$0.00');
                $('#state_fee').val(0);
                updateOrderTotal();
            }
        });
    });

    // Call updateCreditCardFee after other fee updates
    updateShippingFee();
    updateServiceFee();
    updateCreditCardFee();

    // --- Validation function ---
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

    // Render PayPal button only
    if ($('#paypal-button-container').length && typeof paypal !== 'undefined') {
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
                    // Gather all form data
                    var formData = $('form.checkout').serializeArray();
                    // Add PayPal details
                    formData.push({name: 'paypal_order_id', value: data.orderID});
                    formData.push({name: 'paypal_details', value: JSON.stringify(details)});
                    // Send to backend via AJAX
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
    }

    // --- Coupon application logic ---
    var originalShippingFee = null;
    var originalServiceFee = null;
    $('#apply-coupon-btn').on('click', function() {
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
    $('button[name="checkout_place_order"]').hide();
});
</script>

<style>
.is-invalid {
    /* border: 1px solid red !important; */
}
</style>
@endpush