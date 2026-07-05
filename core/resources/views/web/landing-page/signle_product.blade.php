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

    <link href="https://fonts.maateen.me/solaiman-lipi/font.css" rel="stylesheet">

    <style>
        /* ── Base ── */
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
            font-weight: 400;
        }

        /* ── Existing section styles (unchanged) ── */
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
            border: none;
            background: #f26d21;
            display: block;
            padding: 15px 20px;
            border-radius: 7px;
            color: #fff;
            text-align: center;
            font-weight: 700;
            font-size: 20px;
            transition: 0.3s;
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
            object-fit: contain;
        }

        .section-title {
            background: #f26d21;
            padding: 7px;
            border-radius: 7px;
            margin-bottom: 10px;
        }

        .section-title>h4 {
            color: #fff;
            font-size: 30px;
        }

        .benefit-section .benefit-img {
            padding: 0 75px;
        }

        .benefit-section .benefit-img img {
            width: 100%;
            height: 100%;
        }

        .benefit-section .landingPageFeatureImg .slider-img {
            max-height: 400px;
        }

        .benefit-section .landingPageReviewImages .slider-img {
            max-height: 310px;
        }

        .benefit-section .swiper-button-prev,
        .benefit-section .swiper-button-next {
            width: 30px;
            height: 30px;
            background: #ff6a00;
            border-radius: 50%;
            box-shadow: 0 2px 6px rgba(0, 0, 0, .2);
            transition: .3s;
        }

        .benefit-section .swiper-button-prev::after,
        .benefit-section .swiper-button-next::after {
            font-size: 16px;
            font-weight: bold;
            color: #fff;
        }

        .benefit-section .swiper-button-prev:hover,
        .benefit-section .swiper-button-next:hover {
            background: #e65c00;
            transform: scale(1.1);
        }

        .benefit-section svg {
            height: 18px !important;
        }

        .carousel-control-next {
            right: 23%;
        }

        .carousel-control-prev {
            left: 23%;
        }

        .carousel-control-next,
        .carousel-control-prev {
            top: 0% !important;
        }

        .p-details,
        .p-details-contact-section,
        .p-image {
            padding: 30px 13px;
        }

        .p-details h3,
        .p-short-details h4,
        .p-short-details p {
            text-align: left !important;
        }

        .btn-primary:hover,
        .btn-primary:focus,
        .btn-primary:active {
            background-color: #f26d21;
            border-color: #f26d21;
        }

        .btn-primary:not(:disabled):not(.disabled).active,
        .btn-primary:not(:disabled):not(.disabled):active,
        .show>.btn-primary.dropdown-toggle {
            background-color: #f26d21;
            border-color: #f26d21;
        }

        /* ── OTP card ── */
        .otp-card {
            max-width: 500px;
            margin: 20px auto;
            padding: 30px 20px;
            background: #fff;
            box-shadow: rgba(0, 0, 0, .24) 0 3px 8px;
            border-radius: 10px;
            text-align: center;
        }

        .otp-inputs {
            display: flex;
            justify-content: center;
            gap: 12px;
            margin-bottom: 20px;
        }

        .otp-box {
            width: 55px;
            height: 55px;
            text-align: center;
            font-size: 26px;
            border: 1px solid #555;
            background: #fff;
            border-radius: 4px;
        }

        .otp-box:focus {
            outline: none;
            border-color: orange;
        }

        @media (max-width:768px) {
            .slider-img {
                max-height: 550px;
            }

            .benefit-section .benefit-img {
                padding: 0;
            }

            .benefit-section .landingPageReviewImages .slider-img {
                max-height: 400px;
            }

            .carousel-control-next {
                right: 5%;
            }

            .carousel-control-prev {
                left: 5%;
            }
        }

        /* ═══════════════════════════════════════════════
                                                                                                           NEW ORDER SECTION STYLES
                                                                                                        ═══════════════════════════════════════════════ */
        .sp-order-wrap {
            background: #f4f6f8;
            padding: 32px 0 44px;
        }

        /* Banner */
        .sp-select-banner {
            background: #f26d21;
            color: #fff;
            text-align: center;
            padding: 14px 20px;
            font-size: 18px;
            font-weight: 700;
            border-radius: 10px;
            margin-bottom: 20px;
            letter-spacing: .5px;
        }

        /* Product Grid */
        .sp-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 14px;
            margin-bottom: 28px;
        }

        .sp-card-product {
            background: #fff;
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            padding: 14px;
            display: flex;
            gap: 12px;
            align-items: flex-start;
            cursor: pointer;
            transition: border-color .2s, box-shadow .2s;
            position: relative;
        }

        .sp-card-product::after {
            content: '✔';
            position: absolute;
            top: 30%;
            right: 7%;
            width: 26px;
            height: 26px;
            background: #22c55e;
            color: #fff;
            border-radius: 50%;
            font-size: 14px;
            display: none;
            align-items: center;
            justify-content: center;
        }

        .sp-card-product.sp-active::after {
            display: flex;
        }

        /* .sp-card-product:hover { border-color:#22c55e; box-shadow:0 4px 16px rgba(242,109,33,.1); } */
        .sp-card-product.sp-active {
            border-color: #22c55e;
            background: #f0fdf4;
            box-shadow: 0 4px 16px rgba(34, 197, 94, .12);
        }

        .sp-thumb {
            width: 78px;
            height: auto;
            border-radius: 8px;
            object-fit: cover;
            flex-shrink: 0;
            border: 1px solid #e5e7eb;
        }

        .sp-thumb-ph {
            width: 78px;
            height: 78px;
            border-radius: 8px;
            background: #f3f4f6;
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 1px solid #e5e7eb;
            font-size: 28px;
        }

        .sp-info {
            flex: 1;
            min-width: 0;
        }

        .sp-pname {
            font-size: 13px;
            font-weight: 600;
            color: #1f2937;
            line-height: 1.4;
            margin-bottom: 4px;
        }

        .sp-pprice {
            font-size: 15px;
            font-weight: 700;
            color: #22c55e;
            font-family: 'Jost', sans-serif !important;
            margin-bottom: 8px;
        }

        /* Size buttons */
        .sp-size-row {
            display: inline-flex;
            gap: 5px;
            flex-wrap: wrap;
            margin-bottom: 8px;
        }

        .sp-size-btn {
            border: 1.5px solid #d1d5db;
            border-radius: 5px;
            padding: 3px 9px;
            font-size: 12px;
            cursor: pointer;
            background: #fff;
            color: #374151;
            transition: all .15s;
        }

        .sp-size-btn:hover {
            border-color: #f26d21;
            color: #f26d21;
        }

        .sp-size-btn.active {
            background: #1e293b;
            color: #fff;
            border-color: #1e293b;
        }

        /* Qty */
        .sp-qty-row {
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .sp-qty-lbl {
            font-size: 12px;
            color: #6b7280;
        }

        .sp-qty-ctrl {
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .sp-qty-btn {
            width: 28px;
            height: 28px;
            border-radius: 50%;
            border: 1.5px solid #d1d5db;
            background: #f9fafb;
            cursor: pointer;
            font-size: 16px;
            line-height: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #374151;
            transition: all .15s;
            padding: 0;
        }

        .sp-qty-btn:hover {
            background: #f26d21;
            color: #fff;
            border-color: #f26d21;
        }

        .sp-qty-val {
            font-size: 15px;
            font-weight: 700;
            min-width: 22px;
            text-align: center;
            color: #1f2937;
            font-family: 'Jost', sans-serif !important;
        }

        /* Two-column layout */
        .sp-two-col {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        /* Panel card */
        .sp-panel {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 14px;
            overflow: hidden;
        }

        .sp-panel-head {
            background: #f26d21;
            padding: 12px 18px;
            font-size: 16px;
            font-weight: 700;
            color: #fff;
            text-align: center;
        }

        .sp-panel-body {
            padding: 18px;
        }

        /* Form elements */
        .sp-lbl {
            font-size: 13px;
            color: #374151;
            font-weight: 600;
            margin-bottom: 5px;
            display: block;
        }

        .sp-input {
            width: 100%;
            border: 1.5px solid #e5e7eb;
            border-radius: 8px;
            padding: 10px 13px;
            font-size: 14px;
            background: #fff;
            color: #1f2937;
            outline: none;
            transition: border-color .2s;
            font-family: 'SolaimanLipi', sans-serif !important;
        }

        .sp-input:focus {
            border-color: #f26d21;
            box-shadow: 0 0 0 3px rgba(242, 109, 33, .08);
        }

        .sp-input::placeholder {
            color: #9ca3af;
        }

        .sp-select {
            width: 100%;
            border: 1.5px solid #e5e7eb;
            border-radius: 8px;
            padding: 10px 13px;
            font-size: 14px;
            background: #fff;
            color: #1f2937;
            outline: none;
            cursor: pointer;
            transition: border-color .2s;
            font-family: 'SolaimanLipi', sans-serif !important;
        }

        .sp-select:focus {
            border-color: #f26d21;
        }

        .sp-textarea {
            width: 100%;
            border: 1.5px solid #e5e7eb;
            border-radius: 8px;
            padding: 10px 13px;
            font-size: 14px;
            background: #fff;
            color: #1f2937;
            outline: none;
            resize: vertical;
            min-height: 78px;
            transition: border-color .2s;
            font-family: 'SolaimanLipi', sans-serif !important;
        }

        .sp-textarea:focus {
            border-color: #f26d21;
        }

        .sp-fg {
            margin-bottom: 14px;
        }

        /* Saved address */
        .sp-addr-box {
            border: 2px solid #e5e7eb;
            border-radius: 10px;
            padding: 10px 13px;
            cursor: pointer;
            margin-bottom: 10px;
            position: relative;
            transition: border-color .2s;
        }

        .sp-addr-box:hover {
            border-color: #f26d21;
        }

        .sp-addr-box.active {
            border-color: #22c55e;
            background: #f0fdf4;
        }

        .sp-addr-box input[type="radio"] {
            display: none;
        }

        /* Phone feedback */
        .sp-phone-fb {
            font-size: 12px;
            margin-top: 4px;
        }

        /* Summary items */
        .sp-sum-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 0;
            border-bottom: 1px solid #f3f4f6;
        }

        .sp-sum-item:last-of-type {
            border-bottom: none;
        }

        .sp-sum-img {
            width: 50px;
            height: auto;
            border-radius: 8px;
            object-fit: cover;
            flex-shrink: 0;
            border: 1px solid #e5e7eb;
        }

        .sp-sum-img-ph {
            width: 50px;
            height: 50px;
            border-radius: 8px;
            background: #f3f4f6;
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
        }

        .sp-sum-info {
            flex: 1;
            min-width: 0;
        }

        .sp-sum-name {
            font-size: 13px;
            color: #1f2937;
            font-weight: 600;
            line-height: 1.3;
        }

        .sp-sum-meta {
            font-size: 12px;
            color: #6b7280;
            margin-top: 2px;
            font-family: 'Jost', sans-serif !important;
        }

        .sp-sum-price {
            font-size: 14px;
            font-weight: 700;
            color: #1f2937;
            white-space: nowrap;
            font-family: 'Jost', sans-serif !important;
        }

        .sp-empty {
            text-align: center;
            color: #9ca3af;
            font-size: 13px;
            padding: 22px 0;
        }

        /* Shipping options */
        .sp-ship-row {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            margin-bottom: 14px;
        }

        .sp-ship-opt {
            flex: 1;
            min-width: 140px;
            border: 1.5px solid #e5e7eb;
            border-radius: 8px;
            padding: 9px 12px;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 7px;
            font-size: 13px;
            transition: all .2s;
        }

        .sp-ship-opt:hover {
            border-color: #22c55e;
        }

        .sp-ship-opt input[type="radio"] {
            accent-color: #22c55e;
        }

        .sp-ship-opt.checked {
            border-color: #22c55e;
            background: #f0fdf4;
        }

        /* Totals */
        .sp-divider {
            border: none;
            border-top: 1px solid #e5e7eb;
            margin: 10px 0;
        }

        .sp-total-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 13px;
            padding: 5px 0;
            color: #374151;
        }

        .sp-total-row.grand {
            border-top: 2px dashed #e5e7eb;
            margin-top: 6px;
            padding-top: 10px;
            font-size: 15px;
            font-weight: 700;
            color: #22c55e;
        }

        .sp-total-val {
            font-weight: 600;
            font-family: 'Jost', sans-serif !important;
        }

        .sp-grand-val {
            font-size: 18px;
            font-weight: 800;
            color: #22c55e;
            font-family: 'Jost', sans-serif !important;
        }

        /* Submit button */
        .sp-submit-btn {
            width: 100%;
            margin-top: 16px;
            padding: 14px;
            border-radius: 10px;
            background: #f26d21;
            border: none;
            color: #fff;
            font-size: 24px;
            font-weight: 700;
            cursor: pointer;
            transition: background .2s, transform .1s;
            font-family: 'SolaimanLipi', sans-serif !important;
        }

        .sp-submit-btn:hover {
            background: #c75510;
        }

        .sp-submit-btn:active {
            transform: scale(.99);
        }

        .sp-submit-btn:disabled {
            background: #e5e7eb;
            color: #9ca3af;
            cursor: not-allowed;
        }

        @media (max-width:768px) {
            .sp-grid {
                grid-template-columns: 1fr;
            }

            .sp-two-col {
                grid-template-columns: 1fr;
            }

            .sp-thumb,
            .sp-thumb-ph {
                width: 65px;
                height: auto;
            }

            .sp-ship-row {
                flex-direction: column;
            }

            .old-add-container {
                display: flex;
                flex-direction: column;

            }
        }

        .sp-total-row.grand span:first-child {
            font-size: 16px;
            font-weight: 700;
        }
    </style>
@endpush

@section('content')

    {{-- ════════════════════════════════════════
     SLIDER / HEADER SECTION
════════════════════════════════════════ --}}
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
                                                alt="Banner">
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <a class="carousel-control-prev d-flex" href="#carouselExampleIndicators" role="button"
                                data-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="sr-only">Previous</span>
                            </a>
                            <a class="carousel-control-next d-flex" href="#carouselExampleIndicators" role="button"
                                data-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="sr-only">Next</span>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="p-details col-lg-6">
                    <div class="container">
                        <div class="row">
                            <div class="col-lg-12 text-center">
                                <h3 class="mb-2">{{ $productLandingPage->title }}</h3>
                                <div class="p-short-details">{!! $productLandingPage->description !!}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ════════════════════════════════════════
     BENEFIT / FEATURE SECTION
════════════════════════════════════════ --}}
    <section class="benefit-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-10 mx-auto">
                    <div class="section-title">
                        <h4 class="text-center">এই পণ্যের বৈশিষ্ট্য</h4>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-10 mx-auto">
                    <div class="row align-items-center justify-content-center">
                        <div class="col-lg-6 mb-3">
                            @if (json_decode($productLandingPage->feature_list) != null)
                                <ul class="benefit-list">
                                    @foreach (json_decode($productLandingPage->feature_list) as $title)
                                        <li><i class="fa fa-check-square-o"></i>{{ $title }}</li>
                                    @endforeach
                                </ul>
                            @endif
                            <button type="button" class="w-100 order-btn" onclick="scrollToOrder()">অর্ডার করতে
                                চাই</button>
                        </div>
                        <div class="col-lg-6 mb-3">
                            @php
                                $featureImgs = json_decode($productLandingPage->feature_img, true);
                                if (!$featureImgs) {
                                    $featureImgs = [$productLandingPage->feature_img];
                                }
                            @endphp
                            <div class="benefit-img">
                                <div class="offersSwiper landingPageFeatureImg swiper w-100">
                                    <div class="swiper-wrapper">
                                        @foreach ($featureImgs as $img)
                                            <div class="swiper-slide">
                                                <div class="slider-img">
                                                    <img src="{{ asset('assets/storage/landingpage/' . $img) }}"
                                                        alt="Feature">
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                    <div class="swiper-pagination"></div>
                                    <div class="swiper-button-prev"></div>
                                    <div class="swiper-button-next"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Customer Review Slider --}}
            @php
                $reviewImgs = json_decode($productLandingPage->review_img, true);
                if (!$reviewImgs) {
                    $reviewImgs = [];
                }
            @endphp
            @if (count($reviewImgs) > 0)
                <div class="row align-items-center justify-content-center mt-4">
                    <div class="col-lg-10 mx-auto">
                        <div class="section-title">
                            <h4 class="text-center">আমাদের কাস্টমারদের রিভিউ</h4>
                        </div>
                        <div class="landingPageReviewImages swiper w-100">
                            <div class="swiper-wrapper justify-content-lg-center">
                                @foreach ($reviewImgs as $img)
                                    <div class="swiper-slide">
                                        <div class="slider-img">
                                            <img src="{{ asset('assets/storage/landingpage/' . $img) }}" alt="Review">
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <div class="swiper-pagination"></div>
                            <div class="px-5">
                                <div class="swiper-button-next"></div>
                                <div class="swiper-button-prev"></div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </section>

    {{-- ════════════════════════════════════════
     MULTIPLE EXTRA SECTIONS
════════════════════════════════════════ --}}
    @if ($productLandingPage->landingPageSection)
        @foreach ($productLandingPage->landingPageSection as $section)
            <section class="benefit-section my-4">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-10">
                            <div class="section-title">
                                <h4 class="text-center">{{ $section->section_title }}</h4>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-8 mx-auto">
                            <div class="row align-items-center justify-content-center">
                                @if ($section->section_direction == 'left')
                                    <div class="col-lg-6 mb-3">
                                        <p>{{ $section->section_description }}</p>
                                        @if ($section->order_button == 1)
                                            <button type="button" class="w-100 order-btn"
                                                onclick="scrollToOrder()">অর্ডার করতে চাই</button>
                                        @endif
                                    </div>
                                    <div class="col-lg-6 mb-3">
                                        <img src="{{ asset('assets/storage/landingpage/' . $section->section_img) }}"
                                            style="width:100%;height:560px;" alt="Section image">
                                    </div>
                                @else
                                    <div class="col-lg-6 mb-3">
                                        <img src="{{ asset('assets/storage/landingpage/' . $section->section_img) }}"
                                            style="width:100%;height:560px;" alt="Section image">
                                    </div>
                                    <div class="col-lg-6 mb-3">
                                        <p>{{ $section->section_description }}</p>
                                        @if ($section->order_button == 1)
                                            <button type="button" class="w-100 order-btn"
                                                onclick="scrollToOrder()">অর্ডার করতে চাই</button>
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

    {{-- ════════════════════════════════════════
     ORDER SECTION — নতুন design
════════════════════════════════════════ --}}
    <section id="orderPlace" class="sp-order-wrap">
        <div class="container">

            {{-- Flash messages --}}
            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show mb-3" role="alert">
                    {{ session('error') }}
                    <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
                </div>
            @endif
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show mb-3" role="alert">
                    {{ session('success') }}
                    <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
                </div>
            @endif

            <form action="{{ route('sproduct.checkout') }}" method="POST" id="spOrderForm"
                enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="session_id" value="{{ session()->getId() }}">

                {{-- ── STEP 1: পণ্য নির্বাচন ── --}}
                <div class="sp-select-banner">আপনার পণ্য নির্বাচন করুন</div>

                <div class="sp-grid">
                    @foreach ($lpProducts as $product)
                        @php

                            $colorVariants = is_array($product->color_variant)
                                ? $product->color_variant
                                : json_decode($product->color_variant ?? '[]', true);

                            $hasColors = !empty($colorVariants) && count($colorVariants) > 0;

                            $choiceOptions = is_array($product->choice_options)
                                ? $product->choice_options
                                : json_decode($product->choice_options, true);
                            $hasSizes = !empty($choiceOptions);
                        @endphp
                        @php
                            $discountAmt = 0;
                            if ($product->discount > 0) {
                                $discountAmt =
                                    $product->discount_type == 'flat'
                                        ? $product->discount
                                        : ($product->unit_price / 100) * $product->discount;
                            }
                            $finalPrice = $product->unit_price - $discountAmt;
                        @endphp

                        <div class="sp-card-product" id="spCard_{{ $product->id }}" data-id="{{ $product->id }}"
                            data-price="{{ $finalPrice }}" onclick="spToggle({{ $product->id }}, this)">

                            {{-- Image --}}
                            @if ($product->thumbnail)
                                <img class="sp-thumb"
                                    src="{{ asset('assets/storage/product/thumbnail') }}/{{ $product->thumbnail }}"
                                    alt="{{ $product->name }}">
                            @else
                                <div class="sp-thumb-ph">🛍️</div>
                            @endif

                            <div class="sp-info">
                                <div class="sp-pname">{{ $product->name }}</div>

                                <div class="sp-pprice">
                                    @if ($discountAmt > 0)
                                        <del style="font-size:12px; color:#9ca3af; font-weight:500;">{{ number_format($product->unit_price) }}
                                            ৳</del>
                                        {{ number_format($finalPrice) }} ৳
                                    @else
                                        {{ number_format($product->unit_price) }} ৳
                                    @endif
                                </div>
                                {{-- Color options --}}
                                @if ($hasColors)
                                    <div class="sp-size-row" onclick="event.stopPropagation()" style="gap:8px;">
                                        @foreach ($colorVariants as $ci => $color)
                                            @php
                                                $code = $color['code'] ?? '';
                                                $cImage = $color['image'] ?? '';
                                                $cName = $color['color'] ?? '';
                                            @endphp
                                            <button type="button" class="sp-color-btn {{ $ci === 0 ? 'active' : '' }}"
                                                data-pid="{{ $product->id }}" data-code="{{ $code }}"
                                                data-name="{{ $cName }}" title="{{ $cName }}"
                                                onclick="spColor(this, '{{ $product->id }}', '{{ $code }}', '{{ $cName }}', '{{ asset($cImage) }}')"
                                                style="
                    width:32px;
                    height:32px;
                    border-radius:6px;
                    border:2px solid {{ $ci === 0 ? '#1e293b' : '#d1d5db' }};
                    padding:0;
                    cursor:pointer;
                    overflow:hidden;
                    background:#fff;
                ">
                                                <img src="{{ asset($cImage) }}" alt="{{ $cName }}"
                                                    style="width:100%; height:100%; object-fit:cover; display:block;">
                                            </button>
                                        @endforeach
                                    </div>
                                    <input type="hidden" name="sp_color[{{ $product->id }}]"
                                        id="colorHidden_{{ $product->id }}"
                                        value="{{ $colorVariants[0]['code'] ?? '' }}">
                                @endif

                                {{-- Size / Choice options --}}
                                @if ($hasSizes)
                                    @foreach ($choiceOptions as $choice)
                                        <div class="sp-size-row" onclick="event.stopPropagation()">
                                            @foreach ($choice['options'] as $ki => $opt)
                                                <button type="button"
                                                    class="sp-size-btn {{ $ki === 0 ? 'active' : '' }}"
                                                    data-pid="{{ $product->id }}" data-choice="{{ $choice['name'] }}"
                                                    onclick="spSize(this,'{{ $product->id }}','{{ $choice['name'] }}','{{ $opt }}')">
                                                    {{ $opt }}
                                                </button>
                                            @endforeach
                                        </div>
                                        <input type="hidden" name="sp_size[{{ $product->id }}][{{ $choice['name'] }}]"
                                            id="szHidden_{{ $product->id }}_{{ $choice['name'] }}"
                                            value="{{ $choiceOptions[0]['options'][0] ?? '' }}">
                                    @endforeach
                                @endif
                                <br>
                                {{-- Qty --}}
                                <div class="sp-qty-row" onclick="event.stopPropagation()">
                                    <span class="sp-qty-lbl">Qty:</span>
                                    <div class="sp-qty-ctrl">
                                        <button type="button" class="sp-qty-btn"
                                            onclick="spQty({{ $product->id }}, -1)">−</button>
                                        <span class="sp-qty-val" id="qtyVal_{{ $product->id }}">0</span>
                                        <button type="button" class="sp-qty-btn"
                                            onclick="spQty({{ $product->id }}, 1)">+</button>
                                    </div>
                                </div>

                                <input type="hidden" name="sp_qty[{{ $product->id }}]"
                                    id="qtyHidden_{{ $product->id }}" value="0">
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- ── STEP 2: Billing + Summary ── --}}
                <div class="sp-two-col">

                    {{-- LEFT — Billing --}}
                    <div class="sp-panel">
                        <div class="sp-panel-head">বিলিং বিবরণ</div>
                        <div class="sp-panel-body">

                            @if ($customer && $shippingAddresses->count() > 0)
                                <label class="sp-lbl">পূর্বের ডেলিভারিকৃত ঠিকানা বেছে নিন অথবা নতুন দিন</label>
                                <div class="old-add-container"
                                    style="max-height:260px; overflow-y:auto; margin-bottom:12px;">
                                    @foreach ($shippingAddresses as $ki => $addr)
                                        <label for="addr_{{ $addr->id }}"
                                            class="sp-addr-box {{ $ki == 0 ? 'active' : '' }}">
                                            <input type="radio" id="addr_{{ $addr->id }}" name="address_type"
                                                value="{{ $addr->id }}" {{ $ki == 0 ? 'checked' : '' }}
                                                onchange="spAddrToggle(false, this)">
                                            <div style="font-size:13px;">
                                                <strong>{{ $addr->contact_person_name }}</strong><br>
                                                📞 {{ $addr->phone }}<br>
                                                🏠 {{ $addr->address }}
                                            </div>
                                            <button type="button" class="btn btn-sm btn-dark"
                                                style="position:absolute;bottom:6px;right:6px;font-size:11px;"
                                                onclick="openEditModal({{ $addr }});event.stopPropagation()">
                                                ✏️ Edit
                                            </button>
                                        </label>
                                    @endforeach
                                </div>
                                <label class="sp-addr-box"
                                    style="border-color:#22c55e;color:#22c55e;font-weight:600;font-size:13px;">
                                    <input type="radio" name="address_type" value="new"
                                        onchange="spAddrToggle(true, this)">
                                    + নতুন ঠিকানা যোগ করুন
                                </label>
                            @endif

                            {{-- New address form --}}
                            <div id="spNewAddrForm"
                                style="{{ $customer && $shippingAddresses->count() > 0 ? 'display:none;' : '' }}">

                                @if (!$customer)
                                    <input type="hidden" name="address_type" value="new">
                                @endif

                                <div class="sp-fg">
                                    <label class="sp-lbl">আপনার নাম <span class="text-danger">*</span></label>
                                    <input type="text" name="name" class="sp-input auto-save"
                                        placeholder="আপনার নাম লিখুন" value="{{ old('name') }}">
                                    @error('name')
                                        <span class="text-danger" style="font-size:12px;">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="sp-fg">
                                    <label class="sp-lbl">ফোন নম্বর <span class="text-danger">*</span></label>
                                    <input type="number" name="phone" id="spPhone"
                                        class="sp-input auto-save check-phone" placeholder="01XXXXXXXXX"
                                        value="{{ old('phone') }}">
                                    <span id="phoneFeedback" class="sp-phone-fb"></span>
                                    @error('phone')
                                        <span class="text-danger" style="font-size:12px;">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="sp-fg">
                                    <label class="sp-lbl">আপনার ঠিকানা <span class="text-danger">*</span></label>
                                    <textarea name="address" class="sp-textarea auto-save" placeholder="বাড়ি / রোড / এলাকার নাম লিখুন">{{ old('address') }}</textarea>
                                    @error('address')
                                        <span class="text-danger" style="font-size:12px;">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            {{-- Note --}}
                            <div class="sp-fg">
                                <label class="sp-lbl">ইমার্জেন্সি নোট (ঐচ্ছিক)</label>
                                <textarea name="customer_note" class="sp-textarea" placeholder="বিশেষ কিছু জানাতে চাইলে লিখুন">{{ old('customer_note') }}</textarea>
                            </div>

                            {{-- Size chart --}}
                            @foreach ($lpProducts as $product)
                                @if ($product->size_chart)
                                    <div class="mt-3">
                                        <img class="w-100 rounded"
                                            src="{{ asset('assets/storage/product/thumbnail') }}/{{ $product->size_chart }}"
                                            alt="Size chart">
                                    </div>
                                @endif
                            @endforeach

                        </div>
                    </div>

                    {{-- RIGHT — Order Summary --}}
                    <div class="sp-panel">
                        <div class="sp-panel-head">অর্ডার বিবরণ</div>
                        <div class="sp-panel-body">

                            {{-- Selected items list --}}
                            <div id="spSummaryList">
                                <p class="sp-empty">কোনো পণ্য নির্বাচন করা হয়নি</p>
                            </div>

                            <hr class="sp-divider">

                            {{-- Shipping methods --}}
                            <div class="sp-fg">
                                <label class="sp-lbl">ডেলিভারি পদ্ধতি <span class="text-danger">*</span></label>

                                @if ($shippingConfig->shipping_type === 'free_shipping' && $shippingConfig->free_shipping_type === 'all_products')

                                    {{-- সব পণ্যে Free --}}
                                    <div class="p-2 rounded mb-2"
                                        style="background:#f0fff4; border:1px dashed #28a745; font-size:14px; color:#198754;">
                                        🎉 সব পণ্যে <strong>ডেলিভারি চার্জ ফ্রি</strong> চলছে!
                                    </div>
                                    {{-- hidden input — first method auto select --}}
                                    @if ($shippingMethods->first())
                                        <input type="hidden" name="shipping_method"
                                            value="{{ $shippingMethods->first()->id }}" data-cost="0"
                                            id="freeShipHidden">
                                    @endif
                                @elseif (
                                    $shippingConfig->shipping_type === 'free_shipping' &&
                                        $shippingConfig->free_shipping_type === 'without_discount_product')
                                    {{-- without_discount_product — JS দিয়ে dynamic handle হবে --}}
                                    @php
                                        $freeMin = (float) ($shippingConfig->free_shipping_min_amount ?? 0);
                                    @endphp

                                    <div id="lpFreeShipNotice" class="p-2 rounded mb-2 d-none"
                                        style="background:#f0fff4; border:1px dashed #28a745; font-size:14px; color:#198754;">
                                        🎉 <strong>আপনি ডেলিভারি চার্জ ফ্রি পেয়েছেন!</strong>
                                    </div>
                                    <div id="lpFreeShipProgress" class="p-2 rounded mb-2"
                                        style="background:#fff3cd; border:1px dashed #ffc107; font-size:14px;">
                                        🛒 আর মাত্র <strong id="lpRemaining">৳{{ number_format($freeMin) }}</strong>
                                        কিনলে <strong>ডেলিভারি চার্জ ফ্রি</strong> পাবেন! <span
                                            class="text-danger">(ডিসকাউন্ট ছাড়া পণ্যে)</span>
                                    </div>

                                    <div id="lpShipMethodsWrap">
                                        <div class="sp-ship-row">
                                            @foreach ($shippingMethods as $ship)
                                                <label class="sp-ship-opt" id="shipLabel_{{ $ship->id }}">
                                                    <input type="radio" required name="shipping_method"
                                                        class="sp-ship-radio" value="{{ $ship->id }}"
                                                        data-cost="{{ $ship->cost }}"
                                                        data-discount="{{ $ship->discount_amount }}"
                                                        data-discounttype="{{ $ship->discount_type }}"
                                                        onchange="spUpdateTotals()">
                                                    <span>{{ $ship->title }}<br>
                                                        @if ($ship->discount_amount > 0)
                                                            @php
                                                                $dCost =
                                                                    $ship->discount_type === 'percent'
                                                                        ? $ship->cost -
                                                                            round(
                                                                                ($ship->cost * $ship->discount_amount) /
                                                                                    100,
                                                                            )
                                                                        : $ship->cost - $ship->discount_amount;
                                                                $dCost = max(0, $dCost);
                                                            @endphp
                                                            <del style="font-size:11px; color:#999;">{{ number_format($ship->cost) }}
                                                                ৳</del>
                                                            <strong>{{ number_format($dCost) }} ৳</strong>
                                                        @else
                                                            <strong>{{ number_format($ship->cost) }} ৳</strong>
                                                        @endif
                                                    </span>
                                                </label>
                                            @endforeach
                                        </div>
                                    </div>

                                    {{-- Free হলে hidden input --}}
                                    <input type="hidden" name="shipping_method" id="lpFreeShipInput" value=""
                                        disabled>
                                @else
                                    {{-- Order Wise / Default --}}
                                    <div class="sp-ship-row">
                                        @foreach ($shippingMethods as $ship)
                                            <label class="sp-ship-opt" id="shipLabel_{{ $ship->id }}">
                                                <input type="radio" required name="shipping_method"
                                                    class="sp-ship-radio" value="{{ $ship->id }}"
                                                    data-cost="{{ $ship->cost }}"
                                                    data-discount="{{ $ship->discount_amount }}"
                                                    data-discounttype="{{ $ship->discount_type }}"
                                                    onchange="spUpdateTotals()">
                                                <span>{{ $ship->title }}<br>
                                                    @if ($ship->discount_amount > 0)
                                                        @php
                                                            $dCost =
                                                                $ship->discount_type === 'percent'
                                                                    ? $ship->cost -
                                                                        round(
                                                                            ($ship->cost * $ship->discount_amount) /
                                                                                100,
                                                                        )
                                                                    : $ship->cost - $ship->discount_amount;
                                                            $dCost = max(0, $dCost);
                                                        @endphp
                                                        <del style="font-size:11px; color:#999;">{{ number_format($ship->cost) }}
                                                            ৳</del>
                                                        <strong>{{ number_format($dCost) }} ৳</strong>
                                                    @else
                                                        <strong>{{ number_format($ship->cost) }} ৳</strong>
                                                    @endif
                                                </span>
                                            </label>
                                        @endforeach
                                    </div>

                                @endif
                            </div>

                            {{-- Totals --}}
                            <div class="sp-total-row">
                                <span>পণ্যের মোট মূল্য</span>
                                <span class="sp-total-val" id="spSubtotal">০ ৳</span>
                            </div>
                            <div class="sp-total-row">
                                <span>ডেলিভারি চার্জ</span>
                                <span class="sp-total-val" id="spShipCost">০ ৳</span>
                            </div>
                            <div class="sp-total-row grand">
                                <span>মোট পণ্যের দাম</span>
                                <span class="sp-grand-val" id="spGrand">০ ৳</span>
                            </div>

                            <button type="submit" id="spSubmitBtn" class="sp-submit-btn">
                                অর্ডার সম্পূর্ণ করুন
                            </button>

                        </div>
                    </div>

                </div>{{-- /.sp-two-col --}}
            </form>

            <div class="row mt-4">
                <div class="col-lg-3 mx-auto text-center">
                    <a href="{{ route('home') }}" class="btn btn-primary">Discover More Products</a>
                </div>
            </div>

        </div>
    </section>

    {{-- Edit Address Modal --}}
    <div class="modal fade" id="editAddressModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form method="POST" action="{{ route('address.update') }}" id="addressUpdateForm">
                @csrf
                <input type="hidden" name="id" id="edit_id">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">ঠিকানা সম্পাদনা করুন</h5>
                        <button type="button" class="close" data-bs-dismiss="modal">
                            <span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group mb-2">
                            <label>নাম</label>
                            <input type="text" name="name" id="edit_name" class="form-control">
                        </div>
                        <div class="form-group mb-2">
                            <label>ফোন</label>
                            <input type="number" name="phone" id="edit_phone" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>ঠিকানা</label>
                            <textarea name="address" id="edit_address" class="form-control" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-primary" type="submit">আপডেট করুন</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        const SP = {
            @foreach ($lpProducts as $p)
                @php
                    $pDiscountAmt = 0;
                    if ($p->discount > 0) {
                        $pDiscountAmt = $p->discount_type == 'flat' ? $p->discount : ($p->unit_price / 100) * $p->discount;
                    }
                    $pFinalPrice = $p->unit_price - $pDiscountAmt;

                    $pColorVariants = is_array($p->color_variant) ? $p->color_variant : json_decode($p->color_variant ?? '[]', true);
                    $pDefaultColorName = $pColorVariants[0]['color'] ?? null;
                    $pDefaultColorCode = $pColorVariants[0]['code'] ?? null;
                    $pDefaultColorImage = $pColorVariants[0]['image'] ?? null;
                @endphp
                {{ $p->id }}: {
                        id: {{ $p->id }},
                        selected: false,
                        qty: 0,
                        price: {{ (float) $pFinalPrice }},
                        name: @json($p->name),
                        thumb: "{{ $p->thumbnail ? asset('assets/storage/product/thumbnail/' . $p->thumbnail) : '' }}",
                        hasDiscount: {{ $pDiscountAmt > 0 ? 'true' : 'false' }},
                        colorName: @json($pDefaultColorName),
                        colorCode: @json($pDefaultColorCode),
                        colorImage: @json($pDefaultColorImage ? asset($pDefaultColorImage) : null)
                    },
            @endforeach
        };

        /* ── Auto-select if single product ── */
        document.addEventListener('DOMContentLoaded', function() {
            const spKeys = Object.keys(SP);
            if (spKeys.length === 1) {
                const onlyId = spKeys[0];
                SP[onlyId].selected = true;
                SP[onlyId].qty = 1;
                syncQty(onlyId);
                const card = document.getElementById('spCard_' + onlyId);
                if (card) card.classList.add('sp-active');
                render();
            }
        });

        /* ── Toggle product card ── */
        function spToggle(id, el) {
            const p = SP[id];
            p.selected = !p.selected;
            if (p.selected && p.qty === 0) p.qty = 1;
            if (!p.selected) p.qty = 0;
            syncQty(id);
            el.classList.toggle('sp-active', p.selected);
            render();
        }

        /* ── Qty change ── */
        function spQty(id, delta) {
            const p = SP[id];
            p.qty = Math.max(0, p.qty + delta);
            p.selected = p.qty > 0;
            syncQty(id);
            const card = document.getElementById('spCard_' + id);
            if (card) card.classList.toggle('sp-active', p.selected);
            render();
        }

        function syncQty(id) {
            document.getElementById('qtyVal_' + id).textContent = SP[id].qty;
            document.getElementById('qtyHidden_' + id).value = SP[id].qty;
        }

        /* ── Size select ── */
        function spSize(btn, pid, choice, val) {
            document.querySelectorAll(`.sp-size-btn[data-pid="${pid}"][data-choice="${choice}"]`)
                .forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            const h = document.getElementById(`szHidden_${pid}_${choice}`);
            if (h) h.value = val;
        }

        function spColor(btn, pid, code, name, imageUrl) {
            document.querySelectorAll(`.sp-color-btn[data-pid="${pid}"]`)
                .forEach(b => {
                    b.classList.remove('active');
                    b.style.borderColor = '#d1d5db';
                });
            btn.classList.add('active');
            btn.style.borderColor = '#1e293b';

            const h = document.getElementById(`colorHidden_${pid}`);
            if (h) h.value = code;

            if (SP[pid]) {
                SP[pid].colorName = name;
                SP[pid].colorCode = code;
                SP[pid].colorImage = imageUrl; // ✅ image url save
            }

            render();
        }

        /* ── Render summary ── */
        function render() {
            const list = document.getElementById('spSummaryList');
            const sel = Object.values(SP).filter(p => p.selected && p.qty > 0);

            if (!sel.length) {
                list.innerHTML = '<p class="sp-empty">কোনো পণ্য নির্বাচন করা হয়নি</p>';
                spUpdateTotals();
                return;
            }

            list.innerHTML = sel.map(p => `
        <div class="sp-sum-item">
            ${p.thumb
                ? `<img class="sp-sum-img" src="${p.thumb}" alt="${p.name}">`
                : `<div class="sp-sum-img-ph">🛍️</div>`}
            <div class="sp-sum-info">
                <div class="sp-sum-name">${p.name}</div>
                <div class="sp-sum-meta">
                    পরিমাণ: ${p.qty}
                    ${p.colorImage ? `
                            | রং:
                            <img src="${p.colorImage}" alt="${p.colorName || ''}"
                                style="width:18px; height:18px; border-radius:4px; object-fit:cover; vertical-align:middle; border:1px solid #ddd;">
                        ` : ''}
                </div>
            </div>
            <div class="sp-sum-price">${(p.price * p.qty).toLocaleString('en-BD')} ৳</div>
        </div>
    `).join('');

            spUpdateTotals();
        }

        /* ── Update totals ── */
        const LP_SHIPPING_CONFIG = {
            type: "{{ $shippingConfig->shipping_type }}",
            freeType: "{{ $shippingConfig->free_shipping_type }}",
            freeMin: {{ $shippingConfig->free_shipping_min_amount ?? 0 }},
            globalDiscountMin: {{ $freeShippingMinAmount }},
            globalDiscount: {{ $freeShippingDiscount }},
        };

        function spUpdateTotals() {
            // সব selected product-এর total (display করার জন্য)
            const subtotal = Object.values(SP)
                .filter(p => p.selected && p.qty > 0)
                .reduce((s, p) => s + p.price * p.qty, 0);

            // ── without_discount_product logic ──
            if (LP_SHIPPING_CONFIG.type === 'free_shipping' &&
                LP_SHIPPING_CONFIG.freeType === 'without_discount_product') {

                const freeMin = LP_SHIPPING_CONFIG.freeMin;

                // *** KEY CHANGE: শুধু discount-ছাড়া product গুলো count করা হচ্ছে ***
                const nonDiscountedTotal = Object.values(SP)
                    .filter(p => p.selected && p.qty > 0 && !p.hasDiscount)
                    .reduce((s, p) => s + p.price * p.qty, 0);

                const isFree = freeMin > 0 && nonDiscountedTotal >= freeMin;
                const remaining = Math.max(0, freeMin - nonDiscountedTotal);

                const notice = document.getElementById('lpFreeShipNotice');
                const progress = document.getElementById('lpFreeShipProgress');
                const wrap = document.getElementById('lpShipMethodsWrap');
                const freeInput = document.getElementById('lpFreeShipInput');
                const remainEl = document.getElementById('lpRemaining');

                if (remainEl) remainEl.textContent = '৳' + remaining.toLocaleString('en-BD');

                if (isFree) {
                    notice?.classList.remove('d-none');
                    progress?.classList.add('d-none');
                    wrap?.classList.add('d-none');

                    document.querySelectorAll('.sp-ship-radio').forEach(r => {
                        r.disabled = true;
                        r.required = false;
                    });
                    if (freeInput) {
                        freeInput.disabled = false;
                        const firstMethod = document.querySelector('.sp-ship-radio');
                        if (firstMethod) freeInput.value = firstMethod.value;
                    }

                    document.getElementById('spSubtotal').textContent = subtotal.toLocaleString('en-BD') + ' ৳';
                    document.getElementById('spShipCost').textContent = 'Free';
                    document.getElementById('spGrand').textContent = subtotal.toLocaleString('en-BD') + ' ৳';
                    return;

                } else {
                    notice?.classList.add('d-none');
                    progress?.classList.remove('d-none');
                    wrap?.classList.remove('d-none');

                    document.querySelectorAll('.sp-ship-radio').forEach(r => {
                        r.disabled = false;
                        r.required = true;
                    });
                    if (freeInput) freeInput.disabled = true;
                }
            }

            // ── all_products free ──
            if (LP_SHIPPING_CONFIG.type === 'free_shipping' &&
                LP_SHIPPING_CONFIG.freeType === 'all_products') {

                document.getElementById('spSubtotal').textContent = subtotal.toLocaleString('en-BD') + ' ৳';
                document.getElementById('spShipCost').textContent = 'Free';
                document.getElementById('spGrand').textContent = subtotal.toLocaleString('en-BD') + ' ৳';
                return;
            }

            // ── Order Wise / Default ──
            const shipInput = document.querySelector('.sp-ship-radio:checked');
            let shipCost = 0;

            if (shipInput) {
                const baseCost = parseFloat(shipInput.dataset.cost) || 0;
                const discountAmt = parseFloat(shipInput.dataset.discount) || 0;
                const discountType = shipInput.dataset.discounttype || 'flat';

                let methodDiscount = 0;
                if (discountAmt > 0) {
                    methodDiscount = discountType === 'percent' ?
                        baseCost * discountAmt / 100 :
                        discountAmt;
                    methodDiscount = Math.min(methodDiscount, baseCost);
                }

                let globalDiscount = 0;
                if (LP_SHIPPING_CONFIG.globalDiscountMin > 0 &&
                    subtotal >= LP_SHIPPING_CONFIG.globalDiscountMin) {
                    const remaining = baseCost - methodDiscount;
                    globalDiscount = Math.min(LP_SHIPPING_CONFIG.globalDiscount, remaining);
                }

                shipCost = Math.max(0, baseCost - methodDiscount - globalDiscount);
            }

            document.getElementById('spSubtotal').textContent = subtotal.toLocaleString('en-BD') + ' ৳';
            document.getElementById('spShipCost').textContent = shipCost.toLocaleString('en-BD') + ' ৳';
            document.getElementById('spGrand').textContent = (subtotal + shipCost).toLocaleString('en-BD') + ' ৳';

            document.querySelectorAll('.sp-ship-opt').forEach(l => l.classList.remove('checked'));
            if (shipInput) shipInput.closest('.sp-ship-opt')?.classList.add('checked');
        }

        /* ── Address toggle ── */
        function spAddrToggle(showNew, el) {
            const form = document.getElementById('spNewAddrForm');
            form.style.display = showNew ? 'block' : 'none';
            form.querySelectorAll('input,textarea').forEach(i => i.disabled = !showNew);
            document.querySelectorAll('.sp-addr-box').forEach(b => b.classList.remove('active'));
            el.closest('.sp-addr-box').classList.add('active');
        }

        /* ── Form submit guard ── */
        document.getElementById('spOrderForm').addEventListener('submit', function(e) {
            const sel = Object.values(SP).filter(p => p.selected && p.qty > 0);
            if (!sel.length) {
                e.preventDefault();
                alert('অনুগ্রহ করে কমপক্ষে একটি পণ্য নির্বাচন করুন।');
                return;
            }
            const btn = document.getElementById('spSubmitBtn');
            btn.disabled = true;
            btn.innerHTML = 'অর্ডার করা হচ্ছে... <span class="spinner-border spinner-border-sm"></span>';
        });

        /* ── Shipping change listener ── */
        document.querySelectorAll('.sp-ship-radio').forEach(r => r.addEventListener('change', spUpdateTotals));
    </script>

    <script>
        /* ── Phone validation ── */
        document.addEventListener('DOMContentLoaded', function() {
            const phoneRegex = /^(01[3-9]\d{8})$/;
            const input = document.getElementById('spPhone');
            const feedback = document.getElementById('phoneFeedback');
            if (!input || !feedback) return;

            input.addEventListener('input', function() {
                const val = this.value.trim();
                feedback.className = 'sp-phone-fb';
                if (!val) {
                    feedback.textContent = '';
                    return;
                }
                if (!phoneRegex.test(val)) {
                    feedback.classList.add('text-danger');
                    feedback.textContent = 'সঠিক বাংলাদেশী নম্বর দিন (01XXXXXXXXX)';
                } else {
                    feedback.classList.add('text-success');
                    feedback.textContent = '✓ সঠিক নম্বর';
                }
            });

            input.addEventListener('blur', function() {
                if (!this.value.trim()) {
                    feedback.className = 'sp-phone-fb text-danger';
                    feedback.textContent = 'ফোন নম্বর আবশ্যক';
                }
            });
        });
    </script>

    <script>
        /* ── Auto-save ── */
        $(document).ready(function() {
            let timer;
            console.log($('#spOrderForm').serialize());
            $('.auto-save').on('input', function() {
                clearTimeout(timer);
                timer = setTimeout(function() {
                    $.ajax({
                        url: "{{ route('save.user.info') }}",
                        type: 'POST',
                        data: $('#spOrderForm').serialize(),
                        dataType: 'json',
                        success: function(res) {
                            if (res.success) console.log(res);
                        }
                    });
                }, 1000);
            });
        });
    </script>

    <script>
        /* ── Edit address modal ── */
        function openEditModal(address) {
            document.getElementById('edit_id').value = address.id;
            document.getElementById('edit_name').value = address.contact_person_name;
            document.getElementById('edit_phone').value = address.phone;
            document.getElementById('edit_address').value = address.address;
            $('#editAddressModal').modal('show');
        }

        $('#addressUpdateForm').on('submit', function(e) {
            e.preventDefault();
            $.ajax({
                url: $(this).attr('action'),
                method: 'POST',
                data: $(this).serialize(),
                success: function(res) {
                    if (res.status === 'success') {
                        $('#editAddressModal').modal('hide');
                        toastr.success('ঠিকানা আপডেট হয়েছে');
                        location.reload();
                    }
                },
                error: function(xhr) {
                    console.log(xhr.responseText);
                }
            });
        });
    </script>
    <script>
        function scrollToOrder() {
            const el = document.getElementById('orderPlace');
            if (el) {
                el.scrollIntoView({
                    behavior: 'smooth'
                });
            }
        }
    </script>
@endpush
