@php
    $promoOffers = \App\Models\Banner::where('banner_type', 'promo_offer')
        ->where('published', 1)
        ->orderBy('id', 'desc')
        ->get();

    $total = $promoOffers->count();

    $half = ceil($total / 2);

    $left_promo_offers = $promoOffers->take($half);
    $right_promo_offers = $promoOffers->slice($half);
    $main_banner = \App\Models\Banner::where('banner_type', 'Main Banner')
        ->where('published', 1)
        ->orderBy('id', 'desc')
        ->get();

@endphp

<section class="header-slider-section ">
    <div class="container">
        <div class="row align-items-center">
            <div class="@if ($left_promo_offers->count() > 0) col-2 pr-0 @endif ">
                @if ($left_promo_offers->count() > 0)
                    <div class="swiper leftPromo h-100">
                        <div class="swiper-wrapper">
                            @foreach ($left_promo_offers as $promo)
                                <div class="swiper-slide">
                                    <a class="text-center d-block" href="{{ $promo['url'] }}">
                                        <img style="max-width:100%; max-height:350px"
                                            src="{{ asset('assets/storage/banner') }}/{{ $promo['photo'] }}"
                                            class="img-fluid" alt="promo image">
                                    </a>
                                </div>
                            @endforeach
                        </div>
                        <div class="swiper-pagination "></div>
                    </div>
                @endif
            </div>
            <div class=" px-0 @if ($total > 0) col-8 @else col-12 @endif">
                <div id="carouselExampleIndicators" class="carousel slide position-relative container "
                    data-ride="carousel" data-interval="3000">
                    <ol class="carousel-indicators">
                        @foreach ($main_banner as $key => $banner)
                            <li data-target="#carouselExampleIndicators" data-slide-to="{{ $key }}"
                                class="{{ $key == 0 ? 'active' : '' }}"></li>
                        @endforeach
                    </ol>
                    <div class="carousel-inner">
                        @foreach ($main_banner as $key => $banner)
                            <div class="carousel-item {{ $key == 0 ? 'active' : '' }}">
                                <div class="main-slider">
                                    <a href="{{ $banner['url'] }}">
                                        <img class="d-block w-100 rounded-0 rounded-lg"
                                            onerror="this.src='{{ asset('assets/frontend/img/placeholder.jpg') }}'"
                                            src="{{ asset('assets/storage/banner') }}/{{ $banner['photo'] }}"
                                            alt="Main Banner Image">
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
            </div>
            <div class="@if ($right_promo_offers->count() > 0) col-2 pl-0 @endif ">
                @if ($right_promo_offers->count() > 0)
                    <div class="swiper rightPromo h-100">
                        <div class="swiper-wrapper">

                            @foreach ($right_promo_offers as $promo)
                                <div class="swiper-slide">
                                    <a class="text-center d-block" href="{{ $promo['url'] }}">
                                        <img style="max-width:100%; max-height:350px"
                                            src="{{ asset('assets/storage/banner') }}/{{ $promo['photo'] }}"
                                            class="img-fluid" alt="promo image">
                                    </a>
                                </div>
                            @endforeach

                        </div>

                        <div class="swiper-pagination"></div>
                    </div>
                @endif
            </div>
        </div>
    </div>

</section>
