<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Oasismint</title>
        <!-- Favicon-->
        <link rel="icon" type="image/x-icon" href="assets/favicon.ico" />
        <!-- Bootstrap Icons-->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css" rel="stylesheet" />
        <!-- Google fonts-->
        <link href="https://fonts.googleapis.com/css?family=Merriweather+Sans:400,700" rel="stylesheet" />
        <link href="https://fonts.googleapis.com/css?family=Merriweather:400,300,300italic,400italic,700,700italic" rel="stylesheet" type="text/css" />
        <!-- SimpleLightbox plugin CSS-->
        <link href="https://cdnjs.cloudflare.com/ajax/libs/SimpleLightbox/2.1.0/simpleLightbox.min.css" rel="stylesheet" />
        <!-- Core theme CSS (includes Bootstrap)-->
         <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0EvHe/X+R7Yk6pU5NnpLq39kgk0KAmU7/yoaWYoZ0YBj+pZBya8M4DTh0I+0zjHb" crossorigin="anonymous">
        <link rel="stylesheet" href="{{ asset('landingPageAseets/css/styles.css') }}" />
        <style>
            .logo-img {
                max-width: 300px;
                width: 100%;
                margin-bottom: 32px;
            }
        </style>
    </head>
    <body id="page-top">
        
        <!-- Masthead-->
        <header class="masthead">
            <div class="container px-4 px-lg-5 h-100">
                <div class="row gx-4 gx-lg-5 h-100 align-items-center justify-content-center text-center">
                    <div class="col-lg-8 align-self-end">
                        <img src="landingPageAseets/assets/img/logo.png" alt="Samson Armory Logo" class="logo-img mb-4">
                        <h1 class="text-white font-weight-bold mt-5">We're almost there!</h1>
                        <hr class="divider" />
                        <p class="text-white">Oasismint is opening soon in Brooklyn, New York!</p>
                        <p class="text-white mt-4">And we want you to be the first to know.</p>
                    </div>
                    <div class="col-lg-8 align-self-baseline mt-4">
                        <form id="email-form">
                            <div class="row" id="email-form-row">
                                <div class="col-md-8 mt-3">
                                    <div class="form-floating">
                                        <input class="form-control" required id="email" type="email" placeholder="Enter your email..." data-sb-validations="required" />
                                        <label for="email">Enter your email</label>
                                    </div>
                                </div>
                                <div class="col-md-4 mt-3">
                                    <button class="btn btn-primary btn-xl w-100" id="submitButton" type="submit">Next</button>
                                </div>
                            </div>
                            <div class="row mt-4" id="show-thankyou-message" style="display: none">
                                <div class="col-md-12">
                                    <h5 class="text-white">Thank you for your submission. Do you have another 30 seconds to get special promotions and pricing? <a href="#detailed-form" class="show-detailed-form" style="cursor: pointer;">Click HERE</a></h5>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </header>
       
        <!-- Call to action-->
        <section class="page-section bg-dark text-white pt-5" id="detailed-form" style="display: none">
            <div class="container px-4 px-lg-5">
                <h2 class="mb-4 text-center">Complete Your Profile</h2>
                <form id="detail-form">
                    <div class="row justify-content-center">
                        <div class="col-md-8">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input class="form-control" id="first_name" name="first_name" type="text" placeholder="First Name" required />
                                        <label class="text-dark" for="first_name">First Name</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input class="form-control" id="last_name" name="last_name" type="text" placeholder="Last Name" required />
                                        <label class="text-dark" for="last_name">Last Name</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input class="form-control" id="city" name="city" type="text" placeholder="City of Residence" required />
                                        <label class="text-dark" for="city">City of Residence</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input class="form-control" id="detail_email" name="email" type="email" placeholder="Email address" required />
                                        <label class="text-dark" for="detail_email">Email address</label>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="text-white" class="form-label text-white">Investment:</label>
                                <div class="invalid-feedback">
                                    Please select at least one investment type.
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="investment_type[]" value="gold" id="gold">
                                    <label class="form-check-label" for="gold">Gold</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="investment_type[]" value="silver" id="silver">
                                    <label class="form-check-label" for="silver">Silver</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="investment_type[]" value="platinum" id="platinum">
                                    <label class="form-check-label" for="platinum">Platinum</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="investment_type[]" value="all" id="all">
                                    <label class="form-check-label" for="all">All</label>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label  class="form-label text-white">Investment Criteria:</label>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="investment_criteria" value="1000-10000" id="criteria_1" required>
                                    <label class="form-check-label" for="criteria_1">$1,000-$10,000</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="investment_criteria" value="10001-20000" id="criteria_2" required>
                                    <label class="form-check-label" for="criteria_2">$10,001-$20,000</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="investment_criteria" value="20000+" id="criteria_3" required>
                                    <label class="form-check-label" for="criteria_3">$20,000+</label>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="text-white">Want us to be in touch with you to start investing now?</label>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="contact_preference" value="yes" id="contact_yes" required>
                                    <label class="form-check-label" for="contact_yes">Yes</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="contact_preference" value="no" id="contact_no" required>
                                    <label class="form-check-label" for="contact_no">No</label>
                                </div>
                            </div>

                            <div class="form-floating mb-3" id="mobile_number_container" style="display: none;">
                                <input class="form-control" id="mobile_number" name="mobile_number" type="tel" placeholder="Mobile Number" />
                                <label class="text-dark" for="mobile_number">Mobile Number</label>
                            </div>

                            <div class="alert alert-success mb-3" id="detail-success-message" style="display: none;">
                                Thank you for completing your profile! We will contact you soon.
                            </div>

                            <button class="btn btn-primary btn-xl" type="submit">Submit</button>
                        </div>
                    </div>
                </form>
            </div>
        </section>

        <!-- Footer-->
        <footer class="bg-light py-5">
            <div class="container px-4 px-lg-5"><div class="small text-center text-muted">Copyright &copy; 2025 - <a href="https://www.oasismint.com" target="_blank">Oasismint</a>  </div></div>
        </footer>
        <!-- Bootstrap core JS-->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
        <!-- jQuery -->
        <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
        <!-- SimpleLightbox plugin JS-->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/SimpleLightbox/2.1.0/simpleLightbox.min.js"></script>
        <!-- Core theme JS-->
        <script src="js/scripts.js"></script>
        <!-- * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *-->
        <!-- * *                               SB Forms JS                               * *-->
        <!-- * * Activate your form at https://startbootstrap.com/solution/contact-forms * *-->
        <!-- * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *-->
        <script src="https://cdn.startbootstrap.com/sb-forms-latest.js"></script>

        <script>
            $(document).ready(function() {
                // Add click handler for the detailed form link
                $('.show-detailed-form').on('click', function(e) {
                    e.preventDefault();
                    $('#detailed-form').show();
                    $('html, body').animate({
                        scrollTop: $('#detailed-form').offset().top
                    }, 1000);
                });

                $('#email-form').on('submit', function(e) {
                    e.preventDefault();
                    
                    $.ajax({
                        url: '{{ route("subscriber.store") }}',
                        method: 'POST',
                        data: {
                            email: $('#email').val(),
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            if(response.status === 'success') {
                                $('#email-form-row').hide();
                                $('#show-thankyou-message').show();
                                // Auto-fill the detail form email field
                                $('#detail_email').val($('#email').val());
                                $('#email').val('');
                            }
                        },
                        error: function(xhr) {
                            if(xhr.status === 422) {
                                alert(xhr.responseJSON.message);
                            } else {
                                alert('Something went wrong. Please try again.');
                            }
                        }
                    });
                });
                
                $('#detail-form').on('submit', function(e) {
                    e.preventDefault();
                    
                    // Check if at least one investment type is selected
                    var investmentTypes = $('input[name="investment_type[]"]:checked').length;
                    if (investmentTypes === 0) {
                        $('.invalid-feedback').addClass('d-block');
                        return false;
                    } else {
                        $('.invalid-feedback').removeClass('d-block');
                    }
                    
                    $.ajax({
                        url: '{{ route("subscriber.storeDetail") }}',
                        method: 'POST',
                        data: {
                            first_name: $('#first_name').val(),
                            last_name: $('#last_name').val(),
                            city: $('#city').val(),
                            email: $('#detail_email').val(),
                            investment_type: $('input[name="investment_type[]"]:checked').map(function() {
                                return this.value;
                            }).get(),
                            investment_criteria: $('input[name="investment_criteria"]:checked').val(),
                            contact_preference: $('input[name="contact_preference"]:checked').val(),
                            mobile_number: $('#mobile_number').val(),
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            if(response.status === 'success') {
                                $('#detail-success-message').show();
                                $('#detail-form')[0].reset();
                                $('#mobile_number_container').hide();
                                $('html, body').animate({
                                    scrollTop: $('#detail-success-message').offset().top - 100
                                }, 1000);
                            }
                        },
                        error: function(xhr) {
                            if(xhr.status === 422) {
                                alert(xhr.responseJSON.message);
                            } else {
                                alert('Something went wrong. Please try again.');
                            }
                        }
                    });
                });

                $('input[name="contact_preference"]').change(function() {
                    if ($(this).val() === 'yes') {
                        $('#mobile_number_container').show();
                        $('#mobile_number').prop('required', true);
                    } else {
                        $('#mobile_number_container').hide();
                        $('#mobile_number').prop('required', false);
                    }
                });

                // Handle "All" checkbox functionality
                $('#all').change(function() {
                    if ($(this).is(':checked')) {
                        // Check all other checkboxes
                        $('input[name="investment_type[]"]').prop('checked', true);
                    } else {
                        // Uncheck all other checkboxes
                        $('input[name="investment_type[]"]').prop('checked', false);
                    }
                });

                // Handle individual checkboxes
                $('input[name="investment_type[]"]').not('#all').change(function() {
                    var allChecked = $('input[name="investment_type[]"]').not('#all').length === 
                                   $('input[name="investment_type[]"]').not('#all').filter(':checked').length;
                    $('#all').prop('checked', allChecked);
                });
            });
        </script>
    </body>
</html>
