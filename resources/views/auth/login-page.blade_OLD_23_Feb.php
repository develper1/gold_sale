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
                                                <a href="{{ route('forget-password') }}">Lost your password?</a>
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
                                <div class="alert alert-info mb-3" role="alert">
                                    <strong>Note:</strong> For anti-money laundering compliance, you must register an account to place orders. Guest checkout is not available.

                                </div>
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

</script>
@endpush
