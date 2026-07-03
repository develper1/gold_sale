<!DOCTYPE html>
<html lang="en">
	@include('layouts.header')
	<style>
		/* Keep footer newsletter layout clean when reCAPTCHA is enabled. */
		.block-newsletter .newsletter-form.newsletter-form-with-captcha {
			display: block;
			max-width: 360px;
		}
		.block-newsletter .newsletter-form.newsletter-form-with-captcha input[type='email'] {
			margin-bottom: 10px;
		}
		.block-newsletter .newsletter-form.newsletter-form-with-captcha .newsletter-captcha-wrap {
			margin: 10px 0 12px;
			overflow: hidden;
		}
		.block-newsletter .newsletter-form.newsletter-form-with-captcha .btn-submit {
			display: inline-flex;
			align-items: center;
			justify-content: center;
			min-width: 150px;
		}
		.block-newsletter .newsletter-form.newsletter-form-with-captcha .btn-submit:before {
			content: none;
		}
		.block-newsletter .newsletter-form.newsletter-form-with-captcha .btn-submit input[type='submit'] {
			height: 44px;
			line-height: 44px;
			padding: 0 18px;
			font-size: 13px;
			font-weight: 600;
			color: #fff;
		}
		@media (max-width: 420px) {
			.block-newsletter .newsletter-form.newsletter-form-with-captcha .g-recaptcha {
				transform: scale(0.88);
				transform-origin: left top;
			}
			.block-newsletter .newsletter-form.newsletter-form-with-captcha .newsletter-captcha-wrap {
				height: 68px;
			}
		}
	</style>
	
	<body class="home home-4 title-4">
		<div id="page" class="hfeed page-wrapper">
			@include('layouts.nav')
			@if(session('success'))
				<div class="alert alert-success" style="
					background-color: #d4edda;
					color: #155724;
					padding: 15px 20px;
					margin: 20px auto;
					max-width: 600px;
					border-radius: 8px;
					border-left: 5px solid #28a745;
					text-align: center;
					font-weight: 500;
					box-shadow: 0 2px 10px rgba(0,0,0,0.1);
				">
					<i class="fa fa-check-circle" style="margin-right: 8px;"></i>
					{{ session('success') }}
				</div>
			@endif

			@if(session('error'))
				<div class="alert alert-danger" style="
					background-color: #f8d7da;
					color: #721c24;
					padding: 15px 20px;
					margin: 20px auto;
					max-width: 600px;
					border-radius: 8px;
					border-left: 5px solid #dc3545;
					text-align: center;
					font-weight: 500;
					box-shadow: 0 2px 10px rgba(0,0,0,0.1);
				">
					<i class="fa fa-exclamation-circle" style="margin-right: 8px;"></i>
					{{ session('error') }}
				</div>
			@endif
			<div id="site-main" class="site-main">
				<div id="main-content" class="main-content">
					<div id="primary" class="content-area">
                        @yield('content')
						<!-- #content -->
					</div><!-- #primary -->
				</div><!-- #main-content -->
			</div>

			<footer id="site-footer" class="site-footer four-columns no-border-top">
				<div class="footer">
					<div class="section-padding">
						<div class="section-container">
							<div class="block-widget-wrap">
								<div class="row"><!-- 
									<div class="col-lg-3 col-md-6 column-1">
										<div class="block block-menu">
											<h2 class="block-title">Customer Services</h2>
											<div class="block-content">
												<ul>
													<li>
														<a href="shop-grid-left.html">Contact Us</a>
													</li>
													<li>
														<a href="shop-grid-left.html">Track Your Order</a>
													</li>
													<li>
														<a href="shop-grid-left.html">Product Care & Repair</a>
													</li>
													<li>
														<a href="shop-grid-left.html">Book an Appointment</a>
													</li>
													<li>
														<a href="shop-grid-left.html">Frequently Asked Questions</a>
													</li>
													<li>
														<a href="shop-grid-left.html">Shipping & Returns</a>
													</li>
												</ul>
											</div>
										</div>
									</div> -->
									<div class="col-lg-3 col-md-6 column-2">
										<div class="block block-menu">
											<h2 class="block-title">Policies</h2>
											<div class="block-content">
												<ul>
													<li>
														<a href="{{ route('sales-policy') }}">Sales Policy</a>
													</li>
													<li>
														<a href="{{ route('returns-exchanges-policy') }}">Returns & Exchanges Policy</a>
													</li>
													<li>
														<a href="{{ route('terms-of-sale') }}">Terms of Sale</a>
													</li>
													<li>
														<a href="{{ route('anti-money-laundering-policy') }}">Anti-Money Laundering Policy</a>
													</li>													
													
												</ul>
											</div>
										</div>
									</div><!-- 									
									<div class="col-lg-3 col-md-6 column-2">
										<div class="block block-menu">
											<h2 class="block-title">Policies</h2>
											<div class="block-content">
												<ul>
													<li>
														<a href="{{ route('sales-policy') }}">Privacy Policy</a>
													</li>
													<li>
														<a href="{{ route('returns-exchanges-policy') }}">User Agreement</a>
													</li>
													<li>
														<a href="{{ route('terms-of-sale') }}">Return & Market Policy</a>
													</li>
													
												</ul>
											</div>
										</div>
									</div>
									<div class="col-lg-3 col-md-6 column-3">
										<div class="block block-menu">
											<h2 class="block-title">Catalog</h2>
											<div class="block-content">
												<ul>
													<li>
														<a href="shop-grid-left.html">Earrings</a>
													</li>
													<li>
														<a href="shop-grid-left.html">Necklaces</a>
													</li>
													<li>
														<a href="shop-grid-left.html">Bracelets</a>
													</li>
													<li>
														<a href="shop-grid-left.html">Rings</a>
													</li>
													<li>
														<a href="shop-grid-left.html">Jewelry Box</a>
													</li>
													<li>
														<a href="shop-grid-left.html">Studs</a>
													</li>
												</ul>
											</div>
										</div>
									</div> -->
									<div class="col-lg-3 col-md-6 column-4">
										<div class="block block-newsletter">
											<h2 class="block-title">Our Newsletter</h2>
											<div class="block-content">
												<div class="newsletter-text">Sign up for the latest offers and exclusives.</div>
												<form action="{{ route('subscriber.store') }}" method="POST" class="newsletter-form newsletter-form-with-captcha">
													@csrf
													<input type="email" name="email" placeholder="Email address" required>
													<div class="newsletter-captcha-wrap">
														<div class="g-recaptcha" data-sitekey="{{ config('services.recaptcha.site_key') }}"></div>
													</div>
													@error('g-recaptcha-response')
														<span class="invalid-feedback" style="color: #dc3545; display: block; margin-top: 5px;" role="alert">{{ $message }}</span>
													@enderror
													<label class="btn-submit" for="newsletter-submit">
														<input type="submit" id="newsletter-submit" value="Subscribe">
													</label>
												</form>
											</div>
										</div>

										<!-- <div class="block block-social">
											<ul class="social-link">
												<li><a href="https://www.instagram.com/oasis.mint" target="_blank"><i class="fa fa-instagram"></i></a></li>
											</ul>
										</div> -->
									</div>
									
									
									
									<div class="col-lg-3 col-md-6 column-4">
										<div class="block block-newsletter">
											<h2 class="block-title">Membership</h2>
											<div class="block-content">
												
													<img src="../../../assets/media/ANA_Member.jpg" style="width:50%; max-width:400px" alt="ANA Member" border="0">
											</div>
										</div>

									</div>
								</div>
								
								
								
								
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="footer-bottom">
					<div class="section-padding">
						<div class="section-container">
							<div class="block-widget-wrap">
								<div class="row">
									<div class="col-md-6">
										<div class="footer-left">
											<p class="copyright">Copyright © 2023. All Right Reserved</p>
										</div>
									</div>
									<div class="col-md-6">
										<div class="footer-right">
											<div class="block block-image">
												<img width="309" height="32" src="{{ asset('assets/media/payments.png') }}" alt="">
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</footer>
		</div>

        @include('layouts.footer')

		<script src="https://www.google.com/recaptcha/api.js" async defer></script>



		
	</body>
</html>