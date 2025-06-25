@extends('layouts.app')

@section('content')
<div id="title" class="page-title">
    <div class="section-container">
        <div class="content-title-heading">
            <h1 class="text-title-heading">
                Reset Password
            </h1>
        </div>
        <div class="breadcrumbs">
            <a href="/">Home</a><span class="delimiter"></span>Reset Password
        </div>
    </div>
</div>

<div id="content" class="site-content" role="main">
    <div class="section-padding">
        <div class="section-container p-l-r">
            <div class="page-forget-password">
                <form method="POST" class="reset-password" action="{{ route('password.update') }}">
                    @csrf
                    <input type="hidden" name="token" value="{{ $token }}">
                    <p class="form-row form-row-first">
                        <label>Email</label>
                        <input class="input-text" type="email" name="email" value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus>
                        @error('email')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </p>
                    <p class="form-row form-row-first">
                        <label>New Password</label>
                        <input class="input-text" type="password" name="password" required autocomplete="new-password">
                        @error('password')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </p>
                    <p class="form-row form-row-first">
                        <label>Confirm New Password</label>
                        <input class="input-text" type="password" name="password_confirmation" required autocomplete="new-password">
                    </p>
                    <div class="clear"></div>
                    <p class="form-row">
                        <button type="submit" class="button">Reset Password</button>
                    </p>
                </form>
            </div>
        </div>
    </div>
</div><!-- #content -->
@endsection
