@php
$sliders = \App\Models\HomeSlider::orderBy('order')->get();
@endphp

@extends('layouts.app')

@section('content')

<x-mainslider :sliders="$sliders" height="30vh" autoplay="true" />

<x-page-header 
    title="Verify Your Email" 
    :breadcrumbs="[
        ['label' => 'Home', 'url' => '/home'],
        ['label' => 'Verify Email']
    ]" 
/>

<div id="content" class="site-content" role="main">
    <div class="section-padding">
        <div class="section-container p-l-r">
            <div class="container" style="max-width: 600px; margin: 0 auto;">
                <div class="card" style="padding: 2rem;">
                    <h2 style="margin-bottom: 1rem;">Verify Your Email Address</h2>
                    @if (session('resent'))
                        <div class="alert alert-success" role="alert" style="margin-bottom: 1rem;">
                            A fresh verification link has been sent to your email address.
                        </div>
                    @endif
                    <p>Before proceeding, please check your email for a verification link.</p>
                    <p>
                        If you did not receive the email,
                        <form class="d-inline" method="POST" action="{{ route('verification.send') }}">
                            @csrf
                            <button type="submit" class="button" style="background:none;border:none;padding:0;color:#007bff;cursor:pointer;text-decoration:underline;">click here to request another</button>.
                        </form>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
