@extends('layouts.app')

@section('content')

<div id="title" class="page-title">
    <div class="section-container">
        <div class="content-title-heading">
            <h1 class="text-title-heading">
                Login / Register
            </h1>
        </div>
        <div class="breadcrumbs">
            <a href="index.html">Home</a><span class="delimiter"></span>Login / Register
        </div>
    </div>
</div>

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
                                        @csrf
                                        <div class="username">
                                            <label>{{ __('Email Address') }} <span class="required">*</span></label>
                                            <input id="email" type="email" class="input-text @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                                            @error('email')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        <div class="password">
                                            <label for="password">{{ __('Password') }} <span class="required">*</span></label>
                                            <input class="input-text @error('password') is-invalid @enderror" type="password" name="password" required autocomplete="current-password">
                                        </div>
                                        <div class="rememberme-lost">
                                            <div class="remember-me">
                                                <input name="rememberme" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                                <label class="inline">Remember me</label>
                                            </div>
                                            <div class="lost-password">
                                                <a href="page-forgot-password.html">Lost your password?</a>
                                            </div>
                                        </div>
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
                                <div class="form-register">
                                    <form method="post" class="register" action="{{ route('register') }}">
                                        @csrf
                                        <div class="email">
                                            <label>Name <span class="required">*</span></label>
                                            <input type="text" class="input-text @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>
                                            @error('name')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        <div class="email">
                                            <label>Email address <span class="required">*</span></label>
                                            <input type="email" class="input-text @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">
                                            @error('email')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        <div class="password">
                                            <label>Password <span class="required">*</span></label>
                                            <input type="password" class="input-text @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">
                                            @error('password')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        <div class="password">
                                            <label>{{ __('Confirm Password') }} <span class="required">*</span></label>
                                            <input type="password" class="input-text" name="password_confirmation" required autocomplete="new-password">
                                            
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