@extends('web.layouts.app')

@section('title', 'Premium Clothing & Original Skincare BD | ' . $web_config['name']->value)

@section('meta_description',
    'Discover trending fashion and authentic beauty products with fast delivery across
    Bangladesh. Premium Clothing & Original Skincare | Shopping Zone BD')
    @push('css_or_js')
        <meta property="og:image"
            content="{{ $web_config['fav_icon']->value
                ? asset('assets/storage/company') . '/' . $web_config['fav_icon']->value
                : asset('assets/default/icons/szbd.png') }}" />
        <meta property="og:title" content="Premium Clothing & Original Skincare BD | {{ $web_config['name']->value }} " />
        <meta property="og:url" content="{{ env('APP_URL') }}">
        <meta property="og:description"
            content="Discover trending fashion and authentic beauty products with fast delivery across Bangladesh. Premium Clothing & Original Skincare | Shopping Zone BD">

        <meta property="twitter:card"
            content="{{ $web_config['fav_icon']->value
                ? asset('assets/storage/company') . '/' . $web_config['fav_icon']->value
                : asset('assets/default/icons/szbd.png') }}" />
        <meta property="twitter:title" content="Premium Clothing & Original Skincare BD | {{ $web_config['name']->value }} " />
        <meta property="twitter:url" content="{{ env('APP_URL') }}">
        <meta property="twitter:description"
            content="Discover trending fashion and authentic beauty products with fast delivery across Bangladesh. Premium Clothing & Original Skincare | Shopping Zone BD">
    @endpush
@section('style')
    <style>
        .shop-more-btn.btn-warning {
            background-color: #ff5d00 !important;
            border-color: #ff5d00 !important;
            color: #fff !important;
        }

        .shop-more-btn.btn-warning:hover {
            background-color: #e37f00 !important;
            border-color: #e37f00 !important;
            color: #fff !important;
        }

        .shop-more-btn.btn-warning:focus,
        .shop-more-btn.btn-warning:active,
        .shop-more-btn.btn-warning:focus-visible {
            background-color: #e37f00 !important;
            border-color: #e37f00 !important;
            box-shadow: 0 0 0 0.25rem rgba(255, 145, 0, 0.4) !important;
            color: #fff !important;
        }

        .shop-more-btn.btn-warning:disabled,
        .shop-more-btn.btn-warning.disabled {
            background-color: #ff5d00 !important;
            border-color: #ff5d00 !important;
            opacity: 0.65 !important;
            color: #fff !important;
        }

        .btn-primary:hover,
        .btn-primary:active {
            color: #fff;
            background-color: #ff5d00 !important;
            border-color: #ff5d00 !important;
        }

        .btn-primary:focus {
            box-shadow: 0 0 0 0.25rem rgba(0, 0, 0, 0.4) !important;
        }

        section.category .owl-nav,
        .new-arrivals-section .owl-nav {
            display: block !important;
        }

        .owl-nav button {
            position: absolute;
            top: 45%;
            transform: translateY(-50%);
            background: rgba(255, 94, 0, 0.862) !important;
            color: #fff;
            border: none;
            outline: none;
            border-radius: 5px;
            width: 40px;
            height: 50px;
            font-size: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: 0.3s;

        }

        .owl-nav button:hover {
            background-color: #ff5d00 !important;
            color: #fff;
        }

        .owl-nav button:focus {
            border: none !important;
            outline: none !important;
            outline: none !important;
        }

        section.category .owl-nav .owl-prev,
        .new-arrivals-section .owl-nav .owl-prev {
            left: 0%;
            top: 54% !important;
        }

        section.category .owl-nav .owl-next,
        .new-arrivals-section .owl-nav .owl-next {
            right: 0;
            top: 54%;
        }

        section.category .owl-nav button,
        .new-arrivals-section .owl-nav button {
            height: 40px;
        }

        .category .card {
            border-radius: 12px;
        }

        .category .card-body {
            padding: 5px 0;
        }

        .category .card img {
            border-radius: 12px 12px 0 0;

        }

        section.category .card-title {

            font-size: 16px;
            font-weight: 600;
        }

        section.category .card-text {

            font-size: 14px;
            margin-top: 8px;
            color: #000 !important;
        }

        .category .category-carosel img {
            height: 200px;
        }

        .owl-nav button {
            background: rgb(255 94 0 / 54%) !important;
        }

        /*new arrival slider */
        .owl-carousel {
            position: relative;
        }

        .owl-nav .owl-prev {
            left: 4px;
        }

        .owl-nav .owl-next {
            right: -4px;
        }

        .owl-nav button.disabled {
            background: #555 !important;
            opacity: 0.5;
            cursor: not-allowed;
        }


        .new-arrivals-section .product-box>.product-image2>a>img {
            width: 100% !important;
            height: auto;
            object-fit: cover;
            display: block;
            margin: 0 auto !important;
        }

        .section__title span {
            position: absolute;
            color: #f0f1f3;
            left: 0;
            right: 0;
            z-index: -1;
            font-weight: 700;
            font-size: 3rem;
            text-transform: uppercase;
            line-height: 0;
            font-family: Open Sans, sans-serif;
        }

        .btn-warning {
            color: #fff !important;
            background-color: #f26d21 !important;
        }

        .btn-warning:hover {
            color: #fff;
            background-color: #f26d21 !important;
        }

        /* Mobile Category styles*/
        section.mobile-category .category-item img {
            max-width: 100%;
            height: auto;
        }

        section.mobile-category .category-item h5 {
            font-size: 15px;
        }

        section.mobile-category .category-item p {
            font-size: 12px;
            margin-top: 5px;
        }

        section.mobile-category .category-item {
            background: #f26d21;
            height: 170px;

        }

        section.mobile-category .category-item .image-box {
            height: 120px;
        }

        section.mobile-category .category-item .image-box img {
            height: 100%;
            width: 100%;
            object-fit: cover;
            object-position: top;
        }

        section.top-banner .big-banner {
            height: 200px;
            width: 100%;
        }

        @media (max-width: 768px) {
            section.top-banner .big-banner {
                height: auto;
                width: 100%;
            }
        }

        @media (max-width: 768px) {
            .small-title {
                font-size: 20px;
            }
        }

        @media (min-width: 992px) {
            .product-slider .product-image2-col-2 {
                height: 424px;
            }
        }

        /* 7 April 26 */
        .product-slider .product-box-col-2 {
            height: auto !important;
        }

        /* category new style 11 jun 2026 */
        /* ===== Category Section ===== */


        .section-heading-title h3 {
            font-size: 1.4rem;
            font-weight: 600;
            color: #3a2f25;
            margin-bottom: 1.25rem;
        }

        /* ===== Card Item ===== */
        .category-item {
            text-align: center;
        }

        .category-item a {
            display: flex;
            flex-direction: column;
            align-items: center;

            text-decoration: none;
        }

        /* Image box — white bg + border + shadow */
        .category-item .img-box {
            width: 100%;
            /* aspect-ratio: 1 / 1; */
            /* সবসময় square */
            background: #ffffff;
            border-radius: 14px;
            border: 1.5px solid #e0d5c8;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.10);
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }



        .category-item .img-box img {
            width: 100%%;
            height: auto;
            object-fit: contain;
            display: block;
        }

        /* Title — no background, plain text */
        .category-item .cat-title {
            font-size: 13px;
            font-weight: 500;
            color: #3a2f25;
            background: none;
            margin: 0;
            line-height: 1.3;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            width: 100%;
            margin-top: 10px;
        }

        /* ===== Owl Nav ===== */
        .owl-carousel.category-carosel .owl-nav button {
            position: absolute !important;
            top: 50% !important;
            /* transform: translateY(-50%) !important; */
            background: #f0a847 !important;
            border-radius: 50% !important;
            width: 30px !important;
            height: 30px !important;
            display: flex !important;
            align-items: center;
            justify-content: center;
            border: none !important;
            outline: none;
            z-index: 10;
        }

        .owl-carousel.category-carosel .owl-nav .owl-prev {
            left: -14px !important;
        }

        .owl-carousel.category-carosel .owl-nav .owl-next {
            right: -14px !important;
        }

        .owl-carousel.category-carosel .owl-nav button i {
            color: #fff !important;
            font-size: 13px;
        }

        /* ===== Responsive ===== */
        @media (max-width: 767px) {
            .category-item .cat-title {
                font-size: 11px;
            }
        }
    </style>
@endsection
@section('content')

    @include('web.layouts.partials._modals')
    <!------start  header main slider-->
    @include('web.layouts.partials.slider')
    <!------start  header main slider-->
    <?php
    $company_mobile_logo = \App\Models\BusinessSetting::where('type', 'company_web_logo')->first()->value;
    ?>

    {{-- Category Section Start --}}
    <section class="category">
        <div class="container px-0">
            <div class="row mb-3 mt-3">
                <div class="col-12">
                    <div class="section-heading-title d-flex align-items-center justify-content-center">
                        <h3 class="mb-0">Categories</h3>
                    </div>
                </div>
            </div>

            <div class="owl-carousel category-carosel">
                @foreach ($categories as $category)
                    <div class="category-item">
                        <a href="{{ route('category.products', $category->slug) }}">
                            <div class="img-box">
                                <img src='{{ asset("assets/storage/category/$category->icon") }}'
                                    alt="{{ $category->name }}">
                            </div>
                            <p class="cat-title">{{ $category->name }}</p>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    {{-- mobile category --}}
    {{-- <section class="mobile-category d-block d-lg-none mt-2">
        <div class="container">
            <div class="row my-1 my-lg-3">
                <div class="col-12">
                    <div class="section-heading-title d-flex align-items-center justify-content-center">
                        <h3>Categories</h3>
                    </div>
                </div>
            </div>
            <div class="row gap-0">
                @foreach ($categories as $category)
                    <div class="col-4 px-1" style="gap: 20px;">
                        <a href="{{ route('category.products', $category->slug) }}" class="mcat-link">
                            <div class="mcat-box">
                                <img src="{{ asset("assets/storage/category/$category->icon") }}"
                                    alt="{{ $category->name }}" class="mcat-img">
                            </div>
                            <div class="mcat-title">
                                {{ $category->name }}
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </section> --}}
    <!------Start Product section----->
    {{-- Coupon Slider Start --}}
    @if (count($couponSlider) > 0)
        <section class="coupon-slider">
            <div class="container ">
                <div class="row mb-2 my-2 my-lg-3">
                    <div class="col-12">
                        <div class="section-heading-title d-flex align-items-center justify-content-center">
                            <h3>Exclusive Deals at Asmi Super Shop</h3>
                        </div>
                    </div>
                </div>

                <div>
                    <div class="swiper CouponSlider ">
                        <div class="swiper-wrapper @if (count($couponSlider) <= 0) justify-content-center @endif">

                            @foreach ($couponSlider as $slider)
                                <!-- Slide item -->
                                <div class="swiper-slide">
                                    <a target="_blank" href="{{ $slider->url }}">
                                        <img src="{{ asset('assets/storage/banner/' . $slider->photo) }}"
                                            class="img-fluid coupon-img" alt="Asmi Promotion">
                                    </a>
                                </div>
                            @endforeach
                        </div>

                        <div class="swiper-pagination"></div>
                        <div class="swiper-button-next"></div>
                        <div class="swiper-button-prev"></div>
                    </div>
                </div>
            </div>
        </section>
    @endif
    {{-- Coupon Slider End --}}

    {{-- product Slider Start --}}
    <section class="product-slider">
        <div class="container ">
            <div class="row mb-2 px-3 my-2 my-lg-3">
                <div class="col-12 px-0 px-lg-2">
                    <div class="section-heading-title d-flex align-items-center justify-content-between">
                        <h3 style="text-transform: uppercase">Featured Products</h3>

                        <a class="text-orange" href="{{ route('featured.products') }}">View All <i
                                class="bi bi-arrow-right"></i></a>

                    </div>

                </div>
            </div>

            <div class="swiper ProductSlider product_slider_box">
                <div class="swiper-wrapper">
                    @php $decimal_point_settings = \App\CPU\Helpers::get_business_settings('decimal_point_settings') @endphp
                    @if ($featured_products->count() > 0)

                        <!-- Your product columns go here -->
                        @foreach ($featured_products as $product)
                            <div class="swiper-slide">
                                @include('web.products.productBox', ['dataCategory' => 'category1'])
                            </div>
                        @endforeach

                    @endif
                </div>

                <div class="swiper-pagination"></div>
                {{-- <div class="swiper-button-next"></div>
                <div class="swiper-button-prev"></div> --}}
            </div>
            {{-- ALL MODALS OUTSIDE --}}
            @foreach ($featured_products as $product)
                @include('web.products.product_modal')
            @endforeach
        </div>
    </section>


    {{-- Start Category Product Slider --}}
    @foreach ($home_categories as $category)
        @if (count($category->Products) > 0)
            <section class="product-slider">
                <div class="container ">
                    <div class="row mb-2 my-2 my-lg-3 px-3">
                        <div class="col-12 px-0 px-lg-2">
                            @if (count($category->Products) > 0)
                                <div class="section-heading-title d-flex align-items-center justify-content-between">
                                    <h3 style="text-transform: uppercase">{{ Str::limit($category['name'], 18) }}
                                    </h3>
                                    <a class="text-orange" href="{{ route('category.products', $category->slug) }}">View
                                        All Item <i class="bi bi-arrow-right"></i></a>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="swiper CategoryProductSlider product_slider_box slider_{{ $category->id }}">
                        <div class="swiper-wrapper">
                            @foreach ($category->Products->take(12) as $product)
                                <div class="swiper-slide">
                                    @include('web.products.productBox', [
                                        'dataCategory' => "category_$category->id",
                                    ])
                                </div>
                            @endforeach
                        </div>

                        <div class="swiper-pagination pagination_{{ $category->id }}"></div>
                    </div>
                    @foreach ($category->Products->take(12) as $product)
                        @include('web.products.product_modal')
                    @endforeach
                </div>
            </section>
        @endif
    @endforeach
    {{-- End Category Product Slider --}}

    <section class="our-brand">
        <div class="container">
            <div class="row mb-3">
                <div class="col-12">
                    <div class="section-heading-title d-flex align-items-center justify-content-center">
                        <h3>Our Brands</h3>
                    </div>
                </div>
            </div>
            <div class="swiper myBrandSwiper">
                <div class="swiper-wrapper">

                    @foreach ($ourBrands as $brand)
                        <div class="swiper-slide">
                            <a title="{{ $brand['name'] }}" href="{{ $brand['link'] }}" target="_blank"
                                class="brand-card">
                                <div class="brand-img">
                                    <img src="{{ asset('assets/frontend/images/brands/' . $brand['logo']) }}"
                                        alt="{{ $brand['name'] }}">
                                </div>
                                <p class="brand-name">{{ $brand['name'] }}</p>
                            </a>
                        </div>
                    @endforeach

                </div>

                <div class="swiper-pagination"></div>
                <div class="swiper-button-next"></div>
                <div class="swiper-button-prev"></div>
            </div>

        </div>
    </section>

    <!-- Start customer review Section -->
    <section>
        <div class="container">
            <div class="row">
                <div class="col-md-10 mx-auto">
                    <div class="row my-3">
                        <div class="col text-center">
                            <div class="section-heading-title">
                                <h5>Customers Review</h5>
                                <h3>What our Clients say</h3>
                                <div class="heading-border"></div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col mb-3 text-center">
                            <a data-bs-toggle="offcanvas" href="#clientReviewAdd" role="button"
                                aria-controls="clientReviewAdd" class="btn btn-sm btn-secondary">Write a
                                review <i class="fa fa-pencil-square-o"></i> </a>
                        </div>
                        @include('web.layouts.partials.client_review_canvas')
                    </div>
                    <div class="c-review-slider owl-carousel owl-theme">
                        <?php $clientReviews = \App\Models\ClientReview::where('status', true)->get(); ?>

                        @foreach ($clientReviews as $review)
                            <div class="item">
                                <div class="customer-review-box text-center">
                                    @if ($review->gender == 'male')
                                        <img src="{{ asset('assets/frontend') }}/images/slider/customer-review/smale.png"
                                            alt="Shopping Zone BD customer Review">
                                    @elseif($review->gender == 'female')
                                        <img src="{{ asset('assets/frontend') }}/images/slider/customer-review/sfemale.png"
                                            alt="Shopping Zone BD customer Review">
                                    @else
                                        <img src="{{ asset('assets/frontend') }}/images/slider/customer-review/img1.jpg"
                                            alt="Shopping Zone BD customer Review">
                                    @endif
                                    <div class="customer-name mt-2">
                                        <h3>{{ htmlspecialchars($review['name']) }}</h3>
                                    </div>
                                    <div class="customer-sms">
                                        <p>{{ htmlspecialchars($review['comment']) }}</p>
                                    </div>
                                    <div class="customer-review">
                                        @for ($i = 0; $i < 5; $i++)
                                            <i class="fa fa-star{{ $i < $review->ratings ? '' : '-o' }}"></i>
                                        @endfor
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="our-outlet-section">
        <div class="container">
            <div class="row align-items-center my-5">
                <div class="col-md-12">
                    <div class="row g-4">
                        @foreach ($branchs as $branch)
                            <div class="col-lg-4 col-md-6">
                                <div>
                                    <a target="_blank" href="{{ $branch->map_url ? $branch->map_url : '' }}"
                                        class="d-flex align-items-center gy-4" target="_blank">
                                        <i class="fa fa-map-marker text-muted" style="font-size: 24px"
                                            aria-hidden="true"></i>
                                        <div class="ml-3">
                                            <h6 class="subscribe-title mb-0" style="font-size: 17px; font-weight: 700;"
                                                class="mb-0">
                                                {{ $branch->name }}</h6>
                                            <address style="font-size: 12px; line-height: 20px" class="text-muted">
                                                {!! $branch->address !!}
                                            </address>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                {{-- <div class="col-lg-5 mx-auto mt-4">
                    <h6 style="font-size: 22px; font-weight: 500;  margin: 0 auto; " class="text-center newslater-title">
                        Join our newsletter for latest update
                        on discount and
                        offer</h6>
                    <form action="{{ route('subscription') }}" method="POST" class="mt-3">
                        @csrf
                        <div class="input-group mb-3">

                            <input type="email" required class="form-control subscribe-input"
                                placeholder="Your Email Here" name="email" aria-describedby="button-addon1">

                            <button class="btn bg-orange" type="submit" id="button-addon1"
                                style="border-radius: 0 25px 25px 0 !important;">
                                Subscribe
                            </button>

                        </div>
                        @error('email')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </form>
                </div> --}}

            </div>
        </div>
    </section>
    <script>
        window.addEventListener('load', function() {
            @foreach ($home_categories as $category)
                new Swiper(".slider_{{ $category->id }}", {
                    loop: true,
                    autoplay: {
                        delay: 3000,
                        disableOnInteraction: false,
                        pauseOnMouseEnter: true,
                    },
                    pagination: {
                        el: ".pagination_{{ $category->id }}",
                        clickable: true,
                    },
                    breakpoints: {
                        0: {
                            slidesPerView: 2,
                            spaceBetween: 10
                        },
                        576: {
                            slidesPerView: 2,
                            spaceBetween: 10
                        },
                        768: {
                            slidesPerView: 3,
                            spaceBetween: 10
                        },
                        992: {
                            slidesPerView: 5
                        },
                    },
                });
            @endforeach
        });
    </script>
@endsection
@push('scripts')
    <script>
        cartQuantityInitialize();
    </script>
    <script>
        $(document).ready(function() {
            $('#close-pModal').on('click', function() {
                $('#popup-modal').modal('hide');
            });
        });
    </script>
@endpush
