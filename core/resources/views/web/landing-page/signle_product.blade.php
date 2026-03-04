@extends('web.layouts.app')
@section('title', 'Welcome To' . ' ' . $web_config['name']->value)

@push('css_or_js')
    <meta property="og:image" content="{{ asset('storage/company') }}/{{ $web_config['web_logo']->value }}" />
    <meta property="og:title" content="Best Online Marketplace In Bangladesh {{ $web_config['name']->value }} Home" />
    <meta property="og:url" content="{{ env('APP_URL') }}">
    <meta property="og:description" content="{!! substr(strip_tags($web_config['about']->value), 0, 100) !!}">

    <meta property="twitter:card" content="{{ asset('storage/company') }}/{{ $web_config['web_logo']->value }}" />
    <meta property="twitter:title" content="Welcome To {{ $web_config['name']->value }} Home" />
    <meta property="twitter:url" content="{{ env('APP_URL') }}">
    <meta property="twitter:description" content="{!! substr(strip_tags($web_config['about']->value), 0, 100) !!}">
    {{-- <link rel="stylesheet" href="{{ asset('assets/front-end/css/bangla-font.css') }}"> --}}
    <link href="https://fonts.maateen.me/solaiman-lipi/font.css" rel="stylesheet">
    <style>
        /*---End Bangla fonts*/
        body {
            font-family: 'SolaimanLipi', sans-serif !important;
        }

        p,
        span,
        h1,
        h2,
        h3,
        h4,
        h5,
        h6,
        label {
            font-family: 'SolaimanLipi', sans-serif !important;
            font-weight: 400
        }

        .product-image {
            width: 100%;
            max-width: 300px;
            margin: 0 auto;
        }

        .video-section,
        .benefit-section,
        .use-section {
            margin-top: 40px;
        }

        .benefit-list li {
            list-style: none;
            border-bottom: 1px solid #ddd;
            line-height: 34px;
        }

        .benefit-list li i {
            color: #0bc508;
            margin-right: 10px;
        }

        .order-btn {
            background: #f26d21;
            display: block;
            padding: 15px 20px;
            border-radius: 7px;
            color: #fff;
            text-align: center;
            font-weight: 700;
            font-size: 20px;
            transition: 0.3s;
            font-family: 'SolaimanLipi', sans-serif !important;
        }

        .order-btn:hover {
            background: #ac4103;
            color: #fff;
            text-decoration: none;
        }

        .slider-img {
            height: 550px;
        }

        .slider-img>img {
            width: 100%;
            height: 100%;
        }

        .section-title {
            background: #f26d21;
            padding: 7px 7px;
            border-radius: 7px;
            margin-bottom: 10px;
        }

        .section-title>h4 {
            color: #fff;
            font-size: 30px;
            font-family: 'SolaimanLipi', sans-serif !important;
        }

        .name-price {
            background: #fffdfd;
            padding: 11px 13px;
            border-radius: 7px;
            border: 2px dashed #f26d21;
            box-shadow: rgba(149, 157, 165, 0.2) 0px 8px 24px;
        }

        .name-price>.title-box {
            border-bottom: 2px solid #fff;
        }

        .p-data-box>p {
            font-weight: 500;
        }

        .p-data-box>img {
            width: 200px;
        }

        .order-box {
            box-shadow: rgba(149, 157, 165, 0.2) 0px 8px 24px;
        }

        .sp-right-img {
            width: 100px;
        }

        .p-dtls-box>table>tbody>tr {
            display: flex;
            justify-content: space-between;
            border: 1px solid #ddd;
        }

        .p-type-box {
            background: #f4f4f4;
            padding: 10px 10px;
            margin-bottom: 7px;
            border-radius: 7px;
        }

        .p-type-box>h4 {
            font-size: 16px;
            font-weight: 600;
        }

        .p-type-box>p {
            margin: 0;
            background: #ddd;
            padding: 8px 8px;
        }

        #preloader img {
            vertical-align: middle;
        }

        #preloader {
            display: inline-block;
            margin-left: 10px;
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
            border: 2px solid #ccc;
            padding: 2px 6px !important;
            border-radius: 5px;
            width: 100%;
            text-align: center;
            height: 30px !important;
            position: relative;
        }

        .v-color-box>input:checked+.color-label {
            border: 2px solid #02ab16 !important;
        }

        .v-color-box>input:checked+.color-label::after {
            content: '✔';
            color: white;
            font-size: 12px;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }

        .sp-price {
            font-size: 20px;
            font-weight: 600;
            color: #f26d21;
            font-family: 'Jost';
        }

        .discount-price {
            font-size: 16px;
            font-weight: 600;
            color: #909090;
            font-family: 'Jost';
        }

        .table td,
        .table th {
            font-family: 'Jost', sans-serif !important;
        }

        .shipping-title {
            font-size: 16px;
            font-weight: 700;
            font-family: 'Jost' !important;
        }

        /* style change by Md. Naim.jr 22-10-2025 start*/

        .btn-primary:hover {
            background-color: #f26d21;
        }

        .btn-primary:focus {
            background-color: #f26d21;
            outline: none;
        }

        .btn-primary:active {
            background-color: #f26d21;
            outline: none;
        }

        .btn-primary:not(:disabled):not(.disabled).active,
        .btn-primary:not(:disabled):not(.disabled):active,
        .show>.btn-primary.dropdown-toggle {
            color: #fff;
            background-color: #f26d21;
            border-color: #f26d21;
            outline: none;
        }

        .slider-img>img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .p-details h3 {
            text-align: left !important;
        }

        .p-short-details h4 {
            text-align: left !important;
        }

        .p-short-details p {
            text-align: left !important;
        }

        .p-details,
        .p-details-contact-section,
        .p-image {
            padding: 30px 13px;
        }

        .name-price {
            border: none !important;
            box-shadow: none !important;
        }

        #orderPlace .card-body .order-box:last-child {
            border: 2px dashed #f26d21;
        }

        #orderPlace .btn-primary {
            position: static;
        }

        .benefit-section .benefit-img img {
            width: 100%;
            height: 100%;

        }

        .carousel-control-next {
            right: 23%;
        }

        .carousel-control-prev {
            left: 23%;
        }

        .v-size-box>input:checked+.size-label::after {
            color: green !important;
            font-size: 19px !important;
            top: 57% !important;
            left: 75% !important;
            transform: translate(-50%, -50%) rotate(10deg) !important;
        }

        .benefit-section .benefit-img {
            padding: 0 75px;
        }

        @media (max-width: 768px) {
            .lt-slider .slider-img>img {
                height: 100%;
                object-fit: contain;
                object-position: bottom center;
            }

            .benefit-section .benefit-img img {
                width: 100%;
                height: 100%;
                object-fit: contain;
            }

            .name-price {
                padding: 0 !important;
            }

            .carousel-control-next {
                right: 5%;
            }

            .carousel-control-prev {
                left: 5%;
            }

            .slider-img {
                max-height: 550px;
            }

            .benefit-section .benefit-img {
                padding: 0;
            }

        }

        /* style change by Md. Naim.jr 22-10-2025 end*/
        .p-dtls-box>table>tbody>tr {
            display: block !important;
            justify-content: space-between;
            border: 1px solid #ddd;
        }

        .shipping-box {
            border: 1px solid #ddd;
            padding: 7px;
            border-radius: 5px;
            display: flex;
            justify-content: space-evenly;
            cursor: pointer;
            align-items: center;
            transition: 0.3s all ease-in-out;
        }

        .shipping-box input[type="radio"]:checked+.shipping-title {
            font-weight: bold;
            color: #f26d21;
        }

        .v-color-box>.color-label,
        .v-size-box>.size-label {
            cursor: pointer;
            border: 2px solid #ccc;
            padding: 2px 6px !important;
            border-radius: 5px;
            width: 100%;
            text-align: center;
            height: 70px !important;
            position: relative;
        }

        .v-color-box>input:checked+.color-label::after {
            content: '✔';
            color: green;
            font-size: 30px;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }

        .v-color-box,
        .v-size-box {
            margin-right: 0.925rem !important;
        }

        .incDecBtn {
            width: 30px;
            height: 30px;
            padding: 0;
            line-height: 30px;
            text-align: center;
        }
    </style>
@endpush
@section('content')
    <!-- Header Section -->
    @php
        $customer = auth('customer')->user();
        if ($customer) {
            $shippingAddresses = \App\Models\ShippingAddress::where('customer_id', $customer->id)->get();
        }
    @endphp
    <section>
        <div class="container">
            <div class="row">
                <div class="col-lg-6">
                    <div class="lt-slider">
                        <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel"
                            data-bs-interval="2000">
                            <ol class="carousel-indicators">
                                @foreach (json_decode($productLandingPage->slider_img) as $key => $image)
                                    <li data-target="#carouselExampleIndicators" data-slide-to="{{ $key }}"
                                        class="{{ $key == 0 ? 'active' : '' }}"></li>
                                @endforeach
                            </ol>
                            <div class="carousel-inner">
                                @foreach (json_decode($productLandingPage->slider_img) as $key => $image)
                                    <div class="carousel-item {{ $key == 0 ? 'active' : '' }}">
                                        <div class="slider-img">
                                            <img class="d-block w-100"
                                                src="{{ asset('assets/storage/landingpage/slider') }}/{{ $image }}"
                                                alt="">
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button"
                                data-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="sr-only">Previous</span>
                            </a>
                            <a class="carousel-control-next" href="#carouselExampleIndicators" role="button"
                                data-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="sr-only">Next</span>
                            </a>
                        </div>
                    </div>
                </div>
                <!-- Offer Section -->
                <div class="p-details col-lg-6">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-12 text-center">
                                <div class="p-name">
                                    <h3 class="mb-2">{{ $productLandingPage->product->name }}</h3>
                                </div>
                                <div class="p-short-details">
                                    {!! $productLandingPage->description !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>



    @php
        $videoUrl = $productLandingPage->video_url;
        $embedUrl = '';
        $width = '100%'; // Default width
        $height = '530'; // Default height
        $col = '6'; // Default col

        if (strpos($videoUrl, 'facebook.com') !== false) {
            if (strpos($videoUrl, '/reel/') !== false) {
                // Facebook Reel URL
                $videoId = explode('/reel/', $videoUrl)[1];
                $videoId = explode('?', $videoId)[0];
                $embedUrl = "https://www.facebook.com/plugins/video.php?href=https://www.facebook.com/watch/?v={$videoId}";
                $height = '800'; // Facebook Reel height
                $col = '4';
            } elseif (strpos($videoUrl, 'watch?v=') !== false || strpos($videoUrl, '/watch/') !== false) {
                // Facebook Watch Video URL
                if (strpos($videoUrl, 'v=') !== false) {
                    $videoId = explode('v=', $videoUrl)[1];
                } else {
                    $videoId = explode('/watch/', $videoUrl)[1];
                }
                $videoId = explode('&', $videoId)[0]; // Remove trailing query parameters
                $embedUrl = "https://www.facebook.com/plugins/video.php?href=https://www.facebook.com/watch/?v={$videoId}";
                $height = '530'; // Facebook Watch video height
            }
        } elseif (strpos($videoUrl, 'youtube.com') !== false || strpos($videoUrl, 'youtu.be') !== false) {
            if (strpos($videoUrl, 'youtube.com/watch') !== false) {
                // Standard YouTube Video URL
                $videoId = explode('v=', $videoUrl)[1];
                $videoId = explode('&', $videoId)[0];
                $embedUrl = "https://www.youtube.com/embed/{$videoId}";
            } elseif (strpos($videoUrl, 'youtu.be') !== false) {
                // Shortened YouTube URL
                $videoId = explode('/', $videoUrl)[3];
                $videoId = explode('?', $videoId)[0];
                $embedUrl = "https://www.youtube.com/embed/{$videoId}";
            } elseif (strpos($videoUrl, '/embed/') !== false) {
                // YouTube Embed URL
                $embedUrl = $videoUrl;
            }
            // Adjust height for YouTube Shorts
            if (strpos($videoUrl, 'shorts') !== false) {
                $height = '700'; // YouTube Shorts height
                $col = '4';
            }
        }
    @endphp



    <!-- Benefit Section -->
    <section class="benefit-section">
        <div class="container">
            <div class="row">
                <div class="col">
                    <div class="section-title">
                        <h4 class="text-center" style="font-family: 'SolaimanLipi', sans-serif;">এই পণ্যের বৈশিষ্ট্য</h4>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-8 mx-auto">
                    <div class="row align-items-center justify-content-center">
                        <div class="col-md-6 mb-3">
                            @if (json_decode($productLandingPage->feature_list) != null)
                                <ul class="benefit-list">
                                    @foreach (json_decode($productLandingPage->feature_list) as $title)
                                        <li><i class="fa fa-check-square-o"></i>{{ $title }}</li>
                                    @endforeach
                                </ul>
                            @endif

                            <a href="#orderPlace" class="w-100 order-btn">অর্ডার করতে চাই</a>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="benefit-img">
                                {{-- <img src="{{ asset('landingpage/') }}"
                                    style="width: 100%; height:500px;" alt=""> --}}
                                <img src="{{ asset('storage/landingpage/' . $productLandingPage->feature_img) }}"
                                    style="width: 100%; " alt="">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Multiple section --}}
    @if ($productLandingPage->landingPageSection)
        @foreach ($productLandingPage->landingPageSection as $key => $section)
            <section class="benefit-section my-4">
                <div class="container">
                    <div class="row">
                        <div class="col">
                            <div class="section-title">
                                <h4 class="text-center">{{ $section->section_title }}</h4>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-8 mx-auto">
                            <div class="row align-items-center justify-content-center">
                                @if ($section->section_direction == 'left')
                                    <div class="col-md-6 mb-3">
                                        <div class="section-dtls">
                                            <p>{{ $section->section_description }}</p>
                                        </div>
                                        @if ($section->order_button == 1)
                                            <a href="#orderPlace" class="w-100 order-btn">অর্ডার করতে চাই</a>
                                        @endif
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <div class="benefit-img">
                                            <img src="{{ asset('storage/landingpage/' . $section->section_img) }}"
                                                style="width: 100%; height:560px;" alt="">
                                        </div>
                                    </div>
                                @elseif ($section->section_direction == 'right')
                                    <div class="col-md-6 mb-3">
                                        <div class="benefit-img">
                                            <img src="{{ asset('storage/landingpage/' . $section->section_img) }}"
                                                style="width: 100%; height:560px;" alt="">
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <div class="section-dtls">
                                            <p>{{ $section->section_description }}</p>
                                        </div>
                                        @if ($section->order_button == 1)
                                            <a href="#orderPlace" class="w-100 order-btn">অর্ডার করতে চাই</a>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        @endforeach
    @endif
    {{-- @dd(session('otp')) --}}
    <!-- Form Section -->
    <section id="orderPlace" class="my-4">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-10">
                    <div class="card mb-3">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="section-title">
                                        <h4 class="text-center">অর্ডার করতে নিচের ফর্মটি সঠিক ভাবে পুরন করুন।</h4>
                                    </div>
                                </div>
                            </div>
                            @if (!$customer && !session('otp_verified'))
                                <input type="hidden" name="session_id" id="session_id"
                                    value="{{ session()->getId() }}">
                                <div id="otpSection">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label>আপনার ফোন নাম্বার দিন <span class="text-danger">*</span></label>
                                            <div class="input-group mb-3">
                                                <input type="number" class="form-control otp-phone-save check-phone" id="otp_phone"
                                                    name="phone">
                                                <button type="button" id="send_otp" class="btn btn-info btn-sm">
                                                    ওটিপি পাঠান
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row {{ session()->has('otp') ? '' : 'd-none' }}" id="otpInputRow">
                                        <div class="col-md-6">
                                            <label>ওটিপি দিন</label>
                                            <input type="text" class="form-control" id="otp" name="otp">
                                            <button class="btn btn-success btn-sm mt-2" id="verify_otp">
                                                Verify OTP
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            <form action="{{ route('sproduct.checkout') }}" method="POST" id=""
                                class="checkoutForm userInfoForm {{ !$customer && !session('otp_verified') ? 'd-none' : '' }}">
                                @csrf
                                <input type="hidden" name="session_id" value="{{ session()->getId() }}">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="order-box">
                                            <div class="card-body">
                                                <div class="row mb-3">
                                                    <div class="col">
                                                        <h4 class="">আপনার ঠিকানা</h4>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-12">
                                                        @if ($customer && $shippingAddresses->count() > 0)
                                                            @foreach ($shippingAddresses as $key => $address)
                                                                <div class="address-box {{ $key == 0 ? 'active' : '' }}">
                                                                    <input type="radio"
                                                                        id="address_{{ $address->id }}"
                                                                        name="address_type" value="{{ $address->id }}"
                                                                        {{ $key == 0 ? 'checked' : '' }}
                                                                        onclick="selectAddress(false,this)">
                                                                    <label for="address_{{ $address->id }}">
                                                                        <strong>Name:
                                                                            {{ $address->contact_person_name }}</strong>
                                                                        <br>
                                                                        📞 Phone: {{ $address->phone }}<br>
                                                                        🏠 Address: {{ $address->address }}
                                                                    </label>
                                                                    <button type="button"
                                                                        class="btn btn-sm btn-outline-primary edit-btn"
                                                                        onclick="openEditModal({{ $address }})">✏️</button>
                                                                </div>
                                                            @endforeach

                                                            <label class="address-box">
                                                                <input type="radio" name="address_type" value="new"
                                                                    onclick="selectAddress(true,this)">
                                                                ➕ নতুন ঠিকানা যোগ করুন
                                                            </label>
                                                        @endif
                                                    </div>
                                                    <div id="newAddressForm"
                                                        style="{{ $customer ? 'display:none;' : '' }}"
                                                        class="col-md-12 mb-4">
                                                        @if (!$customer)
                                                            <input type="hidden" name="address_type" value="new">
                                                        @endif
                                                        <div class="form-group">
                                                            <label for="name">নাম <span
                                                                    class="text-danger">*</span></label>
                                                            <input type="text" class="form-control auto-save"
                                                                placeholder="আপনার নাম লিখুন" name="name"
                                                                value="{{ old('name') }}">
                                                            @error('name')
                                                                <span class="text-danger">{{ $message }}</span>
                                                            @enderror
                                                        </div>
                                                        <div class="{{ session()->has('otp_phone') ? 'd-none' : '' }}">
                                                            <div class="form-group">
                                                                <label for="phone">ফোন নম্বর <span
                                                                        class="text-danger">*</span></label>
                                                                <input type="number" class="form-control auto-save check-phone"
                                                                    id="phone" name="phone"
                                                                    placeholder="ফোন নম্বর লিখুন"
                                                                    value="{{ old('phone') }}">
                                                                <span id="phoneFeedback" class="small text-danger"></span>
                                                                @error('phone')
                                                                    <span class="text-danger">{{ $message }}</span>
                                                                @enderror
                                                            </div>
                                                        </div>

                                                        <div class="form-group">
                                                            <label>আপনার ঠিকানা <span class="text-danger">*</span></label>
                                                            <textarea class="form-control auto-save" placeholder="আপনার শিপিং ঠিকানা লিখুন" name="address">{{ old('address') }}</textarea>
                                                            @error('address')
                                                                <span class="text-danger">{{ $message }}</span>
                                                            @enderror
                                                        </div>
                                                    </div>

                                                    <div class="col-md-12 mb-3">
                                                        <div class="form-group">
                                                            <label>ইমার্জেন্সি নোট (ঐচ্ছিক) </label>
                                                            <textarea class="form-control" placeholder="আপনার নোট লিখুন" name="customer_note">{{ old('order_note') }}</textarea>
                                                            @error('customer_note')
                                                                <span class="text-danger">{{ $message }}</span>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="size-chart">
                                                    @if ($productLandingPage->product->size_chart)
                                                        <img class="w-100"
                                                            src="{{ asset('assets/storage/product/thumbnail') }}/{{ $productLandingPage->product['size_chart'] }}"
                                                            class="img-fluid" alt="Product size chart image">
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="order-box">
                                            <div class="card-body">
                                                <div class="row mb-3">
                                                    <div class="col">
                                                        <h4 class="">Your Product</h4>
                                                    </div>
                                                </div>
                                                <div class="row mb-3">
                                                    <div class="col-md-12">
                                                        <div class="name-price">
                                                            <div class="title-box">
                                                                <h4>Product</h4>
                                                            </div>
                                                            <div class="p-data-box d-flex mb-2">
                                                                <div class="mr-2">
                                                                    <img id="main-image" class="sp-right-img"
                                                                        src="{{ asset('assets/storage/product/thumbnail') }}/{{ $productLandingPage->product['thumbnail'] }}">
                                                                </div>
                                                                <div>
                                                                    <p class="m-0">
                                                                        {{ $productLandingPage->product->name }} <i
                                                                            class="fa fa-close"></i> <span
                                                                            class="min-item fw-bold">1</span></p>
                                                                    <div>
                                                                        <span class="sp-price">৳
                                                                            {{ $productLandingPage->product['unit_price'] }}</span>

                                                                    </div>
                                                                    @if ($productLandingPage->product->discount > 0)
                                                                        <span class="discount-price">
                                                                            <del>৳
                                                                                {{ $productLandingPage->product->unit_price }}
                                                                            </del> -
                                                                            @if ($productLandingPage->product->discount_type == 'percent')
                                                                                {{ $productLandingPage->product->discount }}%
                                                                            @elseif($productLandingPage->product->discount_type == 'flat')
                                                                                {{ $productLandingPage->product->discount }}৳
                                                                            @endif
                                                                        </span>
                                                                    @endif

                                                                    <div
                                                                        class="product-quantity d-flex align-items-center">
                                                                        <div class="input-group input-group-style-2 pr-3 d-flex align-items-center"
                                                                            style="width: 160px; ">

                                                                            <button
                                                                                class="btn btn-danger btn-number btn-sm"
                                                                                type="button" data-type="minus"
                                                                                data-field="quantity"
                                                                                style="padding: 10px">
                                                                                <i class="fa fa-minus "></i>
                                                                            </button>

                                                                            <input
                                                                                style="font-size: 20px; font-weight: 600"
                                                                                type="text" readonly
                                                                                class="form-control bg-transparent input-number text-center cart-qty-field"
                                                                                placeholder="1" min="1"
                                                                                max="100">

                                                                            <button
                                                                                class="btn btn-success btn-number btn-sm"
                                                                                type="button" data-type="plus"
                                                                                data-field="quantity"
                                                                                style="padding: 10px">
                                                                                <i class="fa fa-plus "></i>
                                                                            </button>

                                                                        </div>

                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="p-variant">

                                                                @php
                                                                    $qty = 0;
                                                                    if (!empty($productLandingPage->product)) {
                                                                        foreach (
                                                                            json_decode(
                                                                                $productLandingPage->product->variation,
                                                                            )
                                                                            as $key => $variation
                                                                        ) {
                                                                            $qty += $variation->qty;
                                                                        }
                                                                    }
                                                                @endphp

                                                                @if (count($productLandingPage->product->colors) > 0)
                                                                    <div class="row mb-4 mt-3">
                                                                        <div class="col-12 mb-3">
                                                                            <h4 style="font-size: 18px;">Color</h4>
                                                                        </div>
                                                                        @if ($productLandingPage->product->color_variant != null)
                                                                            <div class="col-12 mb-3">
                                                                                <div class="d-flex">
                                                                                    @foreach (json_decode($productLandingPage->product->color_variant) as $key => $color)
                                                                                        <div
                                                                                            class="v-color-box position-relative">
                                                                                            <input type="radio"
                                                                                                id="{{ $productLandingPage->product->id }}-color-{{ $key }}"
                                                                                                name="color"
                                                                                                value="{{ $color->code }}"
                                                                                                @if ($key == 0) checked @endif>
                                                                                            <label
                                                                                                for="{{ $productLandingPage->product->id }}-color-{{ $key }}"
                                                                                                class="color-label"
                                                                                                style="background-color: {{ $color->code }}; overflow: hidden;">
                                                                                                <img src="{{ asset($color->image) }}"
                                                                                                    data-image="{{ asset($color->image) }}"
                                                                                                    alt="{{ $color->color }}"
                                                                                                    style="max-width:100%; height:auto;">
                                                                                            </label>

                                                                                            <span class="d-inline-block"
                                                                                                style="height: 20px; width: 20px; border-radius: 50%; position: absolute;
                                                                                            right: -11px;
                                                                                            top: -34px;
                                                                                            background: {{ $color->code }}"></span>

                                                                                        </div>
                                                                                    @endforeach
                                                                                </div>
                                                                            </div>

                                                                        @endif
                                                                    </div>
                                                                @endif

                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row mb-3">
                                                    <input type="hidden" name="price"
                                                        value="{{ $productLandingPage->product->unit_price }}">
                                                    <input id="qty" type="hidden" name="quantity" value="1">
                                                    <input type="hidden" name="tax"
                                                        value="{{ $productLandingPage->product->tax }}">
                                                    <input type="hidden" name="discount"
                                                        value="{{ $productLandingPage->product->discount }}">
                                                    <input type="hidden" name="product_id"
                                                        value="{{ $productLandingPage->product->id }}">
                                                    <div class="col-md-12">
                                                        <div class="name-price mb-3">
                                                            <div class="title-box">
                                                                <h4>Your order</h4>
                                                            </div>
                                                            <div class="p-dtls-box">
                                                                <table class="mt-4 table">
                                                                    <tr>
                                                                        <th>Subtotal :</th>
                                                                        <td id="subtotal">
                                                                            {{ $productLandingPage->product['unit_price'] }}
                                                                            <span>৳</span>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>

                                                                        <td class="w-100 d-block">
                                                                            <h5 class="shipping-title">Shipping :</h5>

                                                                            <div class="row">
                                                                                @foreach (\App\Models\ShippingMethod::where(['status' => 1])->get() as $shipping)
                                                                                    <div class="col-md-6">
                                                                                        <label class="shipping-box"
                                                                                            for="shipping_{{ $shipping['id'] }}">
                                                                                            <input type="radio" required
                                                                                                name="shipping_method"
                                                                                                class="shipping-method"
                                                                                                id="shipping_{{ $shipping['id'] }}"
                                                                                                value="{{ $shipping['id'] }}"
                                                                                                data-cost="{{ $shipping['cost'] }}"
                                                                                                data-shpping="{{ $shipping['cost'] }}">
                                                                                            <span class="shipping-title">
                                                                                                {{ $shipping['title'] }}
                                                                                            </span>
                                                                                            <span class="shipping-cost">
                                                                                                {{ $shipping['cost'] }}
                                                                                            </span>
                                                                                        </label>
                                                                                    </div>
                                                                                @endforeach
                                                                            </div>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <th>Total:</th>
                                                                        <td>
                                                                            <span
                                                                                id="total">{{ $productLandingPage->product['unit_price'] }}
                                                                                <span>
                                                                                    ৳</span></span>
                                                                            <div id="preloader" style="display: none;">
                                                                                <img src="{{ asset('assets/front-end/img/loader_.gif') }}"
                                                                                    alt="Loading..." width="20">
                                                                            </div>
                                                                        </td>
                                                                    </tr>
                                                                </table>
                                                            </div>
                                                        </div>

                                                        <button type="submit" class="w-100 btn btn-primary">অর্ডার
                                                            সম্পূর্ণ করুন</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-3 mx-auto">
                    <div class="form-group my-3">
                        <a href="{{ route('home') }}" class="btn btn-primary">Discover More Products</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@push('scripts')
    @php
        $price = $productLandingPage->product;
        $cleanPrice = floatval(str_replace(',', '', $price));
        $shippingMethods = \App\Models\ShippingMethod::where(['status' => 1])->get();
    @endphp

    <script>
        $(document).ready(function() {

            let typingTimer;
            let doneTypingInterval = 1000;

            $(".otp-phone-save").on("input", function() {

                clearTimeout(typingTimer);

                let phoneValue = $(this).val();
                let sessionId = $('#session_id').val();

                console.log('phone:', phoneValue, 'session:', sessionId);

                typingTimer = setTimeout(function() {

                    $.ajax({
                        url: "{{ route('save.user.info') }}",
                        type: "POST",
                        data: {
                            phone: phoneValue,
                            session_id: sessionId,
                            _token: "{{ csrf_token() }}"
                        },
                        dataType: "json",
                        success: function(response) {
                            if (response.success) {
                                console.log("Phone auto-saved successfully!");
                            } else {
                                console.log("Failed to save phone.");
                            }
                        },
                        error: function(xhr) {
                            console.log("Error:", xhr.responseText);
                        }
                    });

                }, doneTypingInterval);
            });

        });
    </script>

    <script>
        const unitPrice = {{ $cleanPrice }};



        // Pre-converted shipping costs from the backend
        const shippingPrices = {
            @foreach ($shippingMethods as $shipping)
                "{{ $shipping['id'] }}": parseFloat("{{ $shipping['cost'] }}"),
            @endforeach
        };

        document.addEventListener('DOMContentLoaded', function() {
            //const shippingBox = document.querySelectorAll('.shipping-box');
            const shippingRadios = document.querySelectorAll('.shipping-method');
            const totalElement = document.getElementById('total');
            const preloader = document.getElementById('preloader');

            // Update total price
            function updateTotal(shippingCost) {
                preloader.style.display = 'inline-block'; // Show preloader
                // shippingBox.style.background = '#f26d21';
                setTimeout(function() {
                    // Calculate the new total using pre-converted values
                    const total = unitPrice + shippingCost;
                    console.log("Uporer", unitPrice);


                    // Update the total element with the new total
                    totalElement.innerHTML = total.toFixed(2) +
                        "<span> ৳</span>"; // Assuming 2 decimal points
                    preloader.style.display = 'none'; // Hide preloader after update
                }, 1500); // Simulate a delay for 1.5 seconds
            }

            // Listen for changes in the shipping method
            shippingRadios.forEach(function(radio) {
                radio.addEventListener('change', function() {
                    const shippingCost = parseFloat(shippingPrices[this
                        .value]); // Get pre-converted shipping cost

                    updateTotal(shippingCost);
                });
            });
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const phoneRegex = /^(01[3-9]\d{8})$/;

            document.querySelectorAll('.check-phone').forEach(function(input) {

                const container = input.closest('.col-md-6');
                const feedback = container.querySelector('.phone-feedback');

                input.addEventListener('input', function() {

                    const value = this.value.trim();

                    feedback.classList.remove('text-danger', 'text-success');

                    if (value === '') {
                        feedback.textContent = '';
                        return;
                    }

                    if (!phoneRegex.test(value)) {
                        feedback.classList.add('text-danger');
                        feedback.textContent = 'Please enter valid BD number (017XXXXXXXX)';
                    } else {
                        feedback.classList.add('text-success');
                        feedback.textContent = 'Valid phone number!';
                    }
                });

                input.addEventListener('blur', function() {

                    const value = this.value.trim();

                    if (value === '') {
                        feedback.classList.remove('text-success');
                        feedback.classList.add('text-danger');
                        feedback.textContent = 'Phone number is required';
                    }
                });

            });

        });
    </script>
    <script>
        $(document).ready(function() {
            let typingTimer;
            let doneTypingInterval = 1000; // Time in milliseconds (1 second)

            $(".auto-save").on("input", function() {
                clearTimeout(typingTimer);
                typingTimer = setTimeout(saveUserData, doneTypingInterval);
            });

            function saveUserData() {
                let formData = $(".userInfoForm").serialize();

                $.ajax({
                    url: "{{ route('save.user.info') }}",
                    type: "POST",
                    data: formData,
                    dataType: "json",
                    success: function(response) {
                        if (response.success) {
                            console.log("Data auto-saved successfully!");
                        } else {
                            console.log("Failed to save data.");
                        }
                    },
                    error: function(xhr) {
                        console.log("Error: ", xhr.responseText);
                    }
                });
            }
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const mainImage = document.getElementById('main-image');
            const colorInputs = document.querySelectorAll('input[name="color"]');

            if (!mainImage || colorInputs.length === 0) return;

            // 1️⃣ Set default main image from the first color
            const firstColorLabel = colorInputs[0].nextElementSibling;
            const firstImageSrc = firstColorLabel.querySelector('img').getAttribute('data-image');
            if (firstImageSrc) {
                mainImage.src = firstImageSrc;
            }

            // 2️⃣ Add click listener to each color image
            colorInputs.forEach(input => {
                const label = input.nextElementSibling;
                const img = label.querySelector('img');
                if (img) {
                    img.addEventListener('click', function() {
                        const newSrc = img.getAttribute('data-image');

                        // Add transition effect
                        mainImage.style.transition = 'opacity 0.3s ease';
                        mainImage.style.opacity = '1';

                        // After fade out, change image
                        setTimeout(() => {
                            mainImage.src = newSrc;
                            mainImage.style.opacity = '1';
                        }, 300);
                    });
                }
            });
        });
    </script>
    <script>
        $(document).ready(function() {

            let unitPrice = $('input[name="price"]').val();
            let convertPrice =
                {{ $productLandingPage->product->unit_price }};



            let hiddenQtyInput = $('input[name="quantity"]');
            let visibleQtyInput = $('.cart-qty-field');
            const preloader = document.getElementById('preloader');
            const qty = document.getElementById('qty');
            let qtyShow = document.querySelector('.min-item');
            let spPrice = document.querySelector('.sp-price');

            function updateTotal() {
                preloader.style.display = 'inline-block';
                let qty = parseInt(hiddenQtyInput.val()) || 1;
                console.log(visibleQtyInput);

                let subtotal = convertPrice * qty;
                qtyShow.innerHTML = qty;
                qty.value = qty;
                spPrice.innerHTML = `৳ ${subtotal}`;

                // Subtotal update
                $('#subtotal')
                    .data('value', subtotal)
                    .html(subtotal.toFixed(2) + ' <span>৳</span>');

                // Selected shipping cost
                let shippingCost = parseFloat($('.shipping-method:checked').data('shpping')) || 0;
                console.log("shipping cost: ", shippingCost);


                let total = subtotal + shippingCost;
                console.log("total: ", total);

                $('#total').html(total.toFixed(2) + ' <span>৳</span>');
                preloader.style.display = 'none';
            }

            // PLUS button
            $('.btn-number[data-type="plus"]').click(function() {
                let currentQty = parseInt(hiddenQtyInput.val());
                let max = parseInt(visibleQtyInput.attr('max')) || 100;

                if (currentQty < max) {
                    currentQty++;
                    hiddenQtyInput.val(currentQty);
                    visibleQtyInput.val(currentQty);
                    updateTotal();
                }
            });

            // MINUS button
            $('.btn-number[data-type="minus"]').click(function() {
                let currentQty = parseInt(hiddenQtyInput.val());
                let min = parseInt(visibleQtyInput.attr('min')) || 1;

                if (currentQty > min) {
                    currentQty--;
                    hiddenQtyInput.val(currentQty);
                    visibleQtyInput.val(currentQty);
                    updateTotal();
                }
            });

            // Shipping change হলে total update
            $('.shipping-method').change(function() {
                updateTotal();
            });

        });
    </script>
    <script>
        function selectAddress(showNew, el) {
            const form = document.getElementById('newAddressForm');
            if (showNew) {
                form.style.display = 'block';
                form.querySelectorAll('input,textarea').forEach(i => i.disabled = false);
            } else {
                form.style.display = 'none';
                form.querySelectorAll('input,textarea').forEach(i => i.disabled = true);
            }

            document.querySelectorAll('.address-box').forEach(b => b.classList.remove('active'));
            el.closest('.address-box').classList.add('active');
        }
    </script>
    <script>
        function openEditModal(address) {
            event.stopPropagation();

            document.getElementById('edit_id').value = address.id;
            document.getElementById('edit_name').value = address.contact_person_name;
            document.getElementById('edit_phone').value = address.phone;
            document.getElementById('edit_address').value = address.address;

            $('#editAddressModal').modal('show');
        }
    </script>
    <script>
        // Send OTP
        $('#send_otp').on('click', function() {
            let phone = $('#otp_phone').val();

            $.post("{{ route('send.otp') }}", {
                _token: "{{ csrf_token() }}",
                phone: phone
            }, function(res) {
                if (res.status === 'success') {
                    $('#otpInputRow').removeClass('d-none');
                }
            });
        });

        // Verify OTP
        $('#verify_otp').on('click', function() {
            $.post("{{ route('verify.otp') }}", {
                _token: "{{ csrf_token() }}",
                phone: $('#otp_phone').val(),
                otp: $('#otp').val()
            }, function(res) {
                if (res.status === 'success') {
                    $('#otpSection').hide();
                    $('.checkoutForm').removeClass('d-none');
                }
            });
        });
    </script>
@endpush
