<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Gametech</title>
    @if(isset($page) && $page !== null)
    {!! seo()->for($page) !!}
    @endif
    @if(isset($section) && $section !== null)
    {!! seo()->for($section) !!}
    @endif
    @if(isset($product) && $product !== null)
    {!! seo()->for($product) !!}
    @endif
    @if(isset($ogImage))
    <meta property="og:image" content="{{ $ogImage }}">
    @else
    <meta property="og:image" content="{{ asset('storage/icons/logo-removebg-preview.png') }}">
    @endif
     <!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
    new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
    j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
    'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
    })(window,document,'script','dataLayer','GTM-WV8WXMFX');</script>
    <!-- End Google Tag Manager -->

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="sitemap" type="application/xml" title="Sitemap" href="../../sitemap.xml">
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('storage/icons/logo-removebg-preview.png') }}">
    <link rel="stylesheet" href="{{ asset('website/css/material-design-iconic-font.min.css') }}">
    <link rel="stylesheet" href="{{ asset('website/css/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('website/css/fontawesome-stars.css') }}">
    <link rel="stylesheet" href="{{ asset('website/css/meanmenu.css') }}">
    <link rel="stylesheet" href="{{ asset('website/css/owl.carousel.min.css') }}">
    <link rel="stylesheet" href="{{ asset('website/css/slick.css') }}">
    <link rel="stylesheet" href="{{ asset('website/css/animate.css') }}">
    <link rel="stylesheet" href="{{ asset('website/css/jquery-ui.min.css') }}">
    <link rel="stylesheet" href="{{ asset('website/css/venobox.css') }}">
    <link rel="stylesheet" href="{{ asset('website/css/nice-select.css') }}">
    <link rel="stylesheet" href="{{ asset('website/css/magnific-popup.css') }}">
    <link rel="stylesheet" href="{{ asset('website/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('website/css/helper.css') }}">
    <link rel="stylesheet" href="{{ asset('website/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('website/css/responsive.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/slick.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/slick-theme.css') }}">
    <script src="{{ asset('website/js/vendor/modernizr-2.8.3.min.js') }}"></script>
</head>