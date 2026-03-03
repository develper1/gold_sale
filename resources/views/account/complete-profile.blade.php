@php
$sliders = \App\Models\HomeSlider::orderBy('order')->get();
@endphp

@extends('layouts.app')

@push('styles')
<style>
.page-complete-profile .my-account-content {
    max-width: 700px;
    margin: 0 auto;
}
.page-complete-profile .form-row select.input-text,
.page-complete-profile .form-row .custom-select {
    width: 100%;
    padding: 10px 12px;
    border: 1px solid #e6e6e6;
    border-radius: 4px;
    background: #fff;
    font-size: 14px;
    appearance: auto;
    -webkit-appearance: menulist;
}
.page-complete-profile .form-row select:focus {
    border-color: #cb8161;
    outline: none;
}
.page-complete-profile .form-row .input-text {
    width: 100%;
}
.page-complete-profile .alert-info a:hover {
    text-decoration: underline;
}
</style>
@endpush

@section('content')

<x-mainslider :sliders="$sliders" height="30vh" autoplay="true" />

<x-page-header 
    title="Complete Your Profile" 
    :breadcrumbs="[
        ['label' => 'Home', 'url' => '/home'],
        ['label' => 'Complete Profile']
    ]" 
/>

<div id="content" class="site-content" role="main">
    <div class="section-padding">
        <div class="section-container p-l-r">
            <div class="page-my-account page-complete-profile">
                <div class="my-account-wrap clearfix">
                    <div class="my-account-content" style="width: 100%; float: none;">
                        <div class="my-account-account-details">
                            <h2 class="complete-profile-title" style="margin-bottom: 1rem; font-size: 22px;">Welcome, {{ $user->name ?? '' }}!</h2>
                            <p class="complete-profile-intro" style="margin-bottom: 1.5rem; color: #666; line-height: 1.6;">
                                Before you can place orders, please complete your profile with your address and contact details.
                                You must also read and agree to our policies below.
                            </p>

                            <div class="alert alert-info complete-profile-notice" style="background: #e7f3ff; border: 1px solid #b3d9ff; padding: 1rem 1.25rem; border-radius: 6px; margin-bottom: 25px; color: #0c5460;">
                                <strong>Important:</strong> Before placing orders, you must read and agree to the 
                                <a href="{{ route('sales-policy') }}" target="_blank" rel="noopener" style="color: #0056b3;">Sales Policy</a>, 
                                <a href="{{ route('returns-exchanges-policy') }}" target="_blank" rel="noopener" style="color: #0056b3;">Returns & Exchanges Policy</a>, 
                                <a href="{{ route('terms-of-sale') }}" target="_blank" rel="noopener" style="color: #0056b3;">Terms of Sale</a>, 
                                and <a href="{{ route('anti-money-laundering-policy') }}" target="_blank" rel="noopener" style="color: #0056b3;">Anti Money Laundering Policy</a>.
                                Your agreement will be stored in your customer profile.
                            </div>

                            <form class="edit-account" id="complete-profile-form" action="{{ route('account.complete-profile.store') }}" method="post">
                                @csrf
                                <p class="form-row">
                                    <label for="account_name">Name <span class="required">*</span></label>
                                    <input type="text" class="input-text" name="account_name" id="account_name" value="{{ old('account_name', $user ? $user->name : '') }}" required placeholder="Your full name">
                                </p>
                                <p class="form-row">
                                    <label for="account_email">Email address <span class="required">*</span></label>
                                    <input type="email" class="input-text" name="account_email" id="account_email" value="{{ old('account_email', $user ? $user->email : '') }}" required placeholder="your@email.com">
                                </p>
                                <p class="form-row">
                                    <label for="phone">Phone number <span class="required">*</span></label>
                                    <input type="tel" class="input-text" name="phone" id="phone" value="{{ old('phone', $user ? $user->phone : '') }}" required placeholder="e.g. +1 (555) 123-4567">
                                    @error('phone')
                                        <em class="text-danger" style="display: block; margin-top: 5px;">{{ $message }}</em>
                                    @enderror
                                </p>

                                <fieldset>
                                    <legend>Shipping address</legend>
                                    <p class="form-row form-row-wide">
                                        <label for="shipping_address_1">Street address <span class="required">*</span></label>
                                        <input type="text" class="input-text" name="shipping_address_1" id="shipping_address_1" value="{{ old('shipping_address_1', $user ? $user->shipping_address_1 : '') }}" required placeholder="House number and street name">
                                    </p>
                                    <p class="form-row form-row-wide">
                                        <label for="shipping_address_2">Apartment, suite, unit, etc. <span class="optional">(optional)</span></label>
                                        <input type="text" class="input-text" name="shipping_address_2" id="shipping_address_2" value="{{ old('shipping_address_2', $user ? $user->shipping_address_2 : '') }}" placeholder="Apartment, suite, unit, etc. (optional)">
                                    </p>
                                    <p class="form-row form-row-wide">
                                        <label for="shipping_city">Town / City <span class="required">*</span></label>
                                        <input type="text" class="input-text" name="shipping_city" id="shipping_city" value="{{ old('shipping_city', $user ? $user->shipping_city : '') }}" required placeholder="City name">
                                    </p>
                                    <p class="form-row form-row-wide">
                                        <label for="shipping_country">Country / Region <span class="required">*</span></label>
                                        <select id="shipping_country" name="shipping_country" class="input-text" data-selected-country="{{ old('shipping_country', $user ? $user->shipping_country : '') }}" required>
                                            <option value="">Select a Country / Region</option>
                                        </select>
                                        <span id="country-loading" class="loading-hint" style="font-size: 12px; color: #999; display: none; margin-top: 4px;">Loading countries…</span>
                                        <span id="country-error" class="text-danger" style="font-size: 12px; display: none; margin-top: 4px;"></span>
                                    </p>
                                    <p class="form-row form-row-wide">
                                        <label for="shipping_state">State / County <span class="required">*</span></label>
                                        <select id="shipping_state" name="shipping_state" class="input-text" data-selected-state="{{ old('shipping_state', $user ? $user->shipping_state : '') }}" required>
                                            <option value="">Select a State / County</option>
                                        </select>
                                        <span id="state-hint" class="loading-hint" style="font-size: 12px; color: #999; margin-top: 4px;">Select a country first</span>
                                    </p>
                                    <p class="form-row form-row-wide">
                                        <label for="shipping_postcode">Postcode / ZIP <span class="required">*</span></label>
                                        <input type="text" class="input-text" name="shipping_postcode" id="shipping_postcode" value="{{ old('shipping_postcode', $user ? $user->shipping_postcode : '') }}" required placeholder="Postal code">
                                    </p>
                                </fieldset>

                                <fieldset style="margin-top: 30px;">
                                    <legend>Policy agreement</legend>
                                    <p class="form-row form-row-wide">
                                        <label class="checkbox" style="display: flex; align-items: flex-start; gap: 10px; cursor: pointer;">
                                            <input type="checkbox" class="input-checkbox" name="agree_policies" id="agree_policies" value="1" {{ old('agree_policies') ? 'checked' : '' }} style="margin-top: 4px;">
                                            <span>
                                                I have read and agree to the 
                                                <a href="{{ route('sales-policy') }}" target="_blank" rel="noopener">Sales Policy</a>, 
                                                <a href="{{ route('returns-exchanges-policy') }}" target="_blank" rel="noopener">Returns & Exchanges Policy</a>, 
                                                <a href="{{ route('terms-of-sale') }}" target="_blank" rel="noopener">Terms of Sale</a>, 
                                                and <a href="{{ route('anti-money-laundering-policy') }}" target="_blank" rel="noopener">Anti Money Laundering Policy</a>. 
                                                <span class="required">*</span>
                                            </span>
                                        </label>
                                    </p>
                                    @error('agree_policies')
                                        <p class="text-danger" style="margin-top: -5px; margin-bottom: 15px; font-size: 13px;">{{ $message }}</p>
                                    @enderror
                                </fieldset>

                                <div class="clear"></div>
                                @if($errors->any())
                                    <div class="alert alert-danger" style="margin-bottom: 20px; padding: 12px 16px; border-radius: 6px;">
                                        <ul style="margin: 0; padding-left: 1.25rem;">
                                            @foreach($errors->all() as $err)
                                                <li>{{ $err }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                                <p class="form-row" style="margin-top: 25px;">
                                    <button type="submit" class="button" name="complete_profile" value="Complete Profile" style="padding: 12px 28px; font-size: 14px; text-transform: uppercase; letter-spacing: 0.5px;">Complete Profile</button>
                                </p>
                            </form>
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
    var countriesData = [];
    var countriesUrl = '{{ asset("/public/countries.json") }}';
    var $countrySelect = $('#shipping_country');
    var $stateSelect = $('#shipping_state');
    var $countryLoading = $('#country-loading');
    var $countryError = $('#country-error');
    var $stateHint = $('#state-hint');

    function populateStates(countryCode, selectedStateCode) {
        var states = [];
        countriesData.forEach(function(country) {
            if (country.iso2 === countryCode && country.states) {
                states = country.states;
            }
        });

        var stateOptions = '<option value="">Select a state / county…</option>';
        states.forEach(function(state) {
            var code = state.state_code || state.name || '';
            var name = state.name || state.state_code || '';
            var selectedAttr = (code === selectedStateCode) ? ' selected' : '';
            stateOptions += '<option value="' + escapeHtml(code) + '"' + selectedAttr + '>' + escapeHtml(name) + '</option>';
        });
        if (states.length === 0 && countryCode) {
            stateOptions += '<option value="N/A" selected>Not applicable</option>';
            $stateHint.text('This country has no states/regions').show();
        } else if (!countryCode) {
            $stateHint.text('Select a country first').show();
        } else {
            $stateHint.hide();
        }
        $stateSelect.html(stateOptions);
    }

    function escapeHtml(text) {
        var div = document.createElement('div');
        div.appendChild(document.createTextNode(text));
        return div.innerHTML;
    }

    var preselectedCountry = $countrySelect.data('selected-country') || '';
    var preselectedState = $stateSelect.data('selected-state') || '';

    $countryLoading.show();
    $countryError.hide();

    $.getJSON(countriesUrl)
        .done(function(data) {
            countriesData = data;
            var countryOptions = '<option value="">Select a country / region…</option>';
            data.forEach(function(country) {
                var selectedAttr = (country.iso2 === preselectedCountry) ? ' selected' : '';
                countryOptions += '<option value="' + country.iso2 + '"' + selectedAttr + '>' + country.name + '</option>';
            });
            $countrySelect.html(countryOptions);

            if (preselectedCountry) {
                populateStates(preselectedCountry, preselectedState);
                $stateHint.hide();
            }
        })
        .fail(function(jqxhr, textStatus, error) {
            $countryError.text('Could not load countries. Please refresh the page or contact support.').show();
            $countrySelect.html('<option value="">Select a country / region…</option>');
            $stateSelect.html('<option value="">Select a state / county…</option>');
        })
        .always(function() {
            $countryLoading.hide();
        });

    $countrySelect.on('change', function() {
        var countryCode = $(this).val();
        $stateHint.text(countryCode ? 'Loading…' : 'Select a country first');
        populateStates(countryCode || '', '');
    });

    $('#complete-profile-form').on('submit', function(e) {
        if (!$('#agree_policies').is(':checked')) {
            e.preventDefault();
            alert('You must agree to the policies before completing your profile.');
            $('#agree_policies').focus();
        }
    });
});
</script>
@endpush
