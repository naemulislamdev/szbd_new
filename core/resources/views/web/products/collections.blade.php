@extends('web.layouts.app')
@section('title', 'Trending Collection | ' . $web_config['name']->value)

@section('meta_description', 'Shop our trending collection featuring the latest clothing and skincare products.')


<style>
    @import url('https://fonts.maateen.me/solaiman-lipi/font.css');

    .trending a,
    .btn,
    span.tk {
        font-family: 'SolaimanLipi', sans-serif;
    }

    .btn-primary {
        position: static !important;
        margin: 0;
    }

    .product-box-col-2 {
        height: auto !important;
    }

    .btn-orange {
        background: #ff5d00 !important;
        color: #fff !important;
    }

    .btn-orange:hover {
        background-color: #ff5d00;
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

        #first-col {
            width: 65% !important;
        }
        .withoutSlideProduct .col-sm-6.product-column.col-md-3 .product-image2-col-2 {
                height: 312px;
            }

    }


    .product-box>.product-image2>a>img {
    object-fit: contain!important;
    transform: scale(1.2);
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
                                <a href="#" style="display: block !important;">
                                    <img class="d-block w-100 rounded-0 rounded-lg"
                                        src="{{ asset('assets/storage/deal/main-banner/') }}/{{ $banner }}" alt="">
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
                    {{-- <div class="col-lg-4 col-md-4 col-sm-12 mb-4 mx-auto">
                        <div class="card shadow-lg product-card">
                            <div class="product-img-container">
                                <a href="{{ route('product', $first_product->slug) }}">
                                    <img class="card-img-top"
                                        src="{{ \App\CPU\ProductManager::product_image_path('thumbnail') }}/{{ $first_product['thumbnail'] }}"
                                        alt="{{ $first_product['name'] }}">
                                </a>

                            </div>

                            <div class="card-body">
                                <a href="{{ route('product', $first_product->slug) }}">
                                    <h4 class="product-title">
                                        {{ Str::limit($first_product->name, 50) }}
                                    </h4>
                                </a>
                                @if ($first_product->discount > 0)
                                    <span class="product-text">৳
                                        {{ \App\CPU\Helpers::currency_converter(
                                            $first_product->unit_price - \App\CPU\Helpers::get_product_discount($first_product, $first_product->unit_price),
                                        ) }}</span>
                                    <del>৳ {{ \App\CPU\Helpers::currency_converter($first_product->unit_price) }}</del>
                                @else
                                    <span class="product-text">৳
                                        {{ \App\CPU\Helpers::currency_converter($first_product->unit_price) }}</span>
                                @endif

                            </div>
                            <div style="gap: 10px"
                                class="sm-button d-flex justify-content-center justify-content-lg-between p-3 ">
                                <a href="{{ route('product', $first_product->slug) }}" class="btn btn-info text-white"><i
                                        class="fa fa-eye mr-2"></i>বিস্তারিত দেখুন</a>
                                <button class="btn  btn-orange text-white"
                                    onclick="buy_now('form-{{ $first_product->id }}')"><i
                                        class="fa fa-cart-plus mr-2"></i>অর্ডার
                                    করুন</button>
                            </div>
                        </div>
                    </div> --}}
                    {{-- product --}}
                    <div id="first-col" class="col-lg-3 col-sm-6 product-column mx-auto "
                        data-category="{{ $dataCategory ?? '' }}">
                        <div class="product-box product-box-col-2 card shadow-lg product-card"
                            data-category="{{ $dataCategory ?? '' }}">
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
                                    <img class="img-fluid lazy-image" loading="lazy"
                                        src="data:image/svg+xml,%3Csvg width='300' height='300' xmlns='http://www.w3.org/2000/svg'%3E%3C/svg%3E"
                                        data-src="{{ \App\CPU\ProductManager::product_image_path('thumbnail') }}/{{ $first_product['thumbnail'] }}"
                                        alt="{{ $first_product['name'] }}">
                                </a>
                                <ul class="social">
                                    <li><a href="{{ route('product', $first_product->slug) }}" data-tip="Quick View"><i
                                                class="fa fa-eye"></i></a></li>

                                    <li><a style="cursor: pointer" data-toggle="modal"
                                            data-target="#addToCartModal_{{ $first_product->id }}"
                                            data-tip="Add to Cart"><i class="fa fa-shopping-cart"></i></a>
                                    </li>
                                </ul>
                            </div>
                            <div class="product-content">
                                <h3 class="title"><a
                                        href="{{ route('product', $first_product->slug) }}">{{ Str::limit($first_product['name'], 50) }}</a>
                                </h3>
                                <div class="price d-flex justify-content-center align-content-center">
                                    @if ($first_product->discount > 0)
                                        <span
                                            class="mr-2">{{ \App\CPU\Helpers::currency_converter(
                                                $first_product->unit_price - \App\CPU\Helpers::get_product_discount($first_product, $first_product->unit_price),
                                            ) }}</span>
                                        <del>{{ $first_product->unit_price }}</del>
                                    @else
                                        <span>{{ $first_product->unit_price }}</span>
                                    @endif
                                </div>
                                <button type="button" style="cursor: pointer;" class="btn btn-primary w-100"
                                    onclick="buy_now('form-{{ $first_product->id }}')">অর্ডার করুন</button>
                            </div>
                        </div>
                    </div>
                    {{-- <div class="modal fade" id="addToCartModal_{{ $first_product->id }}" tabindex="-1" role="dialog"
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
                                                    class="mr-2">{{
                                                        $first_product->unit_price - \App\CPU\Helpers::get_product_discount($first_product, $first_product->unit_price),
                                                     }}</span>
                                            </div>
                                        </div>
                                        @if (count($first_product->colors) > 0)
                                            <div class="row">
                                                <div class="col-12">
                                                    <h4>Color</h4>
                                                </div>
                                                <div class="col-12">
                                                    <div class="d-flex">
                                                        @foreach ($first_product->colors as $key => $color)
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

                                        @if (count($first_product->choice_options) > 0)
                                            @foreach ($first_product->choice_options as $key => $choice)
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
                    </div> --}}
                    <!-- AddToCart Modal -->
<div class="modal fade addToCartModalCls" id="addToCartModal_{{ $first_product->id }}" tabindex="-1" role="dialog"
    aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog  modal-dialog-centered modal-dialog-scrollable" role="document">
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
                            <img class="rounded main-image"
                                src="{{ asset('assets/storage/product/thumbnail') }}/{{ $first_product['thumbnail'] }}"
                                alt="{{ $first_product['name'] }}" style="width: 75px;">
                        </div>
                        <div class="p-name">
                            <h5 class="title">{{ Str::limit($first_product['name'], 50) }}</h5>
                            <span style="color: #ff5d00; font-size: 22px;" class="mr-2"><span
                                    style="font-size: 30px;">৳</span>
                                {{
                                    $first_product->unit_price - \App\CPU\Helpers::get_product_discount($first_product, $first_product->unit_price),
                                 }}</span>
                        </div>
                    </div>
                @if (!empty($colors) && count($colors) > 0)
    <div class="row mb-4 mt-3">
        <div class="col-12 mb-3">
            <h4 style="font-size: 18px;">Color</h4>
        </div>

        @if (!empty($colorVariants))
            <div class="col-12 mb-3 mt-4">
                <div class="d-flex">
                    @foreach ($colorVariants as $key => $color)
                        <div class="v-color-box position-relative">

                            <input type="radio"
                                   id="{{ $first_product->id }}-color-{{ $key }}"
                                   name="color"
                                   value="{{ $color['code'] }}"
                                   @checked($key === 0)>

                            <label for="{{ $first_product->id }}-color-{{ $key }}"
                                   class="color-label p-0"
                                   style="background-color: {{ $color['code'] }}; overflow: hidden;">
                                <img src="{{ asset($color['image']) }}"
                                     alt="{{ $color['color'] }}"
                                     style="max-width:100%; height:auto;">
                            </label>

                            <span style="
                                height:20px;width:20px;border-radius:50%;
                                position:absolute;right:-11px;top:-49px;
                                background:{{ $color['code'] }}">
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
@endif



                    @if (count($first_product->choice_options) > 0)
                        @foreach ($choiceOptions as $key => $choice)
                            <div class="row mb-3">
                                <div class="col-12">
                                    <h4 style="font-size: 18px; margin:0;">{{ $choice['title'] }}
                                    </h4>
                                </div>
                                <div class="col-12 mt-3">
                                    <div class="d-flex">
                                        @foreach ($choice['options'] as $key => $option)
                                            <div class="v-size-box">
                                                <input type="radio"
                                                    id="{{ $first_product->id }}-size-{{ $key }}"
                                                    name="{{ $choice['name'] }}" value="{{ $option }}"
                                                    @if ($key == 0) checked @endif>
                                                <label style="height: 38px !important;"
                                                    for="{{ $first_product->id }}-size-{{ $key }}"
                                                    class="size-label">{{ $option }}</label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif
                    <div class="row mt-4">
                        <div class="col-md-3">
                            <h5>Quantity:</h5>
                        </div>
                        <div class="col-md-9 pl-4 ps-4">

                            <div class="product-quantity">
                                <div class="input-group" style="width:160px">

                                    <button class="btn btn-danger btn-pm-qty" data-type="minus"
                                        data-id="{{ $first_product->id }}">
                                        <i class="fa fa-minus"></i>
                                    </button>

                                    <input type="text" class="form-control input-pm-number text-center"
                                        data-id="{{ $first_product->id }}" name="quantity" value="1" min="1" max="100">

                                    <button class="btn btn-success btn-pm-qty" data-type="plus"
                                        data-id="{{ $first_product->id }}">
                                        <i class="fa fa-plus"></i>
                                    </button>

                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-start">
                    <a href="{{ route('product', $first_product->slug) }}" class="btn btn-secondary btn-sm"> <i
                            class="fa fa-eye    "></i> View Details</a>
                    <button type="button" class="btn btn-danger btn-sm"
                        onclick="addToCart('form-{{ $first_product->id }}')"> <i class="fa fa-cart-plus"
                            aria-hidden="true"></i> Add
                        To Cart</button>
                </div>
            </div>
        </form>
    </div>
</div>




                </div>
            </div>
            {{-- Mobile trending  --}}
            @if ($withSlide)
                <div class="d-block d-lg-none pt-4">
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
                                            href="{{ route('product', $product->slug) }}">{{ Str::limit($product['name'], 50) }}</a>
                                    </h3>
                                    <div class="price d-flex justify-content-center align-content-center">
                                        @if ($product->discount > 0)
                                            <span
                                                class="mr-2">{{ \App\CPU\Helpers::currency_converter(
                                                    $product->unit_price - \App\CPU\Helpers::get_product_discount($product, $product->unit_price),
                                                ) }}</span>
                                            <del>{{ $product->unit_price }}</del>
                                        @else
                                            <span>{{ $product->unit_price }}</span>
                                        @endif
                                    </div>
                                    <button type="button" style="cursor: pointer;" class="btn btn-primary w-100"
                                        onclick="buy_now('form-{{ $product->id }}')">অর্ডার করুন</button>
                                </div>
                            </div>

                            {{-- <div class="card shadow-lg product-card">

                            <div class="product-img-container">
                                <a href="#">
                                    <img class="card-img-top"
                                        src="{{ asset('assets/front-end/images/product/2025-05-22-682f30619bd5a.png') }}"
                                        alt="Product Image">
                                </a>

                            </div>

                            <div class="card-body pb-0">
                                <a href="#">
                                    <h4 class="product-title">
                                        {{ Str::limit('Ready Three Piece – Luxury Cotton Collection | Shopping Zone BD | Style #G1899LF', 50) }}
                                    </h4>
                                </a>

                                <p class="product-text">
                                    2,650.00 <span class="fw-bold">৳</span>
                                </p>
                            </div>

                            <div class="sm-button text-center d-flex flex-column gap-2 mx-4 pb-3">

                                <a class="btn btn-sm btn-info text-white w-100 mr-2">
                                    <i class="fa fa-eye"></i> বিস্তারিত দেখুন
                                </a>

                                <button class="btn btn-sm btn-primary text-white w-100 mt-2 mr-3">
                                    <i class="fa fa-cart-plus"></i> অর্ডার করুন
                                </button>

                            </div>

                        </div> --}}
                        @endforeach
                    </div>
                </div>
            @else
                <div class="mt-3 withoutSlideProduct">
                    <div class="row align-items-stretch">
                        @foreach ($subProducts as $key => $product)
                            @include('web.products.product_box', ['classBox' => 'col-md-3'])
                        @endforeach
                    </div>
                </div>
            @endif
            {{-- <div class="d-none d-lg-block">
                <div class="row">
                    @foreach ($subProducts as $key => $product)
                        @include('web-views.products.product_box', ['classBox' => 'col-md-3'])
                    @endforeach
                </div>
            </div> --}}

        </div>
    </section>

@endsection
@push('scripts')
    <script>
        popUpModalQty();
    </script>
@endpush
