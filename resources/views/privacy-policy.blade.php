@php
$sliders = \App\Models\HomeSlider::orderBy('order')->get();
@endphp

@extends('layouts.app')

@section('content')

{{-- Reusable Slider Component --}}
<x-mainslider :sliders="$sliders" height="30vh" autoplay="true" />

<x-page-header 
    title="Sales Policy" 
    :breadcrumbs="[
        ['label' => 'Home', 'url' => '/home'],
        ['label' => 'Sales Policy']
    ]" 
/>

<div id="content" class="site-content" role="main">
    <div class="section-padding">
        <div class="section-container p-l-r">
            <div class="page-faq">
                <div class="row">
                    <div class="col-md-12">
                       

<p><strong>Sales Policy</strong></p>
<p>At <strong>Oasis Mint</strong>, our goal is to provide a seamless, secure, and transparent experience for all our clients. Please review our sales policy carefully before making a purchase. By placing an order with Oasis Mint, you acknowledge and agree to the terms below.</p>

<strong>1. Product Availability &amp; Pricing</strong><br>

<p>All products listed on our website are subject to <strong>availability</strong> and <strong>market-based pricing</strong>. Prices are tied to live spot market values and may fluctuate without notice. Placing an item in your cart does <strong>not</strong> guarantee price or availability until the order is confirmed and payment is received.</p>

<strong>2. Order Confirmation &amp; Finalization</strong><br>

<p>Orders are considered <strong>final</strong> once placed. Upon checkout, you will receive an email confirmation detailing your purchase. Oasis Mint reserves the right to refuse or cancel any order at our discretion, including in the event of pricing errors or inventory issues.</p>


<strong>3. Payment Terms</strong><br>

<p>We accept the following payment methods:</p>
<ul>
<li><strong>Bank Wire Transfer</strong> (preferred for high-value orders)</li>
<li><strong>ACH Transfers</strong></li>
<li><strong>Checks</strong></li>
<li><strong>Select Credit Cards</strong> (subject to verification and limits)</li>
</ul>
<p>Orders are processed upon <strong>cleared payment</strong>. For check payments, shipping may be delayed until funds are fully verified. Wire transfers must be completed within <strong>24 hours</strong> of order placement to secure pricing.</p>

<strong>4. Shipping &amp; Insurance</strong><br>

<p>All orders are shipped <strong>fully insured</strong> with tracking and signature confirmation required. We use trusted carriers (e.g., UPS, FedEx, USPS) and package all items discreetly for your security. Once delivered and signed for, Oasis Mint is not responsible for lost or stolen items.</p>

<strong>5. Returns &amp; Cancellations</strong><br>

<p>Due to the volatile nature of the precious metals market, <strong>all sales are final</strong>. We do <strong>not</strong> accept returns or offer refunds unless the item is received in damaged or incorrect condition. In the rare event of a problem with your order, please contact us within <strong>48 hours</strong> of receipt.</p>

<strong>6. Sales Tax</strong><br>

<p>We are required to collect <strong>sales tax</strong> in accordance with applicable state laws. Sales tax will be calculated and applied at checkout based on your shipping address. For tax-exempt purchases, valid resale or exemption certificates must be submitted and approved prior to ordering.</p>

<strong>7. Identity Verification</strong><br>

<p>To ensure compliance with anti-fraud and anti-money laundering (AML) laws, Oasis Mint may request <strong>government-issued ID or additional documentation</strong> for certain transactions. We reserve the right to hold or cancel orders pending verification.</p>

<strong>8. Market Loss Policy</strong><br>

<p>Once an order is confirmed, you are <strong>locked into the agreed price</strong>. If payment is not received within the required timeframe, Oasis Mint reserves the right to cancel the order and recover <strong>any market loss</strong> resulting from price changes between the time of order and cancellation.</p>

<strong>9. Limitation of Liability</strong>

<p>Oasis Mint shall not be liable for any indirect, incidental, or consequential damages arising from the use of our products or services. Our maximum liability is limited to the original purchase price of the item(s) in question.</p>

<strong>10. Customer Support</strong>

<p>If you have any questions or need assistance, our team is here to help. Contact us via:<br /> <strong>Email:</strong> info@oasismint.com<br /> <strong>Phone:</strong> (212) 470-7540<br /> <strong>Address:</strong> 1234 St John's Place. Unit #130426. Brooklyn, New York 11213<br /> <b>Business Hours:</b> 9-5 pm - Monday thru Friday</p>

                    </div>
                    
                </div>
            </div>
        </div>
    </div>
</div><!-- #content -->

@endsection