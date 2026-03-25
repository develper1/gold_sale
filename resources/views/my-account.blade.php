@php
$sliders = \App\Models\HomeSlider::orderBy('order')->get();
@endphp

@extends('layouts.app')

@section('content')


{{-- Reusable Slider Component --}}
<x-mainslider :sliders="$sliders" height="30vh" autoplay="true" />

<x-page-header 
    title="My Account" 
    :breadcrumbs="[
        ['label' => 'Home', 'url' => '/'],
        ['label' => 'My Account']
    ]" 
/>

<div id="content" class="site-content" role="main">
    <div class="section-padding">
        <div class="section-container p-l-r">
            <div class="page-my-account">
                <div class="my-account-wrap clearfix">
                    <nav class="my-account-navigation">
                        <ul class="nav nav-tabs">
                            <li class="nav-item">
                                <a class="nav-link active" data-toggle="tab" href="#dashboard" role="tab">Dashboard</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#orders" role="tab">Orders</a>
                            </li>
                            {{-- <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#addresses" role="tab">Addresses</a>
                            </li> --}}
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#account-details" role="tab">Account details</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
                            </li>
                        </ul>
                    </nav>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                    <div class="my-account-content tab-content">
                        <div class="tab-pane fade show active" id="dashboard" role="tabpanel">
                            <div class="my-account-dashboard">
                                <p>
                                    Hello <strong>{{ $user ? $user->name : '' }}</strong> (not <strong>{{ $user ? $user->name : '' }}</strong>? <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Log out</a>)
                                </p>
                                <p>
                                    From your account dashboard you can view your <strong>recent orders</strong>, and <strong>edit your password and account details</strong>.
                                </p>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="orders" role="tabpanel">
                            <div class="my-account-orders">
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Order</th>
                                                <th>Date</th>
                                                <th>Status</th>
                                                <th>Total</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($orders as $order)
                                            <tr>
                                                <td>#{{ $order->id }}</td>
                                                <td>{{ $order->created_at->format('F d, Y') }}</td>
                                                <td>{{ ucfirst($order->status) }}</td>
                                                <td>${{ number_format($order->total, 2) }}</td>
                                                <td><a href="javascript:void(0);" class="btn-small d-block view-order-detail" data-order-id="{{ $order->id }}">View</a></td>
                                            </tr>
                                            <tr class="order-detail-row" id="order-detail-row-{{ $order->id }}" style="display:none;background: #f5f5f5;"><td colspan="5"></td></tr>
                                            @empty
                                            <tr>
                                                <td colspan="5">No orders found.</td>
                                            </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="addresses" role="tabpanel">
                            <div class="my-account-addresses">
                                <p>
                                    The following addresses will be used on the checkout page by default.
                                </p>
                                <div class="addresses">
                                    <div class="addresses-col">
                                        <header class="col-title">
                                            <h3>Billing address</h3>
                                            <a href="#" class="edit">Edit</a>
                                        </header>
                                        <address>
                                            3522 Interstate<br>
                                            75 Business Spur,<br>
                                            Sault Ste.<br>
                                            Marie, MI 49783
                                        </address>
                                    </div>
                                    <div class="addresses-col">
                                        <header class="col-title">
                                            <h3>Shipping address</h3>
                                            <a href="#" class="edit">Edit</a>
                                        </header>
                                        <address>
                                            4299 Express Lane<br>
                                            Sarasota,<br>
                                            FL 34249 USA <br>
                                            Phone: 1.941.227.4444
                                        </address>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="account-details" role="tabpanel">
                            <div class="my-account-account-details">
                                <form class="edit-account" id="edit-account-form" action="{{ route('account.update') }}" method="post">
                                    @csrf
                                    <p class="form-row">
                                        <label for="account_first_name">Name <span class="required">*</span></label>
                                        <input type="text" class="input-text" name="account_first_name" value="{{ $user ? $user->name : '' }}">
                                    </p>
                                   
                                    <div class="clear"></div>
          
                                    <p class="form-row">
                                        <label>Email address <span class="required">*</span></label>
                                        <input type="email" class="input-text" name="account_email" value="{{ $user ? $user->email : '' }}">
                                    </p>
                                    <p class="form-row">
                                        <label>Phone number</label>
                                        <input type="tel" class="input-text" name="phone" value="{{ $user ? $user->phone : '' }}" placeholder="e.g. +1 (555) 123-4567">
                                    </p>
                                    <!-- Shipping address -->
                                    <fieldset>
                                        <legend>Shipping address</legend>
                                        <p class="form-row">
                                            <label>Street address</label>
                                            <input type="text" class="input-text" name="shipping_address_1" value="{{ $user ? $user->shipping_address_1 : '' }}" placeholder="House number and street name">
                                        </p>
                                        <p class="form-row">
                                            <label>Apartment, suite, unit, etc. <span class="optional">(optional)</span></label>
                                            <input type="text" class="input-text" name="shipping_address_2" value="{{ $user ? $user->shipping_address_2 : '' }}" placeholder="Apartment, suite, unit, etc. (optional)">
                                        </p>
                                        <p class="form-row">
                                            <label>Town / City</label>
                                            <input type="text" class="input-text" name="shipping_city" value="{{ $user ? $user->shipping_city : '' }}">
                                        </p>
                                        <p class="form-row">
                                            <label>Country / Region</label>
                                            <select id="shipping_country" name="shipping_country" class="input-text " data-selected-country="{{ $user ? $user->shipping_country : '' }}">
                                                <option value="">Select a Country / Region</option>
                                            </select>
                                        </p>
                                        <p class="form-row">
                                            <label>State / County</label>
                                            <select id="shipping_state" name="shipping_state" class="input-text" data-selected-state="{{ $user ? $user->shipping_state : '' }}">
                                                <option value="">Select a State / County</option>
                                            </select>
                                        </p>
                                        <p class="form-row">
                                            <label>Postcode / ZIP</label>
                                            <input type="text" class="input-text" name="shipping_postcode" value="{{ $user ? $user->shipping_postcode : '' }}">
                                        </p>
                                    </fieldset>

                                    <div class="clear"></div>
                                    <fieldset>
                                        <legend>Password change</legend>
                                        <p class="form-row" style="position:relative;">
                                            <label>Current password</label>
                                            <input type="password" class="input-text" name="password_current" autocomplete="off" id="password_current" style="width:100%;padding-right:40px;">
                                            <span class="password-toggle" onclick="togglePassword('password_current', this)" style="position:absolute;top:38px;right:15px;cursor:pointer;z-index:2;">
                                                <i class="fa fa-eye"></i>
                                            </span>
                                        </p>
                                        <p class="form-row" style="position:relative;">
                                            <label>New password</label>
                                            <input type="password" class="input-text" name="password_1" autocomplete="off" id="password_1" style="width:100%;padding-right:40px;">
                                            <span class="password-toggle" onclick="togglePassword('password_1', this)" style="position:absolute;top:38px;right:15px;cursor:pointer;z-index:2;">
                                                <i class="fa fa-eye"></i>
                                            </span>
                                        </p>
                                        <p class="form-row" style="position:relative;">
                                            <label>Confirm new password</label>
                                            <input type="password" class="input-text" name="password_2" autocomplete="off" id="password_2" style="width:100%;padding-right:40px;">
                                            <span class="password-toggle" onclick="togglePassword('password_2', this)" style="position:absolute;top:38px;right:15px;cursor:pointer;z-index:2;">
                                                <i class="fa fa-eye"></i>
                                            </span>
                                        </p>
                                    </fieldset>
                                    <div class="clear"></div>
                                    <div class="account-success-message text-success" id="account-success-message"></div>
                                    
                                    <div id="account-error-message" class="alert alert-danger text-danger" style="display:none;"></div>
                                    <p class="form-row">
                                        <button type="submit" class="button" name="save_account_details" value="Save changes">Save changes</button>
                                    </p>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div><!-- #content -->



@endsection

@push('scripts')
<script>
function togglePassword(fieldId, el) {
    var input = document.getElementById(fieldId);
    var icon = el.querySelector('i');
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}

$(document).ready(function() {
    $('#edit-account-form').on('submit', function(e) {
        e.preventDefault();
        $('#account-error-message').hide().html('');
        $('#account-success-message').hide().html('');
        var form = $(this);
        var formData = form.serialize();
        $.ajax({
            url: form.attr('action'),
            method: 'POST',
            data: formData,
            success: function(response) {
                $('#account-success-message').html('');
                $('#account-success-message').html(response.message || 'Account details updated successfully.').show();
                $('#password_current').val('');
                $('#password_1').val('');
                $('#password_2').val('');
            },
            error: function(xhr) {
                var errors = xhr.responseJSON && xhr.responseJSON.errors ? xhr.responseJSON.errors : null;
                var message = '';
                if (errors) {
                    $.each(errors, function(key, val) {
                        message += val[0] + '<br>';
                    });
                } else if (xhr.responseJSON && xhr.responseJSON.message) {
                    message = xhr.responseJSON.message;
                } else {
                    message = 'An error occurred. Please try again.';
                }
                $('#account-error-message').html(message).show();
            }
        });
    });  
    // Shipping country/state dropdowns
    var countriesData = [];
    var $countrySelect = $('#shipping_country');
    var $stateSelect = $('#shipping_state');

    if ($countrySelect.length && $stateSelect.length) {
        
        function populateStates(countryCode, selectedStateCode) {
            var states = [];
            countriesData.forEach(function(country) {
                if (country.iso2 === countryCode) {
                    states = country.states || [];
                }
            });
            
            var stateOptions = '<option value="">Select a state / county…</option>';
            states.forEach(function(state) {
                var selectedAttr = (state.state_code === selectedStateCode) ? ' selected' : '';
                stateOptions += '<option value="' + state.state_code + '"' + selectedAttr + '>' + state.name + '</option>';
            });
            $stateSelect.html(stateOptions);
        }

        // Get selected values BEFORE ajax call
        var preselectedCountry = $countrySelect.data('selected-country') || '';
        var preselectedState = $stateSelect.data('selected-state') || '';

        $.getJSON('public/countries.json', function(data) {
            countriesData = data;
            
            // Build country options with preselection
            var countryOptions = '<option value="">Select a country / region…</option>';
            data.forEach(function(country) {
                var selectedAttr = (country.iso2 === preselectedCountry) ? ' selected' : '';
                countryOptions += '<option value="' + country.iso2 + '"' + selectedAttr + '>' + country.name + '</option>';
            });
            $countrySelect.html(countryOptions);

            // Now populate states if country was preselected
            if (preselectedCountry) {
                populateStates(preselectedCountry, preselectedState);
            }
        });

        $countrySelect.on('change', function() {
            var countryCode = $(this).val();
            populateStates(countryCode, ''); // Clear state on country change
        });
    }
});
$(document).on('click', '.view-order-detail', function(e) {
    e.preventDefault();
    var btn = $(this);
    var orderId = btn.data('order-id');
    var detailRow = $('#order-detail-row-' + orderId);
    if (detailRow.is(':visible')) {
        detailRow.hide();
        detailRow.find('td').html('');
        return;
    }
    // Hide any other open detail rows
    $('.order-detail-row').hide().find('td').html('');
    $.ajax({
        url: 'account/orders/' + orderId + '/ajax',
        method: 'GET',
        success: function(response) {
            var order = response.order;
            var html = '<div class="order-detail-box">';
            html += '<strong>Order #' + order.id + '</strong> | Date: ' + order.created_at + ' | Status: ' + order.status + '<br>';
            html += '<table class="table table-sm mt-2"><thead><tr><th>Image</th><th>Product</th><th>Qty</th><th>Price</th><th>Subtotal</th></tr></thead><tbody>';
            order.items.forEach(function(item) {
                html += '<tr>';
                html += '<td>' + (item.image ? '<img src="' + item.image + '" alt="'+item.name+'" style="max-width:50px;max-height:50px;">' : '') + '</td>';
                html += '<td>' + item.name + '</td>';
                html += '<td>' + item.quantity + '</td>';
                html += '<td>$' + parseFloat(item.price).toFixed(2) + '</td>';
                html += '<td>$' + parseFloat(item.subtotal).toFixed(2) + '</td>';
                html += '</tr>';
            });
            html += '<tr><td colspan="4" class="text-right">Subtotal:</td><td>$' + parseFloat(order.subtotal).toFixed(2) + '</td></tr>';
            html += '<tr><td colspan="4" class="text-right">Shipping Fee:</td><td>$' + parseFloat(order.shipping_fee).toFixed(2) + '</td></tr>';
            html += '<tr><td colspan="4" class="text-right">State Fee:</td><td>$' + parseFloat(order.state_fee).toFixed(2) + '</td></tr>';
            html += '<tr><td colspan="4" class="text-right">Service Fee:</td><td>$' + parseFloat(order.service_fee).toFixed(2) + '</td></tr>';
            if(order.payment_method === 'credit_card' || order.payment_method === 'paypal') {
                html += '<tr><td colspan="4" class="text-right">Credit Card Fee:</td><td>$' + parseFloat(order.credit_card_fee).toFixed(2) + '</td></tr>';
            }
            html += '<tr><td colspan="4" class="text-right"><strong>Total:</strong></td><td><strong>$' + parseFloat(order.total).toFixed(2) + '</strong></td></tr>';
            html += '</tbody></table></div>';
            detailRow.find('td').html(html);
            detailRow.show();
        },
        error: function(xhr) {
            detailRow.find('td').html('<div class="text-danger">Could not load order details.</div>');
            detailRow.show();
        }
    });
});
</script>
@endpush