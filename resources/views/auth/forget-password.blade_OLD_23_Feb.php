@extends('layouts.app')

@section('content')
<div id="title" class="page-title">
    <div class="section-container">
        <div class="content-title-heading">
            <h1 class="text-title-heading">
                Forgot Password
            </h1>
        </div>
        <div class="breadcrumbs">
            <a href="index.html">Home</a><span class="delimiter"></span>Forgot Password
        </div>
    </div>
</div>

<div id="content" class="site-content" role="main">
    <div class="section-padding">
        <div class="section-container p-l-r">
            <div class="page-forget-password">
                <form method="post" class="reset-password" action="{{ route('forget-password.submit') }}">
                    @csrf
                    @if(session('status'))
                        <div class="alert alert-success">{{ session('status') }}</div>
                    @endif
                    @if(session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif
                    <p>Lost your password? Please enter your  email address. You will receive a link to create a new password via email.</p>
                    <p class="form-row form-row-first">
                        <label>Email</label>
                        <input class="input-text" type="text" name="user_login" autocomplete="username" value="{{ old('user_login') }}">
                        @error('user_login')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </p>
                    <div class="clear"></div>
                    <p class="form-row">
                        <button type="submit" class="button" value="Reset password">Reset password</button>
                    </p>
                </form>
            </div>
        </div>
    </div>
</div><!-- #content -->

@endsection