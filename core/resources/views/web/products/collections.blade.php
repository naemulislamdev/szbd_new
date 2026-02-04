@extends('layouts.front-end.app')
@section('title', 'Trend-collections')

<style>
    @import url('https://fonts.maateen.me/solaiman-lipi/font.css');

    .trending a,
    .btn,
    span.tk {
        font-family: 'SolaimanLipi', sans-serif;
    }

    .btn-orange {
        background: #ff5d00 !important;
        color: #fff !important;
    }

    .btn.btn-orange:focus {
        outline: 0;
        box-shadow: 0 0 0 .2rem rgba(255, 93, 0, .25);
    }

    .product-img-container {
        height: 390px;
        overflow: hidden;
        position: relative;
        /* box-sizing: content-box */
    }

    .product-img-container img {
        width: 100% !important;
        height: 100% !important;
        object-fit: contain;
        position: absolute;
        border-radius: 10px 10px 0 0;
        transition: transform 0.4s ease;
    }

    .product-card {
        border: none;
        box-shadow: rgba(0, 0, 0, 0.24) 0px 3px 8px;
        border-radius: 10px !important;
        transition: 0.5s ease;
    }

    .product-card.card {
        border: none !important;
    }

    .product-title {
        font-size: 18px;
        font-weight: 600;
        line-height: 1.3;
        margin-bottom: 10px;
        color: #1a1a1a;
    }

    .product-text {
        color: #ff5d00;
        font-size: 18px;
        font-weight: 600;
        line-height: 1.5;
        text-align: center;
        overflow: hidden;
    }

    .product-img-container .add-to-cart {
        position: absolute;
        left: 0;
        bottom: -32px;
        width: 100%;
        border: none;
        padding: 4px;
        background: #ff5d00;
        color: #fff;
        cursor: pointer;
        transition: all 0.4s ease;
    }

    .product-card:hover {
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15);
    }

    .product-card:hover .product-img-container img {
        transform: scale(1.1);
    }

    .product-card:hover .product-img-container .add-to-cart {
        bottom: 0;
    }

    .product-card:hover .product-title {
        color: #ff5d00;
    }

    .owl-nav button:focus {
        outline: 0;
        box-shadow: 0 0 0 .2rem rgba(255, 93, 0, .25);
    }

    .owl-nav button {
        position: absolute;
        top: 45%;
        transform: translateY(-50%);
        background: #ff5d00 !important;
        color: #fff !important;
        border: none;
        outline: none;
        border-radius: 5px;
        width: 40px;
        height: 40px;
        font-size: 18px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: 0.3s;
    }

    .owl-carousel .owl-nav button.owl-next {
        right: 0;
        top: 50%;
    }

    .owl-carousel .owl-nav button.owl-prev {
        left: 0;
        top: 50%;
    }

    .first-image-col {
        max-width: 20% !important;
    }
    .product-box-col-2 {
    height: 460px !important;
    border-radius: 10px;
}

    @media (max-width: 768px) {
        .product-title {
            color: #ff5d00;
            font-size: 15px;
        }

        .product-img-container img {
            object-fit: cover;
        }

        .product-img-container {
            height: 280px;
            overflow: hidden;
            position: relative;
        }

        .product-text {
            font-size: 15px;
        }

        .twin-btn {
            flex-direction: column;
            gap: 10px;
        }

        .first-image-col {
            max-width: 100% !important;
        }
           .product-box-col-2 {
            height: 440px !important;
            border-radius: 10px;
        }

    }

 

</style>

@section('content')
    {{-- =========================== Banner Section Start ===================== --}}
    @if ($main_banners)
        <section class="header-slider-section mt-1 mt-lg-3">
            <div id="carouselExampleIndicators" class="carousel slide position-relative container " data-ride="carousel"
                data-interval="3000">
                <ol class="carousel-indicators">
                    @foreach ($main_banners as $key => $banner)
                        <li data-target="#carouselExampleIndicators" data-slide-to="{{ $key }}"
                            class="{{ $key == 0 ? 'active' : '' }}"></li>
                    @endforeach
                </ol>
                <div class="carousel-inner">
                    @foreach ($main_banners as $key => $banner)
                        <div class="carousel-item {{ $key == 0 ? 'active' : '' }}">
                            <div class="main-slider  ">

                                <a href="#">
                                    <img class="d-block w-100 rounded-0 rounded-lg"
                                        src="{{ asset('storage/deal/main-banner') }}/{{ $banner }}"
                                        alt="banner image">
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
                <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="sr-only">Previous</span>
                </a>
                <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="sr-only">Next</span>
                </a>
            </div>
        </section>
    @endif

    {{-- =========================== Banner Section End ===================== --}}
    <section class="my-3 trending career">
        <div class="container " style="max-width: 1200px; ">
            <div class="row ">
                <div class="col text-center">
                    <div class="section-heading-title">
                        <h3>New Trending Collections</h3>
                        <div class="heading-border"></div>
                    </div>
                </div>
            </div>
            <div class=" pt-4">
                <div class="row">
                    <div class="col-lg-4 product-column mx-auto first-image-col" data-category="{{ $dataCategory ?? '' }}">
                        <div class="product-box product-box-col-2" data-category="{{ $dataCategory ?? '' }}">
                            <input type="hidden" name="quantity" value="{{ $first_product->minimum_order_qty ?? 1 }}"
                                min="{{ $first_product->minimum_order_qty ?? 1 }}" max="100">
                            <div class="product-image2 product-image2-col-2" data-category="{{ $dataCategory ?? '' }}">
                                @if ($first_product->discount > 0)
                                    <div class="discount-box float-end">
                                        <span>
                                            @if ($first_product->discount_type == 'percent')
                                                {{ $first_product->discount }}%
                                            @elseif($first_product->discount_type == 'flat')
                                                {{ \App\CPU\Helpers::currency_converter($first_product->discount) }}৳
                                            @endif
                                        </span>
                                    </div>
                                @endif
                                <a href="{{ route('product', $first_product->slug) }}">
                                    <!-- ✅ Lazy Loading Image -->
                                    <img style="object-fit: contain; transform: scale(1.3)" class="img-fluid lazy-image"
                                        loading="lazy"
                                        src="data:image/svg+xml,%3Csvg width='300' height='300' xmlns='http://www.w3.org/2000/svg'%3E%3C/svg%3E"
                                        data-src="{{ \App\CPU\ProductManager::product_image_path('thumbnail') }}/{{ $first_product['thumbnail'] }}"
                                        alt="{{ $first_product['name'] }}">
                                </a>

                            </div>
                            <div class="product-content">
                                <h3 class="title"><a
                                        href="{{ route('product', $first_product->slug) }}">{{ Str::limit($first_product['name'], 50) }}</a>
                                </h3>
                                <div class="price d-flex justify-content-center align-content-center">
                                    @if ($first_product->discount > 0)
                                        <span
                                            class="mr-2">৳ {{ \App\CPU\Helpers::currency_converter(
                                                $first_product->unit_price - \App\CPU\Helpers::get_product_discount($first_product, $first_product->unit_price),
                                            ) }}</span>
                                        <del>{{ \App\CPU\Helpers::currency_converter($first_product->unit_price) }}</del>
                                    @else
                                        <span>৳ {{ \App\CPU\Helpers::currency_converter($first_product->unit_price) }}</span>
                                    @endif
                                </div>
                                <button type="button" style="cursor: pointer;" class="btn btn-primary"
                                    onclick="buy_now('form-{{ $first_product->id }}')">অর্ডার করুন</button>
                            </div>
                        </div>
                    </div>
                    <!-- AddToCart Modal -->
                    <div class="modal fade" id="addToCartModal_{{ $first_product->id }}" tabindex="-1" role="dialog"
                        aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <form id="form-{{ $first_product->id }}" class="mb-2">
                                @csrf
                                <input type="hidden" name="id" value="{{ $first_product->id }}">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="product-modal-box d-flex align-items-center mb-3">
                                            <div class="img mr-3">
                                                <img src="{{ \App\CPU\ProductManager::product_image_path('thumbnail') }}/{{ $first_product['thumbnail'] }}"
                                                    alt="{{ $first_product['name'] }}" style="width: 80px;">
                                            </div>
                                            <div class="p-name">
                                                <h5 class="title">{{ Str::limit($first_product['name'], 50) }}</h5>
                                                <span
                                                    class="mr-2">{{ \App\CPU\Helpers::currency_converter(
                                                        $first_product->unit_price - \App\CPU\Helpers::get_product_discount($first_product, $first_product->unit_price),
                                                    ) }}</span>
                                            </div>
                                        </div>
                                        @if (count(json_decode($first_product->colors)) > 0)
                                            <div class="row">
                                                <div class="col-12">
                                                    <h4>Color</h4>
                                                </div>
                                                <div class="col-12">
                                                    <div class="d-flex">
                                                        @foreach (json_decode($first_product->colors) as $key => $color)
                                                            <div class="v-color-box">
                                                                <input type="radio"
                                                                    id="{{ $first_product->id }}-color-{{ $key }}"
                                                                    name="color" value="{{ $color }}"
                                                                    @if ($key == 0) checked @endif>
                                                                <label style="background: {{ $color }}"
                                                                    for="{{ $first_product->id }}-color-{{ $key }}"
                                                                    class="color-label"></label>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                        @endif

                                        @if (count(json_decode($first_product->choice_options)) > 0)
                                            @foreach (json_decode($first_product->choice_options) as $key => $choice)
                                                <div class="row mb-3">
                                                    <div class="col-12">
                                                        <h4 style="font-size: 18px; margin:0;">{{ $choice->title }}
                                                        </h4>
                                                    </div>
                                                    <div class="col-12">
                                                        <div class="d-flex">
                                                            @foreach ($choice->options as $key => $option)
                                                                <div class="v-size-box">
                                                                    <input type="radio"
                                                                        id="{{ $first_product->id }}-size-{{ $key }}"
                                                                        name="{{ $choice->name }}"
                                                                        value="{{ $option }}"
                                                                        @if ($key == 0) checked @endif>
                                                                    <label
                                                                        for="{{ $first_product->id }}-size-{{ $key }}"
                                                                        class="size-label">{{ $option }}</label>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @endif
                                        <div class="row">
                                            <div class="col-md-10 mx-auto">
                                                <div class="product-quantity d-flex align-items-center">
                                                    <div class="input-group input-group--style-2 pr-3"
                                                        style="width: 160px;">
                                                        <span class="input-group-btn">
                                                            <button class="btn btn-number" type="button"
                                                                data-type="minus" data-field="quantity"
                                                                disabled="disabled" style="padding: 10px">
                                                                -
                                                            </button>
                                                        </span>
                                                        <input type="text" name="quantity"
                                                            class="form-control input-number text-center cart-qty-field"
                                                            placeholder="1" value="1" min="1"
                                                            max="100">
                                                        <span class="input-group-btn">
                                                            <button class="btn btn-number" type="button"
                                                                data-type="plus" data-field="quantity"
                                                                style="padding: 10px">
                                                                +
                                                            </button>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <a href="{{ route('product', $first_product->slug) }}"
                                            class="btn btn-secondary">View
                                            Details</a>
                                        <button type="button" class="btn btn-danger"
                                            onclick="addToCart('form-{{ $first_product->id }}')">Add To Cart</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            {{-- Mobile trending  --}}
            {{-- Mobile trending  --}}
            @if ($withSlide)
                <div class="d-block d-lg-none mt-3">
                    <div class="owl-carousel trending-carousel mt-4 mt-lg-4">
                        @foreach ($subProducts as $product)
                            <div class="product-box product-box-col-2 card shadow-lg product-card"
                                data-category="category">
                                <input type="hidden" name="quantity" value="{{ $product->minimum_order_qty ?? 1 }}"
                                    min="{{ $product->minimum_order_qty ?? 1 }}" max="100">
                                <div class="product-image2 product-image2-col-2" data-category="category">
                                    @if ($product->discount > 0)
                                        <div class="discount-box float-end">
                                            <span>
                                                @if ($product->discount_type == 'percent')
                                                    {{ $product->discount }}%
                                                @elseif($product->discount_type == 'flat')
                                                    {{ \App\CPU\Helpers::currency_converter($product->discount) }}৳
                                                @endif
                                            </span>
                                        </div>
                                    @endif
                                    <a href="{{ route('product', $product->slug) }}">
                                        <!-- ✅ Lazy Loading Image -->
                                        <img class="img-fluid lazy-image" loading="lazy"
                                            src="data:image/svg+xml,%3Csvg width='300' height='300' xmlns='http://www.w3.org/2000/svg'%3E%3C/svg%3E"
                                            data-src="{{ \App\CPU\ProductManager::product_image_path('thumbnail') }}/{{ $product['thumbnail'] }}"
                                            alt="{{ $product['name'] }}">
                                    </a>

                                </div>
                                <div class="product-content">
                                    <h3 class="title"><a
                                            href="{{ route('product', $product->slug) }}">{{ Str::limit($product['name'], 21) }}</a>
                                    </h3>
                                    <div class="price d-flex justify-content-center align-content-center">
                                        @if ($product->discount > 0)
                                            <span
                                                class="mr-2">৳{{ \App\CPU\Helpers::currency_converter(
                                                    $product->unit_price - \App\CPU\Helpers::get_product_discount($product, $product->unit_price),
                                                ) }}</span>
                                            <del>{{ \App\CPU\Helpers::currency_converter($product->unit_price) }}</del>
                                        @else
                                            <span>৳{{ \App\CPU\Helpers::currency_converter($product->unit_price) }}</span>
                                        @endif
                                    </div>
                                    <button type="button" style="cursor: pointer;" class="btn btn-primary"
                                        onclick="buy_now('form-{{ $product->id }}')">অর্ডার করুন</button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @else
                <div class="d-block d-lg-none mt-3">
                    <div class="row">
                        @foreach ($subProducts as $key => $product)
                            @include('web-views.products.product_box_2', ['classBox' => 'col-md-2'])
                        @endforeach
                    </div>
                </div>
            @endif
            <div class="d-none d-lg-block mt-4">
                <div class="row">
                    @foreach ($subProducts as $key => $product)
                        @include('web-views.products.product_box_2', ['classBox' => 'col-md-2'])
                    @endforeach
                </div>
            </div>

        </div>
    </section>

@endsection
