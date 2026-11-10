@php
$sliders = \App\Models\HomeSlider::orderBy('order')->get();
@endphp

@extends('layouts.app')

@section('content')
{{-- Reusable Slider Component --}}
<x-mainslider :sliders="$sliders" height="30vh" autoplay="true" />


<x-page-header 
    title="About Us" 
    :breadcrumbs="[
        ['label' => 'Home', 'url' => '/'],
        ['label' => 'About Us']
    ]" 
/>

<div id="content" class="site-content" role="main">
    <div class="section-padding">
        <div class="section-container p-l-r">
            <div class="page-about-us">
                <div class="row">
                    <div class="col-md-12">
                            <h1>About Us</h1>
    <p>At Oasis Mint, we bring timeless value to modern investors. Specializing in the acquisition and sale of Government Minted Gold, Silver and Platinum coinage, bullion, rare coins, and collectible precious metals. Our company stands at the intersection of honesty, integrity, expertise, and lasting wealth.</p>
    
    <p>Headquartered in New York, Oasis Mint was founded on a deep passion for official government minted coins and precious metals, with a commitment to transparency, trust, and personalized service. With over three decades of experience in the industry, we have built a reputation for excellence among both private collectors and institutional clients across the U.S. and beyond.</p>
    
    <p>Our team of knowledgeable professionals are dedicated to helping clients preserve wealth, diversify their portfolios, and build tangible legacies. We offer only the finest and Official Government Minted coins, investment-grade bullion, and authenticated rare pieces-carefully sourced and securely delivered direct to you.</p>
    
    <h2>Our Mission</h2>
    <p>To deliver honest exceptional service, premium products, and trusted guidance in every transaction-empowering our clients to invest in their future with confidence and clarity.</p>
    
    <h2>Our Vision</h2>
    <p>To be a leading name in the precious metals space, known for reliability, sophistication, and a customer-first approach in everything we do.</p>
    
    <p>Whether you are a seasoned investor or just beginning your journey into precious metals, Oasis Mint will be your refined partner for security, legacy, and long-term value.</p>
    
    <h2>Founder's Note</h2>
    <blockquote>
        <p>"When I founded Oasis Mint and launched our easy to use website, my vision was simple: Create a company where people could invest in gold, platinum and silver with the same trust, care, and confidence they'd expect from a lifelong partner. I've always believed that precious metals are more than just commodities-they're symbols of security, heritage, and enduring value.</p>
        
        <p>Today, we serve clients from all walks of life-from first-time buyers seeking fractional gold, platinum or silver coinage to seasoned investors acquiring large holdings of bullion and precious metals-with the honesty and respect they deserve.</p>
        
        <p>At Oasis Mint, you're not just making a purchase; you're building something lasting.</p>
        
        <p>Thank you for placing your trust in us."</p>
        
        — Yossi Malamud<br>Founder & CEO, Oasis Mint
    </blockquote>
	<br><br>
	<img src="../../assets/media/ANA_Member.jpg" style="width:50%; max-width:400px" alt="ANA Member" border="0">
	
                    </div>
                </div>
                
            </div>
        </div>
    </div>
</div><!-- #content -->

@endsection