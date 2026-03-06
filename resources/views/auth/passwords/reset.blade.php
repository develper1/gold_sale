@php
$sliders = \App\Models\HomeSlider::orderBy('order')->get();
@endphp

@extends('layouts.app')

@section('content')
<!-- <div id="title" class="page-title">
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
</div> -->
{{-- Reusable Slider Component --}}
    <x-mainslider :sliders="$sliders" height="30vh" autoplay="true" />

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
                    <p class="form-row form-row-first" style="position:relative;">
                        <label>New Password</label>
                        <input class="input-text" type="password" name="password" id="password" required autocomplete="new-password">
                        <span class="password-toggle" onclick="togglePassword('password', this)" style="position:absolute;top:38px;right:15px;cursor:pointer;z-index:2;">
                            <i class="fa fa-eye"></i>
                        </span>
                        @error('password')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </p>
                    <p class="form-row form-row-first" style="position:relative;">
                        <label>Confirm New Password</label>
                        <input class="input-text" type="password" name="password_confirmation" id="password_confirmation" required autocomplete="new-password">
                        <span class="password-toggle" onclick="togglePassword('password_confirmation', this)" style="position:absolute;top:38px;right:15px;cursor:pointer;z-index:2;">
                            <i class="fa fa-eye"></i>
                        </span>
                    </p>

                    {{-- CAPTCHA - Same style as contact page --}}
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="g-recaptcha" data-sitekey="{{ config('services.recaptcha.site_key') }}"></div>
                            @error('g-recaptcha-response')
                                <span class="text-danger" style="display: block; margin-top: 5px;">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

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

@push('scripts')
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
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

document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('reset-password-form');
    const messageDiv = document.getElementById('reset-message');
    const submitBtn = document.getElementById('submit-btn');
    
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Show loading state
        submitBtn.disabled = true;
        submitBtn.textContent = 'Resetting...';
        
        // Hide any previous messages
        messageDiv.style.display = 'none';
        
        // Get reCAPTCHA response
        const recaptchaResponse = grecaptcha.getResponse();
        if (!recaptchaResponse) {
            showMessage('Please complete the reCAPTCHA verification.', 'error');
            submitBtn.disabled = false;
            submitBtn.textContent = 'Reset Password';
            return;
        }
        
        // Create FormData and append reCAPTCHA
        const formData = new FormData(form);
        formData.append('g-recaptcha-response', recaptchaResponse);
        
        // Send AJAX request
        fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
            }
        })
        .then(response => {
            if (response.redirected) {
                // If redirected, follow the redirect (to success page)
                window.location.href = response.url;
                return;
            }
            return response.json();
        })
        .then(data => {
            if (data && data.status === 'error') {
                showMessage(data.message, 'error');
                grecaptcha.reset();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            // If it's a redirect (success), this catch won't fire
        })
        .finally(() => {
            if (submitBtn.disabled) {
                submitBtn.disabled = false;
                submitBtn.textContent = 'Reset Password';
            }
        });
    });
    
    function showMessage(message, type) {
        messageDiv.textContent = message;
        messageDiv.style.display = 'block';
        messageDiv.style.padding = '15px';
        messageDiv.style.borderRadius = '5px';
        messageDiv.style.marginBottom = '20px';
        
        if (type === 'error') {
            messageDiv.style.color = '#721c24';
            messageDiv.style.backgroundColor = '#f8d7da';
            messageDiv.style.border = '1px solid #f5c6cb';
        } else {
            messageDiv.style.color = '#155724';
            messageDiv.style.backgroundColor = '#d4edda';
            messageDiv.style.border = '1px solid #c3e6cb';
        }
        
        messageDiv.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }
    
});

</script>
@endpush