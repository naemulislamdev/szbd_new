<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <title>
        @yield('title')
    </title>
    <meta name="meta_title" content="@yield('title')">
    <meta name="keywords" content="@yield('meta_keywords')">
    <meta name="description" content="@yield('meta_description')">

    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!--<meta name="google-site-verification" content="xOGzRa1l3C3m53eRDwIa2qAgUrrO-93lo2toQtsYbr4" />-->
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
    <link rel="apple-touch-icon" sizes="180x180"
        href="{{ asset('assets/storage/company') }}/{{ $web_config['fav_icon']->value }}">
    <link rel="icon" type="image/png" sizes="32x32"
        href="{{ asset('assets/storage/company') }}/{{ $web_config['fav_icon']->value }}">
    <!-- bootstrap icons -->
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.13.1/font/bootstrap-icons.min.css"
        integrity="sha512-t7Few9xlddEmgd3oKZQahkNI4dS6l80+eGEzFQiqtyVYdvcSG2D3Iub77R20BdotfRPA9caaRkg1tyaJiPmO0g=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- Font Awesome cdn link -->
    <link rel="stylesheet" href="{{ asset('assets/frontend') }}/css/font-awesome.min.css" />
    <link rel="stylesheet" href="{{ asset('assets/frontend') }}/css/animate.min.css" />
    <link rel="stylesheet" href="{{ asset('assets/frontend') }}/css/xzoom.min.css" />
    <!-- Owl-carosul css cdn link -->
    <link rel="stylesheet" href="{{ asset('assets/default') }}/toastr/toastr.min.css" />
    <link rel="stylesheet" href="{{ asset('assets/frontend') }}/css/owl.carousel.min.css" />

    <link rel="stylesheet" href="{{ asset('assets/frontend') }}/css/owl.theme.default.min.css" />
    <!-- Bootstrap css -->
    <link rel="stylesheet" href="{{ asset('assets/frontend') }}/css/bootstrap_v4.min.css">
    <link rel="stylesheet" href="{{ asset('assets/frontend') }}/css/simple-lightbox.min.css" />
    <link rel="stylesheet" href="{{ asset('assets/frontend') }}/css/bs_customize.css">
    <link rel="stylesheet" href="{{ asset('assets/frontend/css/user_account.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/frontend/css/custome.css') }}">
    <!--<link rel="stylesheet" href="{{ asset('assets/frontend') }}/css/style.css">-->
    <link rel="stylesheet" href="{{ asset('assets/frontend') }}/css/responsive.css">

    {{-- dont touch this --}}
    @stack('css_or_js')
    <meta name="_token" content="{{ csrf_token() }}">
    <style>
        td.order_status {
            display: inline-block;
            width: 120px;
        }

        .v-color-box input,
        .v-size-box input {
            display: none;
        }

        .v-color-box,
        .v-size-box {
            display: flex;
            align-items: center;
            width: 70px;
            height: 30px !important;
            margin-top: 0px;
            margin-right: 10px;
        }

        .v-color-box>.color-label,
        .v-size-box>.size-label {
            cursor: pointer;
            border: 4px solid #ccc;
            padding: 2px 6px !important;
            border-radius: 5px;
            width: 100%;
            text-align: center;
            /* height: 30px !important; */
            position: relative;
        }

        .v-color-box>input:checked+.color-label,
        .v-size-box>input:checked+.size-label {
            border: 4px solid #02ab16 !important;
        }

        .v-size-box>input:checked+.size-label::after {
            content: '✓';
            position: absolute;
            color: green !important;
            font-size: 28px;
            top: 51% !important;
            left: 77% !important;
            font-weight: bolder;
            transform: translate(-73%, -50%) rotate(7deg) !important;
        }

        .cs_header_number_wrap {
            position: relative;
            padding-left: 50px;
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            -webkit-box-orient: vertical;
            -webkit-box-direction: normal;
            -ms-flex-direction: column;
            flex-direction: column;
            color: #f26d21;
        }

        .cs_header_number_wrap svg {
            position: absolute;
            left: 0;
            width: 40px;
            height: 40px;
            top: 3px;
        }

        .cs_header_number_wrap .cs_header_number {
            font-weight: 600;
            font-family: var(--primary-font);
            font-size: 26px;
            line-height: 1.1em;
        }

        .cs_header_number_wrap .cs_header_number_text {
            font-size: 12px;
            line-height: 1.5em;
            color: #fff;
        }

        .table-cart th {
            background-color: #424242;
            color: #fff;
            text-align: center
        }

        .product-box {
            box-shadow: rgba(0, 0, 0, 0.24) 0px 3px 8px;
        }

        .product-box-col-2 {
            height: 465px;
        }

        .product-box-col-3 {
            height: 680px;
        }

        .product-box-col-6 {
            height: 1235px;
        }

        .product-box-col-4 {
            height: 870px;
        }

        .product-box-col-sm-6 {
            height: 520px;
        }

        .product-box-col-sm-12 {
            height: 750px;
        }

        .product-image2-col-2 {
            height: 325px
        }

        .product-box .title {

            text-align: left;
        }

        /* new header style*/
        header {
            background: #fff;
        }

        .topbar-section {
            background: #ff5d00;
        }

        .menu-area>ul>li>a {
            color: var(--text-black);
        }

        .menu-area>ul>li>a:hover {
            color: #ff5d00;
        }

        .header-icon>a>.fa,
        .slider-content-box>h3,
        .slider-content-box>h5,
        .slider-inner-btn>a {
            color: var(--text-black);
        }

        header.scrolled {
            background-color: #fff;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }

        .topbar-contact,
        .topbar-contact>a,
        header.scrolled .header-icon>a>.fa,
        header.scrolled .menu-area a,
        header.scrolled .navbar-brand {
            color: #000;
        }

        .header-logo {
            width: 210px;
        }

        @media (max-width: 768px) {
            .menu-icon {
                color: #ff5d00;
            }

            .header-icon>a>.fa {
                color: #000;
            }
        }

        @media (max-width: 1080px) {
            header.scrolled .menu-icon {
                color: #ff5d00;
            }
        }

        .chat-wrapper {
            position: fixed;
            right: 0;
            bottom: 14%;
            z-index: 9999;
            text-align: center;
            border-radius: 40px;
            padding-bottom: 10px;
            transition: all 0.3s ease;
        }

        .chat-wrapper.active {
            background-color: #fff;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.25);
        }

        .chat-toggle {
            width: 55px;
            height: 55px;
            border-radius: 50%;
            border: none;
            background: #ff5d00;
            color: #fff;
            font-size: 22px;
            cursor: pointer;
            box-shadow: rgba(0, 0, 0, 0.16) 0px 1px 4px;
            z-index: 10000;
            position: relative;
        }

        .chat-toggle:focus {
            outline: none;
            border: none;
        }

        .chat-box {
            background: #fff;
            padding: 12px;
            margin-bottom: 14px;
            border-radius: 40px 40px 0 0;
            display: flex;
            flex-direction: column;
            gap: 14px;

            opacity: 0;
            transform: translateY(10px) scale(0.95);
            pointer-events: none;
            transition: all 0.3s ease;
        }

        .chat-wrapper.active .chat-box {
            opacity: 1;
            transform: translateY(0) scale(1);
            pointer-events: auto;
        }

        .chat-item {
            width: 52px;
            height: 52px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 22px;
            text-decoration: none;
            transition: transform 0.2s;
        }

        .social-item {
            width: 52px;
            height: 52px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            color: #fff;
            font-size: 22px;
            text-decoration: none;
            transition: transform 0.2s;
        }

        .chat-item:hover {
            transform: scale(1.1);
            color: #fff;
        }

        .messenger {
            background: #0084ff;
        }

        .whatsapp {
            background: #25d366;
        }

        @media (max-width: 768px) {
            .chat-wrapper {
                left: auto !important;
                /* ignore saved left */
                right: 10px !important;
                /* always right */
                bottom: 80px;
                /* adjust for toggle button */
            }

        }

        .menu-area>ul>li>a {
            padding: 0 4px;
        }

        /* slick slider style here */
        .slick-dots {
            list-style: none !important;
            display: flex;
            grid-gap: 10px;
            margin-bottom: 0;
            justify-content: center;
            position: relative;
            bottom: 6px !important;
        }

        .slick-initialized .slick-slide {
            display: flex !important;
            justify-content: center;
        }

        .slick-dots li button {
            font-size: 0 !important;
            border: none;
            outline: none !important;
        }

        .slick-dots li button:before {
            content: '•';
        }



        .slick-dots li {
            border: none;
            width: 16px;
            height: 16px;
            margin: 0 6px;
            cursor: pointer;
        }

        .slick-dots li button {
            width: 100%;
            height: 100%;
            border: none;
            padding: 0;
            background: #7f8c8d;
            border-radius: 20px;
            cursor: pointer;
            transition: all .3s ease;
        }

        /* active */
        .slick-dots li.slick-active button {
            background: #ff5d00;
            width: 30px;
        }

        /* header dropdown */
        .dropdown-menu1 {
            max-height: 75vh;
            min-height: 60vh;
            overflow-y: auto;
            overflow-x: hidden;
            scrollbar-width: thin;
            top: 56px;
            left: -260px;
            width: 1046px;
            border-radius: 0 0 15px 15px;
            box-shadow: 0 14px 28px -14px rgba(0, 0, 0, 0.25),
                0 10px 10px -10px rgba(0, 0, 0, 0.22);
        }

        .m-category-box>a>img {
            height: 50px;
            width: 50px;
            object-fit: contain;
            border-radius: 50%;
        }

        /* Chrome, Edge, Safari */
        .dropdown-menu1::-webkit-scrollbar {
            width: 2px !important;

        }

        .dropdown-menu1::-webkit-scrollbar-track {
            background: transparent;
        }

        .dropdown-menu1::-webkit-scrollbar-thumb {
            background: rgba(255, 93, 0, 0.5);
            border-radius: 10px;
        }

        .m-category-box>a>img {
            height: auto;
            width: 40px;
            object-fit: contain;
            border-radius: 12px;
        }

        .social-item {
            margin-right: .5rem !important;
            padding: 13px;
            color: #fff !important;
            border-radius: 100%;

        }


        @media (max-width: 768px) {
            .chat-wrapper {
                left: auto !important;
                /* ignore saved left */
                right: 10px !important;
                /* always right */
                bottom: 80px;
                /* adjust for toggle button */
            }

            .slick-dots {
                bottom: 5px;
            }

            .copyright-section {
                padding-bottom: 60px;
            }
        }

        #topcontrol {
            bottom: 52px !important;
        }
    </style>
    @php
        $request = request()->route()->getName();
    @endphp

    @php($google_tag_manager_id = \App\CPU\Helpers::get_business_settings('google_tag_manager_id'))
    @if ($google_tag_manager_id)
        <!-- Google Tag Manager -->
        <script>
            (function(w, d, s, l, i) {
                w[l] = w[l] || [];
                w[l].push({
                    'gtm.start': new Date().getTime(),
                    event: 'gtm.js'
                });
                var f = d.getElementsByTagName(s)[0],
                    j = d.createElement(s),
                    dl = l != 'dataLayer' ? '&l=' + l : '';
                j.async = true;
                j.src =
                    'https://www.googletagmanager.com/gtm.js?id=' + i + dl;
                f.parentNode.insertBefore(j, f);
            })(window, document, 'script', 'dataLayer', '{{ $google_tag_manager_id }}');
        </script>
        <!-- End Google Tag Manager -->
    @endif

    @php($pixel_analytices_user_code = \App\CPU\Helpers::get_business_settings('pixel_analytics'))
    <!-- Meta Pixel Code -->
    <script>
        ! function(f, b, e, v, n, t, s) {
            if (f.fbq) return;
            n = f.fbq = function() {
                n.callMethod ?
                    n.callMethod.apply(n, arguments) : n.queue.push(arguments)
            };
            if (!f._fbq) f._fbq = n;
            n.push = n;
            n.loaded = !0;
            n.version = '2.0';
            n.queue = [];
            t = b.createElement(e);
            t.async = !0;
            t.src = v;
            s = b.getElementsByTagName(e)[0];
            s.parentNode.insertBefore(t, s)
        }(window, document, 'script',
            'https://connect.facebook.net/en_US/fbevents.js');
        fbq('init', '1051858697046572');
        fbq('track', 'PageView');
    </script>
    <noscript><img height="1" width="1" style="display:none"
            src="https://www.facebook.com/tr?id=1051858697046572&ev=PageView&noscript=1" /></noscript>
    <!-- End Meta Pixel Code -->

    <!-- TikTok Pixel Code Start  New-->
    <script>
        ! function(w, d, t) {
            w.TiktokAnalyticsObject = t;
            var ttq = w[t] = w[t] || [];
            ttq.methods = ["page", "track", "identify", "instances", "debug", "on", "off", "once", "ready", "alias",
                "group", "enableCookie", "disableCookie", "holdConsent", "revokeConsent", "grantConsent"
            ], ttq.setAndDefer = function(t, e) {
                t[e] = function() {
                    t.push([e].concat(Array.prototype.slice.call(arguments, 0)))
                }
            };
            for (var i = 0; i < ttq.methods.length; i++) ttq.setAndDefer(ttq, ttq.methods[i]);
            ttq.instance = function(t) {
                for (
                    var e = ttq._i[t] || [], n = 0; n < ttq.methods.length; n++) ttq.setAndDefer(e, ttq.methods[n]);
                return e
            }, ttq.load = function(e, n) {
                var r = "https://analytics.tiktok.com/i18n/pixel/events.js",
                    o = n && n.partner;
                ttq._i = ttq._i || {}, ttq._i[e] = [], ttq._i[e]._u = r, ttq._t = ttq._t || {}, ttq._t[e] = +new Date,
                    ttq._o = ttq._o || {}, ttq._o[e] = n || {};
                n = document.createElement("script");
                n.type = "text/javascript", n.async = !0, n.src = r + "?sdkid=" + e + "&lib=" + t;
                e = document.getElementsByTagName("script")[0];
                e.parentNode.insertBefore(n, e)
            };


            ttq.load('D2I4AAJC77U9R4VI8UOG');
            ttq.page();
        }(window, document, 'ttq');
    </script>
    <!-- TikTok Pixel Code End -->

    <!-- Google tag (gtag.js) new added -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=AW-382728824"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());

        gtag('config', 'AW-382728824');
    </script>


    <!-- Google Tag Manager -->
    <script>
        (function(w, d, s, l, i) {
            w[l] = w[l] || [];
            w[l].push({
                'gtm.start': new Date().getTime(),
                event: 'gtm.js'
            });
            var f = d.getElementsByTagName(s)[0],
                j = d.createElement(s),
                dl = l != 'dataLayer' ? '&l=' + l : '';
            j.async = true;
            j.src =
                'https://www.googletagmanager.com/gtm.js?id=' + i + dl;
            f.parentNode.insertBefore(j, f);
        })(window, document, 'script', 'dataLayer', 'GTM-WQ6FD77Z');
    </script>
    <!-- End Google Tag Manager -->


</head>
<!-- Body-->

<body class="toolbar-enabled">
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-WQ6FD77Z" height="0" width="0"
            style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->
    <section class="topbar-section">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <div class="d-flex align-items-center">
                        <img style="width: 40px;" src="{{ asset('assets/frontend/images/logo/whatsapp.png') }}"
                            alt="whatsapp icon">
                        <div class="ml-2">
                            <a class=" text-white text-small d-block" target="_blank" title="Go Whatsapp"
                                style="font-size: 15px; font-weight: 600"
                                href="https://wa.me/8801406667669?text=Is%20anyone%20available%20to%20chat%3F">
                                01406-667669
                            </a>
                            <a class=" text-white text-small" target="_blank" title="Go Whatsapp"
                                style="font-size: 15px; font-weight: 600 "
                                href="https://wa.me/8801805035050?text=Is%20anyone%20available%20to%20chat%3F">
                                01805-035050
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="topbar-left">
                        <div class="topbar-box mt-2">
                            <ul>
                                <li class="nav-item"><a class="btn btn-sm btn-warning text-white" href="#">
                                        Complain</a></li>
                                <li class="nav-item"><a href="{{ route('track-order.index') }}">
                                        Order Track</a></li>
                                @if (auth('customer')->check())
                                    <li class="nav-item"><a href="{{ route('user-account') }}">Profile</a>
                                    </li>
                                @else
                                    <li class="nav-item"><a href="{{ route('customer.auth.login') }}">Login</a>
                                    </li>
                                @endif

                            </ul>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Navbar Electronics Store-->
    @include('web.layouts.partials._header')
    <!-- Page title-->
    <!------Search canva-->
    @include('web.layouts.partials.offcanvas')
    <!------End shopping cart canva-->
    <!------shopping cart shopping cart canva-->
    <!------shopping cart canva-->

    <div class="offcanvas offcanvas-end" tabindex="-1" id="shoppingCartOffcanvas"
        aria-labelledby="offcanvaShoppingCard">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="offcanvaShoppingCard">SHOPPING CART</h5>
            <i class="fa fa-close offcanvasClose" data-bs-dismiss="offcanvas" aria-label="Close"></i>
        </div>
        <div class="offcanvas-body">
            <div class="row mb-3">
                <div class="col">
                    <div class="offcanva-search-title" id="cart_items">
                        @include('web.layouts.partials.cart')
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!------shopping cart shopping cart canva-->

    {{-- loader --}}
    <div class="row">
        <div class="col-12" style="margin-top:10rem;position: fixed;z-index: 9999;">
            <div id="loading" style="display: none;">
                <center>
                    <img width="200"
                        src="{{ asset('assets/storage/company') }}/{{ \App\CPU\Helpers::get_business_settings('loader_gif') }}"
                        onerror="this.src='{{ asset('assets/frontend/img/loader.gif') }}'">
                </center>
            </div>
        </div>
    </div>
    {{-- loader --}}
    <!-- Multi social start-->
    <div class="chat-wrapper draggable d-none d-lg-block" id="chat-wrapper">
        <div class="chat-box" id="chatBox">
            <a title="Messenger" href="https://m.me/shoppingzonebd300" target="_blank" class="chat-item messenger">
                <i class="bi bi-messenger"></i>
            </a>
            <a title="WhatsApp" href="https://wa.me/8801406667669?text=Is%20anyone%20available%20to%20chat%3F"
                target="_blank" class="chat-item whatsapp">
                <i class="bi bi-whatsapp"></i>
            </a>
        </div>

        <button class="chat-toggle" id="chatToggle">
            <i class="bi bi-chat-right-text"></i>
        </button>
    </div>

    <!-- Page Content-->
    @yield('content')

    {{-- Bottom Social Bar --}}
    <div style="position: fixed; left: 0; bottom: -2px; width: 100%; height: auto; z-index: 9999; box-shadow: rgba(14, 30, 37, 0.12) 0px 2px 4px 0px, rgba(14, 30, 37, 0.32) 0px 2px 16px 0px;"
        class="bottom-bar bg-white shadow py-1 rounded-top d-block d-md-none">
        <div class="container">
            <div class="d-flex justify-content-around align-items-center">
                <div class="position-relative">
                    <a style="height: 45px; width: 45px;" title="WhatsApp"
                        href="https://wa.me/8801406667669?text=Is%20anyone%20available%20to%20chat%3F" target="_blank"
                        class="chat-item whatsapp">
                        <i class="bi bi-whatsapp"></i>
                    </a>
                </div>
                <div class="position-relative">
                    <a style="height: 45px; width: 45px;" title="Messenger" href="https://m.me/shoppingzonebd300"
                        target="_blank" class="chat-item messenger">
                        <i class="bi bi-messenger"></i>
                    </a>
                </div>

                <a class="mr-2  bg-primary social-item" href="{{ route('wishlists') }}"><i class="fa fa-heart-o"
                        aria-hidden="true"></i>
                    <span style="right: 185px; top: -6px"
                        class="badge badge-danger countWishlist">{{ session()->has('wish_list') ? count(session('wish_list')) : 0 }}</span>
                </a>


                <a class="mr-2 social-item  bg-primary" data-bs-toggle="offcanvas" href="#shoppingCartOffcanvas"
                    role="button" aria-controls="shoppingCartOffcanvas"><i class="fa fa-shopping-cart"
                        aria-hidden="true"></i>

                    <span style="right: 102px; top: -4px;" class="badge badge-danger total_cart_count"
                        id="total_cart_count">
                        {{ session()->has('cart') ? count(session()->get('cart')) : 0 }}
                    </span>
                </a>


                @if (auth('customer')->check())
                    <a href="{{ route('user-account') }}" class=" social-item bg-primary"><i class="fa fa-user"
                            aria-hidden="true"></i></a>
                @else
                    <a href="{{ route('customer.auth.login') }}" class=" social-item bg-primary"><i
                            class="fa fa-user" aria-hidden="true"></i></a>
                @endif

            </div>
        </div>
    </div>
    <!-- Footer-->
    @include('web.layouts.partials._footer')

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js"></script>
    <script src="{{ asset('assets/frontend') }}/js/bootstrap_v4.min.js"></script>
    <script src="{{ asset('assets/frontend') }}/js/bs_v5.js"></script>
    <!-- Owl-carosul js file cdn link -->
    <script src="{{ asset('assets/frontend') }}/js/owl.carousel.min.js"></script>
    <script src="{{ asset('assets/frontend') }}/js/owl-extra-code.js"></script>
    <script src="{{ asset('assets/frontend') }}/js/wow.min.js"></script>
    <script src="{{ asset('assets/frontend') }}/js/xzoom.min.js"></script>
    <script src="{{ asset('assets/frontend') }}/js/xzoom_setup.js"></script>
    <script src="{{ asset('assets/frontend') }}/js/spartan-multi-image-picker-min.js"></script>
    <script src="{{ asset('assets/frontend') }}/js/scrolltotop.js"></script>
    <!--<script src="https://unpkg.com/interactjs/dist/interact.min.js"></script>-->
    <script src="{{ asset('assets/frontend') }}/js/sweet_alert.js"></script>
    {{-- Toastr --}}
    <script src={{ asset('assets/default/toastr/toastr.min.js') }}></script>
    @if (Session::has('success'))
        <script>
            toastr.success("{{ Session::get('success') }}")
        </script>
    @endif
    @if (Session::has('error'))
        <script>
            toastr.error("{{ Session::get('error') }}")
        </script>
    @endif
    @if (Session::has('info'))
        <script>
            toastr.info("{{ Session::get('info') }}")
        </script>
    @endif
    @if (Session::has('warning'))
        <script>
            toastr.warning("{{ Session::get('warning') }}")
        </script>
    @endif

    <!-- multi social toggle and drag and drop-->
    <script src="{{ asset('assets/frontend') }}/js/custome.js"></script>

    <script>
        var mainurl = "{{ url('/') }}";
        //console.log(mainurl);

        new WOW().init();
    </script>

    <script src="https://ai.szbdfinancing.com/static/js/product-sdk.js"></script>
    <script>
        function addWishlist(product_id) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                }
            });
            $.ajax({
                url: "{{ route('store-wishlist') }}",
                method: 'POST',
                data: {
                    product_id: product_id
                },
                success: function(data) {
                    if (data.value == 1) {
                        Swal.fire({
                            position: 'top-end',
                            type: 'success',
                            title: data.success,
                            showConfirmButton: false,
                            timer: 1500
                        });
                        $('.countWishlist').html(data.count);
                        $('.countWishlist-' + product_id).text(data.product_count);
                        $('.tooltip').html('');
                        /*$('.wishlist' + data.id).html('<button type="button" class="btn" title="Add to wishlist" onclick="addWishlist(' + data.id + ')" style="background-color: transparent ;font-size: 18px; height: 45px; color: #9E9E9E; border: 2px solid #9E9E9E;">' +
                            '                       <i class="fa fa-heart-o mr-2" aria-hidden="true"></i>' +
                            '                   </button>');*/
                        // Product AI API integration
                        const productAPI = new ProductAPI("https://ai.szbdfinancing.com");
                        async function loadProduct() {
                            const product = await productAPI.analyzeProduct(product_id, "view");
                            console.log(product);
                        }

                        loadProduct();

                    } else if (data.value == 2) {
                        Swal.fire({
                            type: 'info',
                            title: 'WishList',
                            text: data.error
                        });
                    } else {
                        Swal.fire({
                            type: 'error',
                            title: 'WishList',
                            text: data.error
                        });
                    }
                }
            });
        }

        function removeWishlist(product_id) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                }
            });
            $.ajax({
                url: "{{ route('delete-wishlist') }}",
                method: 'POST',
                data: {
                    id: product_id
                },
                beforeSend: function() {
                    $('#loading').show();
                },
                success: function(data) {
                    Swal.fire({
                        type: 'success',
                        title: 'WishList',
                        text: data.success
                    });
                    $('.countWishlist').html(data.count);
                    $('#set-wish-list').html(data.wishlist);
                    $('.tooltip').html('');
                    /*$('.wishlist' + data.id).html('<button type="button" class="btn" title="Add to wishlist" onclick="addWishlist(' + data.id + ')" style="background-color: transparent ;font-size: 18px; height: 45px; color: #9E9E9E; border: 2px solid #9E9E9E;">' +
                        '                       <i class="fa fa-heart-o mr-2" aria-hidden="true"></i>' +
                        '                   </button>');*/
                },
                complete: function() {
                    $('#loading').hide();
                },
            });
        }

        function addToCart(form_id, redirect_to_checkout = false) {

            if (!form_id) {
                Swal.fire('Error', 'Something went wrong!', 'error');
                return;
            }

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                }
            });

            $.ajax({
                url: "{{ route('cart.add') }}",
                method: "POST",
                data: $('#' + form_id).serialize(),
                beforeSend: function() {
                    $('#loading').show();
                },
                success: function(response) {

                    $('#loading').hide();

                    // ❌ Out of stock
                    if (response.data === 0) {
                        Swal.fire('Stock Out', 'Sorry, product out of stock!', 'error');
                        return;
                    }

                    // ⚠ Already in cart
                    if (response.data === 1) {
                        Swal.fire('Info', 'Product already added in cart!', 'info')
                            .then(() => {
                                window.location.href = "{{ route('checkout') }}";
                            });
                        return;
                    }

                    // ✅ Success
                    toastr.success('Item has been added to your cart');

                    $('.total_cart_count').text(response.count);
                    updateNavCart();

                    // 🔥 GA4 DATALAYER
                    if (response.product) {
                        window.dataLayer = window.dataLayer || [];
                        dataLayer.push({
                            ecommerce: null
                        }); // clear previous

                        dataLayer.push({
                            event: "add_to_cart",
                            ecommerce: {
                                currency: "BDT",
                                value: (response.product.price * response.product.quantity).toFixed(2),
                                items: [{
                                    item_id: response.product.id,
                                    item_name: response.product.name,
                                    item_brand: response.product.brand || "",
                                    item_category: response.product.category || "",
                                    item_variant: response.product.variant || "",
                                    price: parseFloat(response.product.price),
                                    quantity: parseInt(response.product.quantity)
                                }]
                            }
                        });
                    }

                    if (redirect_to_checkout) {
                        window.location.href = "{{ route('checkout') }}";
                    }
                },
                error: function() {
                    $('#loading').hide();
                    Swal.fire('Error', 'Something went wrong!', 'error');
                }
            });
        }

        function buy_now(form_id) {
            addToCart(form_id, true);
        }

        $('.new-av-product').on('click', function() {
            var product_id = $(this).data('pid');
            console.log(product_id);
            addToCart(product_id, true);
        });

        function removeFromCart(key) {

            $.post('{{ route('cart.remove') }}', {
                _token: '{{ csrf_token() }}',
                key: key
            }, function(data) {
                updateTotalCart();
                updateNavCart();
                $('#cart-summary').empty().html(data);
                toastr.info('Item has been removed from cart', {
                    CloseButton: true,
                    ProgressBar: true
                });
            });
        }

        function updateTotalCart() {
            $.post('<?php echo e(route('cart.totalCart')); ?>', {
                _token: '<?php echo e(csrf_token()); ?>'
            }, function(data) {
                $('.total_cart_count').text(data);
            });
        }

        function updateNavCart() {
            $.post('<?php echo e(route('cart.nav_cart')); ?>', {
                _token: '<?php echo e(csrf_token()); ?>'
            }, function(data) {
                $('#cart_items').html(data);
            });
        }


        function popUpModalQty() {

            $(document)
                .off('click', '.btn-pm-qty') // 🔥
                .on('click', '.btn-pm-qty', function(e) {

                    e.preventDefault();

                    let type = $(this).data('type');
                    let input = $(this).closest('.input-group')
                        .find('.input-pm-number');

                    let currentVal = parseInt(input.val());
                    let min = parseInt(input.attr('min'));
                    let max = parseInt(input.attr('max'));

                    if (isNaN(currentVal)) currentVal = min;

                    if (type === 'minus' && currentVal > min) {
                        input.val(currentVal - 1);
                    }

                    if (type === 'plus' && currentVal < max) {
                        input.val(currentVal + 1);
                    }
                });
        }

        function updateQuantity(key, element) {
            $.post('<?php echo e(route('cart.updateQuantity')); ?>', {
                _token: '<?php echo e(csrf_token()); ?>',
                key: key,
                quantity: element.value
            }, function(data) {
                updateNavCart();
                $('#cart-summary').empty().html(data);
            });
        }

        function changeQty(key, change) {

            let input = $("#cartQuantity" + key);
            let currentQty = parseInt(input.val());

            let newQty = currentQty + change;

            if (newQty < 1) {
                return;
            }

            updateCartQuantity(key, newQty);
        }

        function updateCartQuantity(key, quantity) {

            $.post("{{ route('cart.updateQuantity') }}", {
                _token: "{{ csrf_token() }}",
                key: key,
                quantity: quantity
            }, function(data) {

                if (data['data'] == 0) {

                    toastr.error('Sorry, stock limit exceeded.', {
                        CloseButton: true,
                        ProgressBar: true
                    });

                    $("#cartQuantity" + key).val(data['qty']);

                } else {

                    toastr.info('Quantity updated!', {
                        CloseButton: true,
                        ProgressBar: true
                    });

                    updateNavCart();

                    $('#cart-summary').html(data);
                }

            });
        }


        $('#add-to-cart-form input').on('change', function() {
            getVariantPrice();
        });

        function getVariantPrice() {
            if ($('#add-to-cart-form input[name=quantity]').val() > 0) {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                    }
                });
                $.ajax({
                    type: "POST",
                    url: '{{ route('cart.variant_price') }}',
                    data: $('#add-to-cart-form').serializeArray(),
                    success: function(data) {
                        $('#add-to-cart-form #chosen_price_div').removeClass('d-none');
                        $('#add-to-cart-form #chosen_price_div #chosen_price').html(data.price);
                        $('#available-quantity').html(data.quantity);
                        $('.cart-qty-field').attr('max', data.quantity);
                    }
                });
            }
        }
    </script>
    <script>
        @if (Request::is('/') && \Illuminate\Support\Facades\Cookie::has('popup_banner') == false)
            $(document).ready(function() {
                $('#popup-modal').appendTo("body").modal('show');
            });
            @php(\Illuminate\Support\Facades\Cookie::queue('popup_banner', 'off', 1))
        @endif
    </script>

    @if ($errors->any())
        <script>
            @foreach ($errors->all() as $error)
                toastr.error('{{ $error }}', Error, {
                    CloseButton: true,
                    ProgressBar: true
                });
            @endforeach
        </script>
    @endif
    {{-- outlet search --}}
    <script>
        jQuery(".outlet-search-input").keyup(function() {
            let name = $(this).val();

            if (name.length > 1) {
                $(".search-card").show();

                $.get({
                    url: "{{ route('outlet.search') }}",
                    dataType: "json",
                    data: {
                        name: name
                    },

                    beforeSend: function() {
                        $('#loading').show();
                    },

                    success: function(data) {
                        $('.search-result-box').html(data.result);
                    },

                    complete: function() {
                        $('#loading').hide();
                    }
                });
            } else {
                $('.search-result-box').empty();
                $(".search-card").hide(); // important
            }
        });
    </script>
    <script>
        function couponCode() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                }
            });
            $.ajax({
                type: "POST",
                url: '{{ route('coupon.apply') }}',
                data: $('#coupon-code-ajax').serializeArray(),
                success: function(data) {
                    if (data.status == 1) {
                        let ms = data.messages;
                        ms.forEach(
                            function(m, index) {
                                toastr.success(m, index, {
                                    CloseButton: true,
                                    ProgressBar: true
                                });
                            }
                        );
                    } else {
                        let ms = data.messages;
                        ms.forEach(
                            function(m, index) {
                                toastr.error(m, index, {
                                    CloseButton: true,
                                    ProgressBar: true
                                });
                            }
                        );
                    }
                    setInterval(function() {
                        location.reload();
                    }, 2000);
                }
            });
        }
    </script>
    <script>
        $('.inline_product').click(function() {
            window.location.href = $(this).data('href');
        })
    </script>
    <script>
        jQuery(".search-bar-input").keyup(function() {
            $(".search-card").css("display", "block");
            let name = $(".search-bar-input").val();
            if (name.length > 0) {
                $.get({
                    url: '{{ url('/') }}/searched-products',
                    dataType: 'json',
                    data: {
                        name: name
                    },
                    beforeSend: function() {
                        $('#loading').show();
                    },
                    success: function(data) {
                        $('.search-result-box').empty().html(data.result)
                    },
                    complete: function() {
                        $('#loading').hide();
                    },
                });
            } else {
                $('.search-result-box').empty();
            }
        });

        jQuery(".search-bar-input-mobile").keyup(function() {
            $(".search-card").css("display", "block");
            let name = $(".search-bar-input-mobile").val();
            if (name.length > 0) {
                $.get({
                    url: '{{ url('/') }}/searched-products',
                    dataType: 'json',
                    data: {
                        name: name
                    },
                    beforeSend: function() {
                        $('#loading').show();
                    },
                    success: function(data) {
                        $('.search-result-box').empty().html(data.result)
                    },
                    complete: function() {
                        $('#loading').hide();
                    },
                });
            } else {
                $('.search-result-box').empty();
            }
        });

        jQuery(document).mouseup(function(e) {
            var container = $(".search-card");
            if (!container.is(e.target) && container.has(e.target).length === 0) {
                container.hide();
            }
        });
    </script>
    {{-- products search --}}
    <script>
        $(document).ready(function() {
            $('#searchInput').on('keyup', function() {
                var query = $(this).val();
                if (query.length >= 2) { // Start searching after 2 characters
                    $.ajax({
                        url: "{{ route('product_search') }}", // The route that handles search
                        type: "GET",
                        data: {
                            'query': query
                        },
                        success: function(data) {
                            // console.log(data);
                            if (data.products) {

                                $('#searchResultProducts').html(data
                                    .products); // Display the results
                            } else {
                                $('#searchResultProducts').html('');
                            }
                            if (data.categories) {
                                $('#searchResultCategories').html(data.categories);
                            } else {
                                $('#searchResultCategories').html('');
                            }

                        }
                    });
                }
            });
        });
    </script>
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-CMPYP8JY4C"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());

        gtag('config', 'G-CMPYP8JY4C');
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const lazyImages = document.querySelectorAll(".lazy-image");

            const imageObserver = new IntersectionObserver((entries, observer) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const img = entry.target;
                        img.src = img.dataset.src; // load actual image
                        img.classList.add("loaded");
                        observer.unobserve(img);
                    }
                });
            });

            lazyImages.forEach(img => {
                imageObserver.observe(img);
            });
        });
    </script>
    <script>
        $(document).ready(function() {

            // When any add-to-cart modal is shown
            $('.modal').on('shown.bs.modal', function() {
                let modal = $(this);
                let mainImage = modal.find('.main-image');

                if (!mainImage.length) return;

                // Click on color image
                modal.find('.color-label img').off('click').on('click', function() {

                    let newSrc = $(this).data('image');
                    if (!newSrc) return;

                    mainImage.fadeOut(150, function() {
                        mainImage.attr('src', newSrc).fadeIn(150);
                    });

                });
            });

        });
    </script>

    @stack('scripts')
</body>

</html>
