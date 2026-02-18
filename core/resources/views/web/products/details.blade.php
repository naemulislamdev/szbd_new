@extends('web.layouts.app')

@section('title', $product['name'])

@push('css_or_js')
    <meta name="description" content="{{ strip_tags($product->meta_description) }}">
    <meta name="keywords" content="@foreach (explode(' ', $product['name']) as $keyword) {{ $keyword . ' , ' }} @endforeach">
    @if ($product->added_by == 'seller')
        <meta name="author" content="{{ $product->seller->shop ? $product->seller->shop->name : $product->seller->f_name }}">
    @elseif($product->added_by == 'admin')
        <meta name="author" content="{{ $web_config['name']->value }}">
    @endif
    <!-- Viewport-->

    @if ($product['meta_image'] != null)
        <meta property="og:image" content="{{ asset('assets/storage/product/meta') }}/{{ $product->meta_image }}" />
        <meta property="twitter:card" content="{{ asset('assets/storage/product/meta') }}/{{ $product->meta_image }}" />
    @else
        <meta property="og:image" content="{{ asset('assets/storage/product/thumbnail') }}/{{ $product->thumbnail }}" />
        <meta property="twitter:card"
            content="{{ asset('assets/storage/product/thumbnail/') }}/{{ $product->thumbnail }}" />
    @endif

    @if ($product['meta_title'] != null)
        <meta property="og:title" content="{{ $product->meta_title }}" />
        <meta property="twitter:title" content="{{ $product->meta_title }}" />
    @else
        <meta property="og:title" content="{{ $product->name }}" />
        <meta property="twitter:title" content="{{ $product->name }}" />
    @endif
    <meta property="og:url" content="{{ route('product', [$product->slug]) }}">

    @if ($product['meta_description'] != null)
        <meta property="twitter:description" content="{{ strip_tags($product['meta_description']) }}">
        <meta property="og:description" content="{{ strip_tags($product['meta_description']) }}">
    @else
        <meta property="og:description"
            content="@foreach (explode(' ', $product['name']) as $keyword) {{ $keyword . ' , ' }} @endforeach">
        <meta property="twitter:description"
            content="@foreach (explode(' ', $product['name']) as $keyword) {{ $keyword . ' , ' }} @endforeach">
    @endif
    <meta property="twitter:url" content="{{ route('product', [$product->slug]) }}">

@endpush

@section('content')
    <style>
        .owl-carousel .owl-dots.disabled,
        .owl-carousel .owl-nav.disabled {
            display: block;
        }

        .owl-carousel .owl-nav button.owl-prev,
        .owl-carousel .owl-nav button.owl-next {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            background-color: #fff;
            /* Customize as needed */
            border: none;
            padding: 10px;
            /* Customize as needed */
            cursor: pointer;
            z-index: 1000;
            /* Ensure buttons are above other content */
        }

        .owl-carousel .owl-nav button.owl-prev {
            left: -30px;
            color: #f26d21;
            font-size: 35px;
        }

        .owl-carousel .owl-nav button.owl-next {
            right: -30px;
            color: #f26d21;
            font-size: 35px;
        }

        .owl-carousel .owl-nav button.owl-prev,
        .owl-carousel .owl-nav button.owl-next {
            outline: none;
        }

        .owl-carousel .owl-item img {
            height: 80px;
        }

        .card-header {
            padding: 6px 0px;
            margin-bottom: 0;
            background-color: rgba(0, 0, 0, .03);
            border-bottom: 0px solid rgba(0, 0, 0, .125);
        }

        .card {
            border: 0px solid rgba(0, 0, 0, .125);
        }

        .card-header {
            cursor: pointer;
        }

        .toggle-icon {
            cursor: pointer;
            background: #343a40;
            padding: 6px 24px;
            border-radius: 5px;
            color: #fff;
            font-size: 17px;
            font-weight: 800;
        }

        .btn-link {
            text-decoration: none;
            font-weight: bold;
            color: #7d7d7d;
        }

        .btn-link:hover {
            text-decoration: none;
            color: #7d7d7d;
        }

        .main-image>img {
            width: 100% !important;
        }

        .quantity-wise-price {
            color: #f26d21;
            font-size: 15px;
            font-weight: 600;
        }

        .xzoom-gallery,
        .xzoom-gallery2,
        .xzoom-gallery3,
        .xzoom-gallery4,
        .xzoom-gallery5 {
            border: 0px solid #cecece;
            margin-left: 0px;
            margin-bottom: 0px;
        }

        #main-image {
            transition: opacity 0.3s ease;
        }

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
            padding: 0 !important;
            border-radius: 5px;
            width: 100%;
            text-align: center;
            height: 100px !important;
            position: relative;
            font-size: 18px !important;
            font-weight: 600 !important;
        }

        .v-color-box>input:checked+.color-label::after {
            content: '✔';
            color: green;
            font-size: 28px !important;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }

        .v-color-box,
        .v-size-box {
            margin-right: 0.925rem !important;
        }

        .btn-number {
            width: 30px;
            height: 30px;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 0 !important;
            line-height: 30px;
            font-size: 16px !important;
            text-align: center;
        }
    </style>
    <?php
    $overallRating = \App\CPU\Helpers::get_overall_rating($product->reviews);
    $rating = \App\CPU\Helpers::get_rating($product->reviews);
    $decimal_point_settings = \App\CPU\Helpers::get_business_settings('decimal_point_settings');
    ?>

    @php
        $videoUrl = $product->video_url;
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
            } elseif (strpos($videoUrl, 'shorts') !== false) {
                // YouTube Shorts URL
                //https://www.youtube.com/shorts/zIDDpjTJRjU?feature=share
                $videoId = explode('/', $videoUrl)[4];
                $videoId = explode('?', $videoId)[0];
                $embedUrl = "https://www.youtube.com/shorts/{$videoId}";
            }
            // Adjust height for YouTube Shorts
            if (strpos($videoUrl, 'shorts') !== false) {
                $height = '700'; // YouTube Shorts height
                $col = '4';
            }
        }
    @endphp

    <section class="my-3">
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-3">
                    <div class="p-image">
                        @if ($product->video_shopping == 1)
                            <div class="row">
                                <div class="col-md-11">
                                    <div
                                        style="position: relative; width: {{ $width }}; height: {{ $height }}px;">
                                        <iframe style="width: 100%; height: 100%;" src="{{ $embedUrl }}"
                                            title="Video Player" frameborder="0"
                                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                                            referrerpolicy="strict-origin-when-cross-origin" allowfullscreen>
                                        </iframe>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="row mb-2">
                                <div class="col-md-11 mx-auto">
                                    <div class="main-image mb-3 float-right" id="img-zoom">
                                        <img id="main-image"
                                            src="{{ asset('assets/storage/product/thumbnail') }}/{{ $product['thumbnail'] }}"
                                            xoriginal="{{ asset('assets/storage/product/thumbnail') }}/{{ $product['thumbnail'] }}"
                                            class="img-fluid xzoom" alt="{{ $product->name }}">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-10 mx-auto">
                                    <div class="p-details-sub-img owl-carousel owl-theme">
                                        @if ($product->images != null)
                                            @foreach (json_decode($product->images) as $key => $photo)
                                                <div class="item">
                                                    <a href="{{ asset("assets/storage/product/$photo") }}"
                                                        title="{{ $product->name }}">
                                                        <img src="{{ asset("assets/storage/product/$photo") }}"
                                                            data-image="{{ asset("assets/storage/product/$photo") }}"
                                                            class="xzoom-gallery" alt="{{ $product->name }}">
                                                    </a>
                                                </div>
                                            @endforeach
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="col-md-8 mb-3">
                    <div class="row">
                        <div class="col-md-7 mb-3">
                            <div class="p-details">
                                <h1 class="product-name mb-2">{{ $product->name }}</h1>

                                <div>
                                    <span class="product-price">
                                        ৳ {{ $product->unit_price }}
                                    </span>
                                </div>

                                @if ($product->discount > 0)
                                    <span class="discount-price">
                                        <del>৳ {{ $product->unit_price }} </del> -
                                        @if ($product->discount_type == 'percent')
                                            {{ $product->discount }}%
                                        @elseif($product->discount_type == 'flat')
                                            {{ $product->discount }}৳
                                        @endif
                                    </span>
                                @endif
                                <div class="my-2">
                                    <span class="product-code"><strong>Code:</strong> {{ $product->code }}</span>
                                </div>
                                <p class="product-description">{!! $product['short_description'] !!}</p>
                                @php
                                    $colors = is_array($product->colors)
                                        ? $product->colors
                                        : json_decode($product->colors ?? '[]', true);

                                    $colorVariants = is_array($product->color_variant)
                                        ? $product->color_variant
                                        : json_decode($product->color_variant ?? '[]', true);
                                    $choiceOptions = is_array($product->choice_options)
                                        ? $product->choice_options
                                        : json_decode($product->choice_options ?? '[]', true);
                                @endphp

                                <form id="form-{{ $product->id }}" class="mb-2">
                                    @csrf
                                    <input type="hidden" name="id" value="{{ $product->id }}">

                                    @if (!empty($colors) && count($colors) > 0)
                                        <div class="row">
                                            <div class="col-12 mb-3">
                                                <h4 style="font-size: 18px; font-weight: bold;">Color</h4>
                                            </div>
                                            @if (!empty($colorVariants))
                                                <div class="col-12 mb-3 mt-3">
                                                    <div class="d-flex " style="gap: 20px">
                                                        @foreach ($colorVariants as $key => $color)
                                                            @php
                                                                $code = $color['code'] ?? $color->code;
                                                                $cImage = $color['image'] ?? $color->image;
                                                                $cName = $color['color'] ?? $color->color;
                                                            @endphp

                                                            <div>
                                                                <div class="v-color-box position-relative mr-0"
                                                                    style="margin: 0 !important;"
                                                                    title="{{ $color['color'] }}">
                                                                    <input type="radio"
                                                                        id="{{ $product->id }}-color-{{ $key }}"
                                                                        name="color" value="{{ $code }}"
                                                                        @if ($key == 0) checked @endif>
                                                                    <label
                                                                        for="{{ $product->id }}-color-{{ $key }}"
                                                                        class="color-label"
                                                                        style="background-color: {{ $code }}; overflow: hidden;">
                                                                        <img src="{{ asset($cImage) }}"
                                                                            data-image="{{ asset($cImage) }}"
                                                                            alt="{{ $cName }}"
                                                                            style="max-width:100%; height:auto;">
                                                                    </label>

                                                                    <span class="d-inline-block"
                                                                        style="height: 20px; width: 20px; border-radius: 50%; position: absolute;
                                                                                            right: -14px;
                                                                                            top: -48px;

                                                                                            background: {{ $code }}; box-shadow: rgba(0, 0, 0, 0.16) 0px 3px 6px, rgba(0, 0, 0, 0.23) 0px 3px 6px;"></span>

                                                                </div>
                                                                <div class="mt-4 pt-2">
                                                                    <small
                                                                        style="background: {{ $code }}; padding: 3px;display: block;text-align: center;"
                                                                        class="text-small rounded">{{ $color['color'] }}</small>
                                                                </div>
                                                            </div>
                                                        @endforeach

                                                    </div>
                                                </div>

                                            @endif
                                        </div>
                                    @endif
                                    @php
                                        $qty = 0;
                                        if (!empty($product)) {
                                            foreach (json_decode($product->variation) as $key => $variation) {
                                                $qty += $variation->qty;
                                            }
                                        }
                                    @endphp
                                    @foreach ($choiceOptions as $key => $choice)
                                        <div class="row">
                                            <div class="col-12 mb-3 mt-3">
                                                <h4 style="font-size: 18px; margin:0;">{{ $choice['title'] }}</h4>
                                            </div>
                                            <div class="col-12 ">
                                                <div class="d-flex">
                                                    @foreach ($choice['options'] as $key => $option)
                                                        <div class="v-size-box">
                                                            <input type="radio"
                                                                id="{{ $choice['name'] }}-{{ $option }}"
                                                                name="{{ $choice['name'] }}" value="{{ $option }}"
                                                                @if ($key == 0) checked @endif>
                                                            <label style="height: 35px !important; "
                                                                for="{{ $choice['name'] }}-{{ $option }}"
                                                                class="size-label">{{ $option }}</label>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach

                                    <div class="row mb-3 mt-3 align-items-center">
                                        <div class="col-6 mb-2">
                                            <h4 style="font-size: 18px; margin:0;" class="mb-2">Quantity:</h4>

                                            <div class="product-quantity d-flex align-items-center">
                                                <div
                                                    class="input-group--style-2 pr-3 d-flex align-items-center justify-content-between">

                                                    <button class="btn btn-danger btn-number" type="button"
                                                        data-type="minus" data-field="quantity" style="padding: 10px">
                                                        <i class="fa fa-minus "></i>
                                                    </button>

                                                    <input style="font-size: 20px; font-weight: 600" type="hidden"
                                                        name="quantity"
                                                        class="form-control bg-transparent input-number text-center cart-qty-field border-0 p-0"
                                                        placeholder="1" value="1" min="1" max="100">
                                                    <p class="cart-qty-field input-number">1</p>

                                                    <button class="btn btn-success btn-number" type="button"
                                                        data-type="plus" data-field="quantity" style="padding: 10px">
                                                        <i class="fa fa-plus "></i>
                                                    </button>

                                                </div>

                                            </div>

                                        </div>
                                        <div class="col-6 ">
                                            <div class="d-flex justify-content-between mt-2" id="chosen_price_div">

                                                <span class="instock">Instock: {{ $product->current_stock }}</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <div class="col-6 ">
                                            <button type="button" onclick="buy_now('form-{{ $product->id }}')"
                                                href="javascript:void(0);" class="w-100 common-btn border-0">অর্ডার
                                                করুন</button>
                                        </div>
                                        <div class="col-6">
                                            <button type="button" class="btn btn-dark d-block w-100 font-weight-bold"
                                                onclick="addToCart('form-{{ $product->id }}')">কার্টে যোগ করুন</button>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-12">
                                            <div>
                                                <a target="_blank" title="Go Whatsapp"
                                                    style="  font-size: 18px; font-weight: 600; box-shadow: rgba(0, 0, 0, 0.24) 0px 3px 8px; background: #f26d21;"
                                                    class="text-white d-flex align-items-center border p-2 px-3 rounded"
                                                    href="https://wa.me/8801406667669?text=Is%20anyone%20available%20to%20chat%3F">
                                                    <i style="font-size: 30px" class="fa fa-whatsapp me-2 mr-2"></i>
                                                    <span class="ml-1">01406667669</span>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                                <div class="row">
                                    <div class="col-md-3 mx-auto mb-2">
                                        <button type="button" onclick="addWishlist('{{ $product['id'] }}')"
                                            class="btn for-hover-bg p-wishlist" style="">
                                            <i class="fa fa-heart-o mr-2" aria-hidden="true"></i>
                                            <span class="countWishlist-{{ $product['id'] }}">{{ $countWishlist }}</span>
                                        </button>
                                    </div>
                                    <div class="col-md-12 mb-3">
                                        @if ($product['size_chart'] != null && $product['size_chart'] !== 'def.png')
                                            <div class="accordion mb-3" id="accordionExample">
                                                <div class="card">
                                                    <div class="card-header d-flex justify-content-between align-items-center"
                                                        id="productSizeChart">
                                                        <h5 class="mb-0">
                                                            <button class="btn btn-link" type="button"
                                                                data-toggle="collapse" data-target="#sizeChart"
                                                                aria-expanded="true" aria-controls="sizeChart">
                                                                Size Chart
                                                            </button>
                                                        </h5>
                                                        <span class="toggle-icon" data-toggle="collapse"
                                                            data-target="#sizeChart" aria-expanded="true"
                                                            aria-controls="sizeChart"><i class="fa fa-plus"></i></span>
                                                    </div>

                                                    <div id="sizeChart" class="collapse show"
                                                        aria-labelledby="productSizeChart"
                                                        data-parent="#accordionExample">
                                                        <div class="card-body">
                                                            <div class="size-img-box">

                                                                <img src="{{ asset('assets/storage/product/thumbnail') }}/{{ $product['size_chart'] }}"
                                                                    class="img-fluid" alt="Product size chart image">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                        <div class="accordion mb-3" id="accordionExample">
                                            <div class="card">
                                                <div class="card-header d-flex justify-content-between align-items-center"
                                                    id="productDescription">
                                                    <h5 class="mb-0">
                                                        <button class="btn btn-link" type="button"
                                                            data-toggle="collapse" data-target="#description"
                                                            aria-expanded="true" aria-controls="description">
                                                            Description
                                                        </button>
                                                    </h5>
                                                    <span class="toggle-icon" data-toggle="collapse"
                                                        data-target="#description" aria-expanded="true"
                                                        aria-controls="description"><i class="fa fa-plus"></i></span>
                                                </div>

                                                <div id="description" class="collapse show"
                                                    aria-labelledby="productDescription" data-parent="#accordionExample">
                                                    <div class="card-body">
                                                        <p>
                                                            {!! $product['details'] !!}
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="accordion" id="accordionExample">
                                            <div class="card">
                                                <div class="card-header d-flex justify-content-between align-items-center"
                                                    id="review">
                                                    <h5 class="mb-0">
                                                        <button class="btn btn-link" type="button"
                                                            data-toggle="collapse" data-target="#reviewCollapse"
                                                            aria-expanded="true" aria-controls="reviewCollapse">
                                                            Review
                                                        </button>
                                                    </h5>
                                                    <span class="toggle-icon" data-toggle="collapse"
                                                        data-target="#reviewCollapse" aria-expanded="true"
                                                        aria-controls="reviewCollapse"><i class="fa fa-plus"></i></span>
                                                </div>

                                                @php
                                                    $reviewProductId = $product->id;
                                                @endphp
                                                <div id="reviewCollapse" class="collapse" aria-labelledby="review"
                                                    data-parent="#accordionExample">
                                                    <div class="card-body">
                                                        @php($reviews_of_product = App\Models\Review::where('product_id', $product->id)->paginate(2))

                                                        <div class="row pt-2 pb-3">
                                                            <div class="col-lg-3 col-md-4 ">
                                                                <div
                                                                    class=" row d-flex justify-content-center align-items-center">
                                                                    <div
                                                                        class="col-12 d-flex justify-content-center align-items-center">
                                                                        <h2 class="overall_review mb-2"
                                                                            style="font-weight: 500;font-size: 50px;">
                                                                            {{ $overallRating[1] }}
                                                                        </h2>
                                                                    </div>
                                                                    <div
                                                                        class="d-flex justify-content-center align-items-center star-rating ">
                                                                        @if (round($overallRating[0]) == 5)
                                                                            @for ($i = 0; $i < 5; $i++)
                                                                                <i
                                                                                    class="fa fa-star-o font-size-sm text-accent {{ Session::get('direction') === 'rtl' ? 'ml-1' : 'mr-1' }}"></i>
                                                                            @endfor
                                                                        @endif
                                                                        @if (round($overallRating[0]) == 4)
                                                                            @for ($i = 0; $i < 4; $i++)
                                                                                <i
                                                                                    class="fa fa-star-o font-size-sm text-accent {{ Session::get('direction') === 'rtl' ? 'ml-1' : 'mr-1' }}"></i>
                                                                            @endfor
                                                                            <i
                                                                                class="fa fa-star font-size-sm text-muted {{ Session::get('direction') === 'rtl' ? 'ml-1' : 'mr-1' }}"></i>
                                                                        @endif
                                                                        @if (round($overallRating[0]) == 3)
                                                                            @for ($i = 0; $i < 3; $i++)
                                                                                <i
                                                                                    class="fa fa-star-o font-size-sm text-accent {{ Session::get('direction') === 'rtl' ? 'ml-1' : 'mr-1' }}"></i>
                                                                            @endfor
                                                                            @for ($j = 0; $j < 2; $j++)
                                                                                <i
                                                                                    class="fa fa-star font-size-sm text-accent {{ Session::get('direction') === 'rtl' ? 'ml-1' : 'mr-1' }}"></i>
                                                                            @endfor
                                                                        @endif
                                                                        @if (round($overallRating[0]) == 2)
                                                                            @for ($i = 0; $i < 2; $i++)
                                                                                <i
                                                                                    class="fa fa-star-o font-size-sm text-accent {{ Session::get('direction') === 'rtl' ? 'ml-1' : 'mr-1' }}"></i>
                                                                            @endfor
                                                                            @for ($j = 0; $j < 3; $j++)
                                                                                <i
                                                                                    class="fa fa-star font-size-sm text-accent {{ Session::get('direction') === 'rtl' ? 'ml-1' : 'mr-1' }}"></i>
                                                                            @endfor
                                                                        @endif
                                                                        @if (round($overallRating[0]) == 1)
                                                                            @for ($i = 0; $i < 4; $i++)
                                                                                <i
                                                                                    class="fa fa-star font-size-sm text-accent {{ Session::get('direction') === 'rtl' ? 'ml-1' : 'mr-1' }}"></i>
                                                                            @endfor
                                                                            <i
                                                                                class="fa fa-star-o font-size-sm text-accent {{ Session::get('direction') === 'rtl' ? 'ml-1' : 'mr-1' }}"></i>
                                                                        @endif
                                                                        @if (round($overallRating[0]) == 0)
                                                                            @for ($i = 0; $i < 5; $i++)
                                                                                <i
                                                                                    class="fa fa-star font-size-sm text-muted {{ Session::get('direction') === 'rtl' ? 'ml-1' : 'mr-1' }}"></i>
                                                                            @endfor
                                                                        @endif
                                                                    </div>
                                                                    <div
                                                                        class="col-12 d-flex justify-content-center align-items-center mt-2">
                                                                        <span class="text-center">
                                                                            {{ $reviews_of_product->total() }}
                                                                            ratings
                                                                        </span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-9 col-md-8 pt-sm-3 pt-md-0">
                                                                <div
                                                                    class="row d-flex align-items-center mb-2 font-size-sm">
                                                                    <div class="col-3 text-nowrap "><span
                                                                            class="d-inline-block align-middle text-body">Excellent</span>
                                                                    </div>
                                                                    <div class="col-8">
                                                                        <div class="progress text-body"
                                                                            style="height: 5px;">
                                                                            <div class="progress-bar " role="progressbar"
                                                                                style="background-color: {{ $web_config['primary_color'] }} !important;width: <?php echo $widthRating = $rating[0] != 0 ? ($rating[0] / $overallRating[1]) * 100 : 0; ?>%;"
                                                                                aria-valuenow="60" aria-valuemin="0"
                                                                                aria-valuemax="100"></div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-1 text-body">
                                                                        <span
                                                                            class=" {{ Session::get('direction') === 'rtl' ? 'mr-3 float-left' : 'ml-3 float-right' }} ">
                                                                            {{ $rating[0] }}
                                                                        </span>
                                                                    </div>
                                                                </div>

                                                                <div
                                                                    class="row d-flex align-items-center mb-2 text-body font-size-sm">
                                                                    <div class="col-3 text-nowrap "><span
                                                                            class="d-inline-block align-middle ">Good</span>
                                                                    </div>
                                                                    <div class="col-8">
                                                                        <div class="progress" style="height: 5px;">
                                                                            <div class="progress-bar" role="progressbar"
                                                                                style="background-color: {{ $web_config['primary_color'] }} !important;width: <?php echo $widthRating = $rating[1] != 0 ? ($rating[1] / $overallRating[1]) * 100 : 0; ?>%; background-color: #a7e453;"
                                                                                aria-valuenow="27" aria-valuemin="0"
                                                                                aria-valuemax="100"></div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-1">
                                                                        <span
                                                                            class="{{ Session::get('direction') === 'rtl' ? 'mr-3 float-left' : 'ml-3 float-right' }}">
                                                                            {{ $rating[1] }}
                                                                        </span>
                                                                    </div>
                                                                </div>

                                                                <div
                                                                    class="row d-flex align-items-center mb-2 text-body font-size-sm">
                                                                    <div class="col-3 text-nowrap"><span
                                                                            class="d-inline-block align-middle ">Average</span>
                                                                    </div>
                                                                    <div class="col-8">
                                                                        <div class="progress" style="height: 5px;">
                                                                            <div class="progress-bar" role="progressbar"
                                                                                style="background-color: {{ $web_config['primary_color'] }} !important;width: <?php echo $widthRating = $rating[2] != 0 ? ($rating[2] / $overallRating[1]) * 100 : 0; ?>%; background-color: #ffda75;"
                                                                                aria-valuenow="17" aria-valuemin="0"
                                                                                aria-valuemax="100"></div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-1">
                                                                        <span
                                                                            class="{{ Session::get('direction') === 'rtl' ? 'mr-3 float-left' : 'ml-3 float-right' }}">
                                                                            {{ $rating[2] }}
                                                                        </span>
                                                                    </div>
                                                                </div>

                                                                <div
                                                                    class="row d-flex align-items-center mb-2 text-body font-size-sm">
                                                                    <div class="col-3 text-nowrap "><span
                                                                            class="d-inline-block align-middle">Below
                                                                            Average</span>
                                                                    </div>
                                                                    <div class="col-8">
                                                                        <div class="progress" style="height: 5px;">
                                                                            <div class="progress-bar" role="progressbar"
                                                                                style="background-color: {{ $web_config['primary_color'] }} !important;width: <?php echo $widthRating = $rating[3] != 0 ? ($rating[3] / $overallRating[1]) * 100 : 0; ?>%; background-color: #fea569;"
                                                                                aria-valuenow="9" aria-valuemin="0"
                                                                                aria-valuemax="100"></div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-1">
                                                                        <span
                                                                            class="{{ Session::get('direction') === 'rtl' ? 'mr-3 float-left' : 'ml-3 float-right' }}">
                                                                            {{ $rating[3] }}
                                                                        </span>
                                                                    </div>
                                                                </div>

                                                                <div
                                                                    class="row d-flex align-items-center text-body font-size-sm">
                                                                    <div class="col-3 text-nowrap"><span
                                                                            class="d-inline-block align-middle ">Poor</span>
                                                                    </div>
                                                                    <div class="col-8">
                                                                        <div class="progress" style="height: 5px;">
                                                                            <div class="progress-bar" role="progressbar"
                                                                                style="background-color: {{ $web_config['primary_color'] }} !important;backbround-color:{{ $web_config['primary_color'] }};width: <?php echo $widthRating = $rating[4] != 0 ? ($rating[4] / $overallRating[1]) * 100 : 0; ?>%;"
                                                                                aria-valuenow="4" aria-valuemin="0"
                                                                                aria-valuemax="100"></div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-1">
                                                                        <span
                                                                            class="{{ Session::get('direction') === 'rtl' ? 'mr-3 float-left' : 'ml-3 float-right' }}">
                                                                            {{ $rating[4] }}
                                                                        </span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row pb-4 mb-3">
                                                            <div
                                                                style="display: block;width:100%;text-align: center;background: #F3F4F5;border-radius: 5px;padding:5px;">
                                                                <span class="text-capitalize">Product Review</span>
                                                            </div>
                                                        </div>
                                                        <div class="row pb-4">
                                                            <div class="col-12" id="product-review-list">
                                                                @if (count($product->reviews) == 0)
                                                                    <div class="card">
                                                                        <div class="card-body">
                                                                            <h6 class="text-danger text-center">
                                                                                product review not available'
                                                                            </h6>
                                                                        </div>
                                                                    </div>
                                                                @endif
                                                            </div>
                                                            @if (count($product->reviews) > 0)
                                                                <div class="col-12">
                                                                    <div
                                                                        class="card-footer d-flex justify-content-center align-items-center">
                                                                        <button class="btn"
                                                                            style="background: {{ $web_config['primary_color'] }}; color: #ffffff"
                                                                            onclick="load_review()">view more</button>
                                                                    </div>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="p-details-contact-section">
                                <div class="contact-box d-flex justify-content-between">
                                    <div class="box1">
                                        <p><i class="fa fa-phone"></i> contact:</p>
                                        <p><i class="fa fa-motorcycle"></i> Inside Dhaka:</p>
                                        <p><i class="fa fa-truck"></i> Outside Dhaka:</p>
                                        <p><i class="fa fa-money"></i> Cash on Delivery:</p>
                                        <p><i class="fa fa-refresh"></i> Refund Rules:</p>
                                    </div>
                                    <div class="box1">
                                        <a href="tel:{{ $web_config['phone']->value }}">
                                            <p>{{ $web_config['phone']->value }}</p>
                                        </a>
                                        <p>2-3 working days</p>
                                        <p>3-4 working days</p>
                                        <p>Available</p>
                                        <p>Within 7 Days<a href="{{ route('privacy-policy') }}"> View Policy</a></p>
                                    </div>
                                </div>
                                <div class="pay-method mb-3">
                                    <span>Payment</span>
                                    <img src="{{ asset('assets/frontend/images/payment/payment.png') }}" alt="">
                                </div>
                            </div>
                            @if ($product->video_shopping == 0)
                                @if ($product['video_url'])
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="video-product">
                                                <div
                                                    style="position: relative; width: {{ $width }}; height: {{ $height }}px;">
                                                    <iframe style="width: 100%; height: 100%;" src="{{ $embedUrl }}"
                                                        title="Video Player" frameborder="0"
                                                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                                                        referrerpolicy="strict-origin-when-cross-origin" allowfullscreen>
                                                    </iframe>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </section>
    <section>
        <div class="container">
            <div class="row mb-3">
                <div class="col text-center">
                    <div class="section-heading-title">
                        <h3>Related products</h3>
                        <div class="heading-border"></div>
                    </div>
                    <div class="grid-controls">
                        <button class="grid-btn" data-columns="6" data-category="category3">
                            <div class="grid-icon"></div>
                            <div class="grid-icon"></div>
                        </button>
                        <button class="grid-btn" data-columns="4" data-category="category3">
                            <div class="grid-icon"></div>
                            <div class="grid-icon"></div>
                            <div class="grid-icon"></div>
                        </button>
                        <button class="grid-btn" data-columns="3" data-category="category3">
                            <div class="grid-icon"></div>
                            <div class="grid-icon"></div>
                            <div class="grid-icon"></div>
                            <div class="grid-icon"></div>
                        </button>
                        <button class="grid-btn" data-columns="2" data-category="category3">
                            <div class="grid-icon"></div>
                            <div class="grid-icon"></div>
                            <div class="grid-icon"></div>
                            <div class="grid-icon"></div>
                            <div class="grid-icon"></div>
                            <div class="grid-icon"></div>
                        </button>
                    </div>
                    <div class="grid-controls mobile-grid-controls">
                        <button class="grid-btn grid-btn-mobile" data-columns="12" data-category="category3">
                            <div class="grid-icon"></div>
                        </button>
                        <button class="grid-btn grid-btn-mobile" data-columns="6" data-category="category3">
                            <div class="grid-icon"></div>
                            <div class="grid-icon"></div>
                        </button>
                    </div>
                </div>
            </div>
            <div class="row product-grid">
                <!-- Your product columns go here -->
                @if ($relatedProducts->isNotEmpty())
                    @foreach ($relatedProducts as $product)
                        @include('web.products.product_box', ['dataCategory' => 'category3'])
                    @endforeach
                @else
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <h6 class="text-danger text-center">{{ trans('messages.similar') }}
                                    {{ trans('messages.product_not_available') }}</h6>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </section>
@endsection
@push('scripts')
    <script>
        function cartQuantityInitialize() {

            // PLUS & MINUS BUTTON CLICK (delegated)
            $(document).on('click', '.btn-number', function(e) {
                e.preventDefault();

                let fieldName = $(this).data('field');
                let type = $(this).data('type');
                let input = $("input[name='" + fieldName + "']");

                let currentVal = parseInt(input.val());
                let min = parseInt(input.attr('min'));
                let max = parseInt(input.attr('max'));

                if (isNaN(currentVal)) {
                    input.val(min);
                    return;
                }

                if (type === 'minus') {
                    if (currentVal > min) {
                        input.val(currentVal - 1).trigger('change');
                    }
                }

                if (type === 'plus') {
                    if (currentVal < max) {
                        input.val(currentVal + 1).trigger('change');
                        console.log(currentVal + 1);
                    }
                }
            });

            // STORE OLD VALUE
            $(document).on('focusin', '.input-number', function() {
                $(this).data('oldValue', $(this).val());
            });

            // INPUT CHANGE VALIDATION
            $(document).on('change', '.input-number', function() {

                let minValue = parseInt($(this).attr('min'));
                let maxValue = parseInt($(this).attr('max'));
                let valueCurrent = parseInt($(this).val());
                let name = $(this).attr('name');

                if (valueCurrent < minValue) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Cart',
                        text: 'Minimum quantity reached'
                    });
                    $(this).val($(this).data('oldValue'));
                    return;
                }

                if (valueCurrent > maxValue) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Cart',
                        text: 'Stock limit exceeded'
                    });
                    $(this).val($(this).data('oldValue'));
                    return;
                }

                $(".btn-number[data-type='minus'][data-field='" + name + "']").prop('disabled', valueCurrent <=
                    minValue);
                $(".btn-number[data-type='plus'][data-field='" + name + "']").prop('disabled', valueCurrent >=
                    maxValue);
            });

            // BLOCK NON-NUMERIC INPUT
            $(document).on('keydown', '.input-number', function(e) {
                if (
                    $.inArray(e.keyCode, [46, 8, 9, 27, 13]) !== -1 ||
                    (e.keyCode === 65 && e.ctrlKey === true) ||
                    (e.keyCode >= 35 && e.keyCode <= 39)
                ) {
                    return;
                }
                if ((e.shiftKey || e.keyCode < 48 || e.keyCode > 57) &&
                    (e.keyCode < 96 || e.keyCode > 105)) {
                    e.preventDefault();
                }
            });
        }
    </script>
    <script>
        /* 2️⃣ Product Detail View (Single Product Page) */
        dataLayer.push({
            event: "view_item",
            ecommerce: {
                items: [{
                    item_id: {{ $product->id }},
                    item_name: {{ $product->name }},
                    item_brand: {{ $product->brand->name ?? 'No Brand' }},
                    item_category: "{{ $brand_name ?? 'General' }}", // incomplte
                    item_variant: {{ $product->variation }}, // incomplte
                    price: {{ $product->unit_price }},
                    currency: "BDT"
                }]
            }
        });
    </script>
    <script>
        fbq('track', 'ViewContent', {
            content_ids: ['{{ $product->id }}'],
            content_type: 'product',
            value: {{ $product->unit_price ?? 0 }},
            currency: 'BDT'
        });
    </script>
    <script>
        ttq.track('ViewContent', {
            content_id: '{{ $product->id }}',
            content_type: 'product',
            value: {{ $product->unit_price ?? 0 }},
            currency: 'BDT'
        });
    </script>
    <script>
        //Product description collapse
        $(document).ready(function() {
            $('.collapse').on('show.bs.collapse', function() {
                $(this).prev('.card-header').find('.toggle-icon').html('<i class="fa fa-minus"></i>');
            });

            $('.collapse').on('hide.bs.collapse', function() {
                $(this).prev('.card-header').find('.toggle-icon').html('<i class="fa fa-plus"></i>');
            });
        });
    </script>
    <script type="text/javascript">
        cartQuantityInitialize();
        popUpModalQty();
        getVariantPrice();
        $('#add-to-cart-form input').on('change', function() {
            getVariantPrice();
        });

        function showInstaImage(link) {
            $("#attachment-view").attr("src", link);
            $('#show-modal-view').modal('toggle')
        }
    </script>
    <script>
        $(document).ready(function() {
            load_review();
        });
        let load_review_count = 1;

        function load_review() {

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                }
            });
            $.ajax({
                type: "post",
                url: '{{ route('review-list-product') }}',
                data: {
                    product_id: {{ $reviewProductId }},
                    offset: load_review_count
                },
                success: function(data) {
                    // console.log(data);

                    $('#product-review-list').append(data.productReview)
                    if (data.not_empty == 0 && load_review_count > 2) {
                        toastr.info('no more review remain to load', {
                            CloseButton: true,
                            ProgressBar: true
                        });
                        // console.log('iff');
                    }
                }
            });
            load_review_count++
        }
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
                            mainImage.setAttribute('xoriginal', newSrc);
                            mainImage.style.opacity = '1';
                        }, 300);
                    });
                }
            });
        });
    </script>
@endpush
