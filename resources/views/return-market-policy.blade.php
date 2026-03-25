@php
$sliders = \App\Models\HomeSlider::orderBy('order')->get();
@endphp

@extends('layouts.app')

@section('content')

{{-- Reusable Slider Component --}}
<x-mainslider :sliders="$sliders" height="30vh" autoplay="true" />

<x-page-header 
    title="Terms of Sale" 
    :breadcrumbs="[
        ['label' => 'Home', 'url' => '/'],
        ['label' => 'Terms of Sale']
    ]" 
/>

<div id="content" class="site-content" role="main">
    <div class="section-padding">
        <div class="section-container p-l-r">
            <div class="page-faq">
                <div class="row">
                    <div class="col-md-12">
                        

                      <p><strong>Terms of Sale</strong></p>
<p>Welcome to <strong>Oasis Mint</strong>. By placing an order through our website, phone, or any other platform, you agree to the following <strong>Terms of Sale</strong>, which govern all transactions with Oasis Mint. Please read them carefully before proceeding.</p>

<strong>1. Binding Agreement</strong><br>

<p>Once you place an order with Oasis Mint, whether online or by phone, you are entering into a <strong>legally binding agreement</strong> to purchase the items at the agreed-upon price. Orders <strong>cannot be canceled or modified</strong> once confirmed.</p>

<strong>2. Market Fluctuation Acknowledgment</strong><br>

<p>Precious metals are subject to continuous price changes driven by global markets. By purchasing from Oasis Mint, you acknowledge that:</p>
<ul>
<li><strong>Prices are based on real-time spot market activity</strong></li>
<li>Your order <strong>locks in the price</strong> at the time of checkout or confirmation</li>
<li><strong>You assume the risk</strong> of market fluctuations, whether prices rise or fall after the transaction</li>
<li><strong>Cancellations are not permitted</strong> based on market changes</li>
</ul>
<p>In the event of non-payment or attempted cancellation, <strong>Oasis Mint reserves the right to recover market losses</strong>, administrative fees, and any associated costs.</p>

<strong>3. Payment Obligations</strong><br>

<p>Full payment is required within <strong>[typically 24&ndash;48 hours]</strong> of order confirmation. Accepted payment methods include:</p>
<ul>
<li>Bank wire</li>
<li>ACH transfer</li>
<li>Check (may be subject to a holding period)</li>
<li>Credit card (for qualifying orders)</li>
</ul>
<p>Failure to remit payment within the required window may result in order cancellation and enforcement of our <strong>Market Loss Policy</strong> (see Section 5).</p>

<strong>4. Sales Finality</strong><br>

<p><strong>All sales are final.</strong> Due to the nature of precious metals pricing, Oasis Mint does not accept returns, exchanges, or cancellations once an order is confirmed. This policy applies regardless of subsequent market activity or price movement.</p>

<strong>5. Market Loss Policy</strong>

<p>If an order is canceled by the client (or due to non-payment), and the current market price is lower than the locked-in price, Oasis Mint reserves the right to charge the customer the <strong>difference in market value ("market loss")</strong>, plus a <strong>restocking and administrative fee</strong>.<br /> If market prices have increased, Oasis Mint will retain any market gain and is under no obligation to share or refund the difference.</p>

<strong>6. Shipping, Risk of Loss &amp; Insurance</strong><br>

<p>Oasis Mint ships all orders fully insured with signature confirmation. Risk of loss transfers to the customer <strong>upon confirmed delivery</strong>. We are not responsible for packages lost or stolen <strong>after delivery is confirmed by the carrier</strong>.</p>

<strong>7. Taxes &amp; Legal Compliance</strong><br>

<p>You are responsible for any applicable <strong>state and local sales tax</strong>. We comply with all relevant U.S. laws, including <strong>AML (Anti-Money Laundering)</strong> and <strong>KYC (Know Your Customer)</strong> regulations. Certain orders may require identification verification.</p>

<strong>8. Right to Refuse or Cancel Orders</strong>

<p>We reserve the right to <strong>refuse or cancel any order</strong> at our sole discretion, including but not limited to:</p>
<ul>
<li>Pricing errors</li>
<li>Suspected fraud</li>
<li>Incomplete payment</li>
<li>Unverifiable identity</li>
</ul>
<p>In such cases, you will be notified, and if applicable, a refund will be issued via the original payment method.</p>

<strong>9. Contact Us</strong>

<p>For questions about these terms or your transaction, please contact us:<br /> <strong>Email:</strong> info@oasismint.com<br /> <strong>Phone:</strong> (212) 470-7540<br /> <strong>Address:</strong> 1234 St John's Place. Unit #130426. Brooklyn, New York 11213<br /> <b>Business Hours:</b> 9-5 pm - Monday thru Friday</p>
<p>By proceeding with a purchase from Oasis Mint, you agree to all terms outlined above.</p>





                    </div>
                    
                </div>
            </div>
        </div>
    </div>
</div><!-- #content -->

@endsection