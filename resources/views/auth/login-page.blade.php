@php
$sliders = \App\Models\HomeSlider::orderBy('order')->get();
@endphp

@extends('layouts.app')

@section('content')


{{-- Reusable Slider Component --}}
<x-mainslider :sliders="$sliders" height="30vh" autoplay="true" />

<x-page-header 
    title="Login / Register" 
    :breadcrumbs="[
        ['label' => 'Home', 'url' => '/'],
        ['label' => 'Login / Register']
    ]" 
/>


{{-- Error Summary at Top --}}
@if($errors->any())
    <div class="alert alert-danger" style="
        background-color: #f8d7da;
        color: #721c24;
        padding: 15px 20px;
        margin: 20px auto;
        max-width: 800px;
        border-radius: 8px;
        border-left: 5px solid #dc3545;
        text-align: center;
        font-weight: 500;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    ">
        <i class="fa fa-exclamation-circle" style="margin-right: 8px;"></i>
        <strong>Please fix the following errors:</strong>
        <ul style="list-style: none; padding: 0; margin: 10px 0 0 0;">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div id="content" class="site-content" role="main">
    <div class="section-padding">
        <div class="section-container p-l-r">
            <div class="page-login-register">
                <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-12 sm-m-b-50">
                        <div class="box-form-login">
                            <h2>Login</h2>
                            <div class="box-content">
                                <div class="form-login">
                                    <form method="post" class="login" action="{{ route('login') }}">
                                    {{-- <!-- <form method="post" class="login" id="login-form" action="{{ route('login.submit') }}"> --> --}}
                                        @csrf
                                        <div class="username">
                                            <label>{{ __('Email Address') }} <span class="required">*</span></label>
                                            <input id="email" type="email" class="input-text @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                                            @error('email')
                                                <span class="invalid-feedback" style="color: red; display: block;" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        <div class="password" style="position:relative;">
                                            <label for="password">{{ __('Password') }} <span class="required">*</span></label>
                                            <input class="input-text @error('password') is-invalid @enderror" type="password" name="password" id="password" required autocomplete="current-password">
                                            <span class="password-toggle" onclick="togglePassword('password', this)" style="position:absolute;top:38px;right:15px;cursor:pointer;z-index:2;">
                                                <i class="fa fa-eye"></i>
                                            </span>
                                        </div>
                                        <div class="rememberme-lost">
                                            <div class="remember-me">
                                                <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                                <label for="remember" class="inline">Remember me</label>
                                            </div>

                                            <div class="lost-password">
                                                <a href="{{ route('forget-password') }}">Forgot Password?</a>
                                            </div>
                                        </div>
                                        {{-- <!-- <div class="row">
                                            <div class="col-sm-12">
                                                <div class="g-recaptcha" data-sitekey="{{ config('services.recaptcha.site_key') }}"></div>
                                                @error('g-recaptcha-response')
                                                    <span class="invalid-feedback" style="color: #dc3545; display: block; margin-top: 5px;" role="alert">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div> --> --}}
                                        <div class="button-login">
                                            <input type="submit" class="button" name="login" value="Login">
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12">
                        <div class="box-form-login">
                            <h2 class="register">Register</h2>
                            <div class="box-content">
                                <div class="alert alert-info mb-3" role="alert">
                                    <strong>Note:</strong> For anti-money laundering compliance, you must register an account to place orders. Guest checkout is not available.

                                </div>
                                <div class="form-register" id="register-form-section">
                                    <form method="post" class="register" id="register-form" action="{{ route('register.submit') }}">
                                        @csrf
                                        <div class="email">
                                            <label>Name <span class="required">*</span></label>
                                            <input type="text" class="input-text @error('register_name') is-invalid @enderror" name="register_name" value="{{ old('register_name') }}" required autocomplete="name" {{ $errors->has('register_email') ? 'autofocus' : '' }}>
                                            @error('register_name')
                                                <span class="invalid-feedback" style="color: #dc3545; display: block; margin-top: 4px;" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        <div class="email">
                                            <label>Email address <span class="required">*</span></label>
                                            <input type="email" class="input-text @error('register_email') is-invalid @enderror" name="register_email" value="{{ old('register_email') }}" required autocomplete="email">
                                            @error('register_email')
                                                <span class="invalid-feedback" style="color: #dc3545; display: block; margin-top: 4px;" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                <p style="margin-top: 8px; margin-bottom: 0; font-size: 0.9em;">
                                                    <a href="{{ route('login') }}">Log in instead</a> ·
                                                    <a href="{{ route('forget-password') }}">Forgot Password</a>
                                                </p>
                                            @enderror
                                        </div>
                                        <div class="password">
                                            <label>Password <span class="required">*</span></label>
                                            <input type="password" class="input-text @error('register_password') is-invalid @enderror" name="register_password" required autocomplete="new-password">
                                            @error('register_password')
                                                <span class="invalid-feedback" style="color: #dc3545; display: block; margin-top: 4px;" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        <div class="password">
                                            <label>{{ __('Confirm Password') }} <span class="required">*</span></label>
                                            <input type="password" class="input-text" name="register_password_confirmation" required autocomplete="new-password">

                                        </div>
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <div class="g-recaptcha" data-sitekey="{{ config('services.recaptcha.site_key') }}"></div>
                                                @error('g-recaptcha-response')
                                                    <span class="invalid-feedback" style="color: #dc3545; display: block; margin-top: 5px;" role="alert">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="button-register">
                                            <input type="submit" class="button" name="register" value="Register">
                                        </div>
                                    </form>
                                </div>
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
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
<script>
$(document).ready(function() {
    // With two reCAPTCHA widgets, first (login) = widget 0, second (register) = widget 1
    // var loginWidgetId = 0;
    var registerWidgetId = 1;
    // Client-side captcha validation for login form
    // document.getElementById('login-form')?.addEventListener('submit', function(e) {
    //     if (typeof grecaptcha !== 'undefined') {
    //         var response = grecaptcha.getResponse(loginWidgetId);
    //         if (!response || response.length === 0) {
    //             e.preventDefault();
    //             alert('Please complete the CAPTCHA verification.');
    //             return false;
    //         }
    //     }
    // });
    // Client-side captcha validation for register form
    document.getElementById('register-form')?.addEventListener('submit', function(e) {
        if (typeof grecaptcha !== 'undefined') {
            var response = grecaptcha.getResponse(registerWidgetId);
            if (!response || response.length === 0) {
                e.preventDefault();
                alert('Please complete the CAPTCHA verification.');
                return false;
            }
        }
    });
    @if($errors->has('register_email') || $errors->has('register_name') || $errors->has('register_password'))
    // Scroll to register form when there are validation errors
    document.getElementById('register-form-section')?.scrollIntoView({ behavior: 'smooth', block: 'start' });
    @endif
});
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

</script>
@endpush
