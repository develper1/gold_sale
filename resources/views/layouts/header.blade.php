<head>
    <!-- Meta Data -->
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Home | Oasis Mint</title>
    
    <!-- Favicon -->
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('landingPageAseets/assets/img/favicon.png') }}">
    
    <!-- Dependency Styles -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/bootstrap/css/bootstrap.min.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/feather-font/css/iconfont.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/icomoon-font/css/icomoon.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/font-awesome/css/font-awesome.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/wpbingofont/css/wpbingofont.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/elegant-icons/css/elegant.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/slick/css/slick.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/slick/css/slick-theme.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/mmenu/css/mmenu.min.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/slider/css/jslider.css') }}">
    <!-- Site Stylesheet -->
    <link rel="stylesheet" href="{{ asset('assets/css/app.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('assets/css/responsive.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}" type="text/css">
    
    <!-- Google Web Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900;1,100;1,300;1,400;1,700;1,900&display=swap" rel="stylesheet">
    
    <!-- jQuery and other scripts -->
     <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    {{-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>  --}}
    {{-- <script src="{{ asset('assets/vendor/libs/jquery/js/jquery.min.js') }}"></script> --}}
    <script src="{{ asset('assets/js/app.js') }}"></script>
    
    @stack('styles')
</head>