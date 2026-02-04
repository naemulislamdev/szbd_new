@extends('web.layouts.app')
@section('title', 'Welcome To' . ' ' . $web_config['name']->value)

@push('css_or_js')
    <meta property="og:image" content="{{ asset('assets/storage/company') }}/{{ $web_config['web_logo']->value }}" />
    <meta property="og:title" content="Best Online Marketplace In Bangladesh {{ $web_config['name']->value }} Home" />
    <meta property="og:url" content="{{ env('APP_URL') }}">
    <meta property="og:description" content="{!! substr(strip_tags($web_config['about']->value), 0, 100) !!}">

    <meta property="twitter:card" content="{{ asset('assets/storage/company') }}/{{ $web_config['web_logo']->value }}" />
    <meta property="twitter:title" content="Welcome To {{ $web_config['name']->value }} Home" />
    <meta property="twitter:url" content="{{ env('APP_URL') }}">
    <meta property="twitter:description" content="{!! substr(strip_tags($web_config['about']->value), 0, 100) !!}">
@endpush
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

    .category .category-carosel img {
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
</style>
@section('content')

    @include('web.layouts.partials._modals')
    <!------start  header main slider-->
    @include('web.layouts.partials.slider')
    {{-- New Arrivals Section Start --}}

    {{-- To Banner Section Start --}}
    <section class="top-banner">
        <div class="container-fluid">
            @foreach (\App\Models\Banner::where('banner_type', 'Main Section Banner')->where('published', 1)->orderBy('id', 'desc')->take(3)->get() as $banner)
                <div class="row my-3">
                    <div class="col-md-12">
                        <div class="big-banner">
                            <a href="{{ $banner['url'] }}">
                                <img style="max-height: 100%; width: 100%;"
                                    onerror="this.src='{{ asset('assets/frontend/img/image-place-holder.png') }}'"
                                    src="{{ asset('assets/storage/banner') }}/{{ $banner['photo'] }}"
                                    alt="{{ @$banner['photo'] }}" width="100%;">
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </section>
    {{-- Top Banner Section End --}}


    <?php
    $company_mobile_logo = \App\Models\BusinessSetting::where('type', 'company_web_logo')->first()->value;
    ?>

    {{-- Category Section Start --}}
    <section class="category my-5 d-none d-lg-block">
        <div class="container px-0">
            <div class="row mb-5">
                <div class="col text-center">
                    <div class="section-heading-title position-relative z-30">
                            <div>
                                <h3>Categories</h3>
                            </div>
                        <div class="heading-border"></div>
                    </div>
                </div>
            </div>
            <div class="owl-carousel category-carosel mt-4 mt-lg-4 ">
                @foreach ($categories as $category)
                    <div style="background: #f26d21" class="category-item card">
                        <a href="{{ route('category.products', $category->slug) }}" class="">
                            <img src='{{ asset("assets/storage/category/$category->icon") }}' alt="{{ $category->name }}">
                            <div class="card-body text-center">
                                <h5 class="card-title text-white mb-0">{{ $category['name'] }}</h5>
                                {{-- <p class="card-text text-white p-0 m-0">{{ $category->Products->count() }} Products</p> --}}
                            </div>
                        </a>
                    </div>
                @endforeach

            </div>
        </div>
    </section>
    {{-- mobile category --}}
    <section class="mobile-category mt-4 d-block d-lg-none">
        <div class="container">
            <div>
                <div class=" text-center">
                    <div class="section-heading-title position-relative z-30 ">
                        <div class="row align-items-center">
                            <div style="box-shadow: rgba(0, 0, 0, 0.19) 0px 10px 20px, rgba(0, 0, 0, 0.23) 0px 6px 6px;"
                                class="col-4 d-flex justify-content-between align-items-center bg-white py-2 px-0 rounded">
                                <div style="background: #fff;;">
                                    <a href="{{ route('home') }}">
                                        <img style="max-width: 40px; height: auto;"
                                            src="{{ asset("assets/storage/company/$company_mobile_logo") }}"
                                            onerror="this.src='{{ asset('assets/frontend/img/image-place-holder.png') }}'"
                                            alt="">
                                    </a>
                                </div>
                                <div class="bg-white rounded">
                                    <a class="store-btn appstore" target="_blank"
                                        href="https://play.google.com/store/apps/details?id=com.shoppingzonebd.android">
                                        <img src="{{ asset('assets/front-end') }}/images/logo/google-play.jpg"
                                            alt="Google Play Store" style="width: 30px; height: auto;">
                                    </a>
                                </div>
                                <div class="bg-white rounded">
                                    <a class="store-btn appstore" href="#">
                                        <img src="{{ asset('assets/front-end') }}/images/logo/apple_single.png"
                                            alt="apple Store" style="width: 30px; height: auto;">
                                    </a>
                                </div>
                            </div>
                            <h3 class="col-4 small-title">Categories</h3>
                            <div style="box-shadow: rgba(0, 0, 0, 0.19) 0px 10px 20px, rgba(0, 0, 0, 0.23) 0px 6px 6px;"
                                class="col-4 d-flex justify-content-between align-items-center bg-white py-2 px-0 rounded">
                                <div style="background: #fff; ">
                                    <a href="https://asmishop.com/">
                                        <img style="max-width: 40px; height: auto;"
                                            src="{{ asset('assets/front-end') }}/images/logo/asmi.png"
                                            onerror="this.src='{{ asset('assets/frontend/img/image-place-holder.png') }}'"
                                            alt="">
                                    </a>
                                </div>
                                <div class="bg-white rounded">
                                    <a class="store-btn appstore" target="_blank"
                                        href="https://play.google.com/store/apps/details?id=com.asmishop.android">
                                        <img src="{{ asset('assets/front-end') }}/images/logo/google-play.jpg"
                                            alt="Google Play Store" style="width: 30px; height: auto;">
                                    </a>
                                </div>
                                <div class="bg-dark rounded">
                                    <a class="store-btn appstore" href="#">
                                        <img src="{{ asset('assets/front-end') }}/images/logo/apple_single.png"
                                            alt="apple Store" style="width: 30px; height: auto;">
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="heading-border"></div>
                    </div>
                </div>
            </div>
            <div class="row gap-0">
                @foreach ($categories as $category)
                    <div class="col-4 px-2 pb-3">
                        <div style="background: #f26d21" class="category-item card">
                            <a href="{{ route('category.products', $category->slug) }}" class="stretched-link">
                                <div class="image-box">
                                    <img src="{{ asset("assets/storage/category/$category->icon") }}"
                                        alt="{{ $category->name }}">
                                </div>
                                <div class="card-body text-center p-1">
                                    <h5 class="card-title text-white mb-0">{{ $category['name'] }}</h5>
                                    {{-- <p class="card-text text-white p-0 m-0 text-dark">{{ $category->Products->count() }}
                                        Products</p> --}}
                                </div>
                            </a>
                        </div>
                    </div>
                @endforeach


            </div>
        </div>
    </section>



    <!------Start Product section----->
    <section class="py-3">
        <div class="container">
            {{-- @include('web.layouts.partials.product_filter') --}}

            <div class="row mb-3">
                <div class="col text-center">
                    <div class="section-heading-title">
                        <h3>Our Feature Products</h3>
                        <div class="heading-border"></div>
                    </div>
                    <div class="grid-controls">
                        <button class="grid-btn" data-columns="6" data-category="category1">
                            <div class="grid-icon"></div>
                            <div class="grid-icon"></div>
                        </button>
                        <button class="grid-btn" data-columns="4" data-category="category1">
                            <div class="grid-icon"></div>
                            <div class="grid-icon"></div>
                            <div class="grid-icon"></div>
                        </button>
                        <button class="grid-btn" data-columns="3" data-category="category1">
                            <div class="grid-icon"></div>
                            <div class="grid-icon"></div>
                            <div class="grid-icon"></div>
                            <div class="grid-icon"></div>
                        </button>
                        <button class="grid-btn" data-columns="2" data-category="category1">
                            <div class="grid-icon"></div>
                            <div class="grid-icon"></div>
                            <div class="grid-icon"></div>
                            <div class="grid-icon"></div>
                            <div class="grid-icon"></div>
                            <div class="grid-icon"></div>
                        </button>
                    </div>
                    <div class="grid-controls mobile-grid-controls">
                        <button class="grid-btn grid-btn-mobile" data-columns="12" data-category="category1">
                            <div class="grid-icon"></div>
                        </button>
                        <button class="grid-btn grid-btn-mobile" data-columns="6" data-category="category1">
                            <div class="grid-icon"></div>
                            <div class="grid-icon"></div>
                        </button>
                    </div>
                </div>
            </div>
            @php($decimal_point_settings = \App\CPU\Helpers::get_business_settings('decimal_point_settings'))
            @if ($featured_products->count() > 0)
                <div class="row product-grid">
                    <!-- Your product columns go here -->
                    @foreach ($featured_products as $product)
                        @include('web.products.product_box', ['dataCategory' => 'category1'])
                    @endforeach
                </div>
            @endif

            @foreach ($home_categories as $category)
                <div class="row mb-3">
                    <div class="col text-center">
                        <div class="section-heading-title">
                            <h3>{{ Str::limit($category['name'], 18) }}</h3>
                            <div class="heading-border"></div>
                        </div>
                        <div class="grid-controls">
                            <button class="grid-btn" data-columns="6" data-category="category_{{ $category->id }}">
                                <div class="grid-icon"></div>
                                <div class="grid-icon"></div>
                            </button>
                            <button class="grid-btn" data-columns="4" data-category="category_{{ $category->id }}">
                                <div class="grid-icon"></div>
                                <div class="grid-icon"></div>
                                <div class="grid-icon"></div>
                            </button>
                            <button class="grid-btn" data-columns="3" data-category="category_{{ $category->id }}">
                                <div class="grid-icon"></div>
                                <div class="grid-icon"></div>
                                <div class="grid-icon"></div>
                                <div class="grid-icon"></div>
                            </button>
                            <button class="grid-btn" data-columns="2" data-category="category_{{ $category->id }}">
                                <div class="grid-icon"></div>
                                <div class="grid-icon"></div>
                                <div class="grid-icon"></div>
                                <div class="grid-icon"></div>
                                <div class="grid-icon"></div>
                                <div class="grid-icon"></div>
                            </button>
                        </div>
                        <div class="grid-controls mobile-grid-controls">
                            <button class="grid-btn grid-btn-mobile" data-columns="12"
                                data-category="category_{{ $category->id }}">
                                <div class="grid-icon"></div>
                            </button>
                            <button class="grid-btn grid-btn-mobile" data-columns="6"
                                data-category="category_{{ $category->id }}">
                                <div class="grid-icon"></div>
                                <div class="grid-icon"></div>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="row product-grid">
                    <!-- Your product columns go here -->
                    @foreach ($category->Products as $key => $product)
                        @if ($key < 12)
                            @include('web.products.product_box', [
                                'dataCategory' => "category_$category->id",
                            ])
                        @endif
                    @endforeach
                </div>
            @endforeach
            @foreach (\App\Models\Banner::where('banner_type', 'Footer Banner')->where('published', 1)->orderBy('id', 'desc')->take(3)->get() as $banner)
                <div class="row my-3">
                    <div class="col-md-12">
                        <div class="big-banner">
                            <a href="{{ $banner['url'] }}">
                                <img onerror="this.src='{{ asset('assets/frontend/img/image-place-holder.png') }}'"
                                    src="{{ asset('assets/storage/banner') }}/{{ $banner['photo'] }}"
                                    alt="{{ @$banner['photo'] }}" width="100%;">
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
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
                                            alt="">
                                    @elseif($review->gender == 'female')
                                        <img src="{{ asset('assets/frontend') }}/images/slider/customer-review/sfemale.png"
                                            alt="">
                                    @else
                                        <img src="{{ asset('assets/frontend') }}/images/slider/customer-review/img1.jpg"
                                            alt="">
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
    <!-- Start Newslater Section -->
    <section class="newslater-section">
        <div class="container">
            <div class="row">
                <div class="col-md-10 mx-auto">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="newslater-text">
                                <h4>SALE 20% OFF ALL STORE</h4>
                                <h2>Subscribe our Newsletter</h2>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="newslater-input">
                                <form action="{{ route('subscription') }}" method="post">
                                    @csrf
                                    <div class="input-group mb-3 w-100">
                                        <input type="text" class="form-control" placeholder="Your Email Address"
                                            name="subscription_email" required>
                                        <div class="input-group-append">
                                            <button type="submit" class="btn btn-dark"
                                                id="basic-addon2">subscribe</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
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
