<header id="header" class="py-2"
    style="box-shadow: rgba(60, 64, 67, 0.3) 0px 1px 2px 0px, rgba(60, 64, 67, 0.15) 0px 2px 6px 2px;">
    <div class="container">
        <div class="row main_row align-items-lg-center">
            <div class="col-md-2 d-none d-lg-flex align-items-center flex-row gap-5">
                <!-- <a class="navbar-brand" href="index.html">Shopping Zone BD</a> -->

                <a href="{{ route('home') }}">
                    <img class="header-logo"
                        src="{{ asset('assets/storage/logo') . '/' . $web_config['web_logo']->value }}"
                        onerror="this.src='{{ asset('assets/frontend/img/image-place-holder.png') }}'"
                        alt="{{ $web_config['name']->value }}">
                </a>

            </div>
            <div class="col-md-8 pr-0">
                @php $categories = \App\Models\Category::where('home_status', 1)->orderBy('order_number')->get(); @endphp
                @php
                    $discountOffer = \App\Models\DiscountOffer::where('status', 1)->first();
                @endphp
                <nav class="navbar">
                    <div class="menu-area">
                        <ul>
                            @if ($discountOffer != null)
                                <li><a href="{{ route('discount.offers', ['slug' => $discountOffer->slug ?? '']) }}"><img
                                            style="height: 60px; width: auto;"
                                            src="{{ asset('storage/offer') }}/{{ $discountOffer['image'] }}"
                                            alt="offer image"></a>
                                </li>
                            @endif
                            <li><a href="{{ route('home') }}">Home</a></li>

                            <li class="dd-btn1"><a href="#">Categories <i class="fa fa-angle-down"></i></a>
                                <div class="dropdown-menu1">
                                    <div class="row">
                                        @foreach ($categories as $category)
                                            <div class="col-md-4 mb-2">
                                                <div class="m-category-box">
                                                    <a href="{{ route('category.products', $category->slug) }}">
                                                        <img src="{{ asset("assets/>storage/category/$category->icon") }}"
                                                            onerror="this.src='{{ asset('assets/frontend/img/image-place-holder.png') }}'">
                                                        {{ $category['name'] }} <i
                                                            class="fa fa-angle-right float-right mt-1"></i>
                                                    </a>
                                                </div>

                                                @if ($category->subCategory->count() > 0)
                                                    <div class="s-category-box">
                                                        <ul class="w-nav-list level_3 ml-4">
                                                            @foreach ($category->subCategory as $subCat)
                                                                <li class="s-category"><a
                                                                        href="{{ route('category.products', [$category->slug, $subCat->slug]) }}">{{ $subCat['name'] }}
                                                                    </a>

                                                                    @if ($subCat->childes->count() > 0)
                                                                        <div class="dropdown-menuc">
                                                                            <ul class="w-nav-list level_3 ml-3">
                                                                                @foreach ($subCat->childes as $childCat)
                                                                                    <li><a
                                                                                            href="{{ route('category.products', [$category->slug, $subCat->slug, $childCat->slug]) }}">{{ $childCat['name'] }}</a>
                                                                                    </li>
                                                                                @endforeach
                                                                            </ul>
                                                                        </div>
                                                                    @endif
                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                    </div>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </li>
                            <li><a href="{{ route('shop') }}">Shop</a>
                            </li>
                            <li><a href="{{ route('video_shopping') }}">video shopping</a>
                            </li>


                            <li><a href="{{ route('offers.product') }}"><img style="height: 50px; width: auto;"
                                        src="{{ asset('assets/frontend/img/sp_offer.png') }}" alt="special image"></a>
                            </li>
                            <li><a href="{{ route('outlets') }}">Our outlets</a></li>
                            <li><a href="{{ route('careers') }}">Careers</a></li>
                        </ul>
                    </div>

                    <div class="d-flex d-lg-none">
                        <i class="fa fa-bars menu-icon"></i>

                        <div class="ml-4 d-flex align-items-center flex-row">
                            <!-- <a class="navbar-brand" href="index.html">Shopping Zone BD</a> -->
                            <a href="{{ route('home') }}">
                                <img style="max-width: 100%;"
                                    src="{{ asset('assets/storage/logo') . '/' . $web_config['web_logo']->value }}"
                                    onerror="this.src='{{ asset('assets/frontend/img/image-place-holder.png') }}'"
                                    alt="{{ $web_config['name']->value }}">
                            </a>

                        </div>
                    </div>
                </nav>
            </div>
            <div class="col-md-2 ms-auto">
                <div class="header-icon ms-5 align-items-center">
                    @if ($discountOffer != null)
                        <a class="d-block d-lg-none"
                            href="{{ route('discount.offers', ['slug' => $discountOffer->slug ?? '']) }}"><img
                                style="height: 60px; width: auto;"
                                src="{{ asset('storage/offer') }}/{{ $discountOffer['image'] }}"
                                alt="offer image"></a>
                    @endif

                    <a data-bs-toggle="offcanvas" href="#searchOffcanvas" role="button"
                        aria-controls="searchOffcanvas"><i class="fa fa-search" aria-hidden="true"></i></a>


                    <a class="d-none d-lg-inline-block" href="{{ route('wishlists') }}"><i class="fa fa-heart-o"
                            aria-hidden="true"></i>
                        <span
                            class="badge badge-danger countWishlist">{{ session()->has('wish_list') ? count(session('wish_list')) : 0 }}</span></a>
                    <a class="d-none d-lg-inline-block" data-bs-toggle="offcanvas" href="#shoppingCartOffcanvas"
                        role="button" aria-controls="shoppingCartOffcanvas"><i class="fa fa-shopping-cart"
                            aria-hidden="true"></i><span class="badge badge-danger total_cart_count"
                            id="total_cart_count">
                            {{ session()->has('cart') ? count(session()->get('cart')) : 0 }}
                        </span></a>

                    @if (auth('customer')->check())
                        <a href="{{ route('user-account') }}" class="d-none d-lg-inline-block"><i class="fa fa-user"
                                aria-hidden="true"></i></a>
                    @else
                        <a href="{{ route('customer.auth.login') }}" class="d-none d-lg-inline-block"><i
                                class="fa fa-user" aria-hidden="true"></i></a>
                    @endif



                </div>
            </div>
        </div>
    </div>
</header>

<!--end header-->
<!--start mobile menu-->
<div class="mobile-menu">
    <div class="mm-logo row align-items-center" style="background: #fff; padding: 11px 18px;">
        <a href="{{ route('home') }}" class="col-9">
            <img style="max-width: 100%; height: auto;"
                src="{{ asset('assets/storage/logo') . '/' . $web_config['mob_logo']->value }}"
                onerror="this.src='{{ asset('assets/frontend/img/image-place-holder.png') }}'" alt="">
        </a>
        <div class="mm-cross-icon col-3 text-right">
            <i class="fa fa-times mm-ci"></i>
        </div>
    </div>
    <div class="mm-menu">
        <div class="accordion" id="accordionExample">
            <div class="menu-box">
                <div class="menu-link">
                    <a href="{{ route('home') }}"><i class="fa fa-ptab3 mr-2"></i>Home</a>
                </div>
            </div>
            <div class="menu-box">
                <div class="menu-link" id="headingOne">
                    <a class="mmenu-btn menu-link-active" type="button" data-toggle="collapse"
                        data-target="#categories" aria-expanded="true"><i class="fa fa-ptab3 mr-2"></i>Categories<i
                            class="fa fa-plus"></i></a>
                </div>
                <div id="categories" class="menu-body collapse" aria-labelledby="headingOne"
                    data-parent="#accordionExample">
                    <div class="card-body">
                        <ul>
                            @foreach ($categories as $category)
                                <li class="mega-dd-btn-2">
                                    <div class="menu-link d-flex justify-content-between">
                                        <a
                                            href="{{ route('category.products', $category->slug) }}">{{ $category['name'] }}</a>
                                        <a data-toggle="collapse" type="button"
                                            data-target="#category__{{ $category['id'] }}" aria-expanded="true"><i
                                                class="fa fa-plus"></i></a>
                                    </div>
                                    @if ($category->subCategory->count() > 0)
                                        <div class="collapse" id="category__{{ $category['id'] }}">
                                            <div class="card card-body">
                                                <ul class="mega-item">
                                                    @foreach ($category->subCategory as $subCat)
                                                        <li class="mega-dd-btn-2">
                                                            <div class="menu-link d-flex justify-content-between">
                                                                <a
                                                                    href="{{ route('category.products', [$category->slug, $subCat->slug]) }}">{{ $subCat['name'] }}</a>
                                                                <a type="button" data-toggle="collapse"
                                                                    data-target="#subCategory__{{ $subCat['id'] }}"
                                                                    aria-expanded="true"><i
                                                                        class="fa fa-plus"></i></a>
                                                            </div>
                                                            @if ($subCat->childes->count() > 0)
                                                                <div class="collapse"
                                                                    id="subCategory__{{ $subCat['id'] }}">
                                                                    <div class="card card-body">
                                                                        <ul class="mega-item">
                                                                            @foreach ($subCat->childes as $childCat)
                                                                                <li><a
                                                                                        href="{{ route('category.products', [$category->slug, $subCat->slug, $childCat->slug]) }}">{{ $childCat['name'] }}</a>
                                                                                </li>
                                                                            @endforeach
                                                                        </ul>
                                                                    </div>
                                                                </div>
                                                            @endif
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        </div>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
            <div class="menu-box">
                <div class="menu-link">
                    <a href="{{ route('shop') }}"><i class="fa fa-ptab3 mr-2"></i>
                        Shop</a>
                </div>
            </div>
            <div class="menu-box">
                <div class="menu-link">
                    <a href="{{ route('video_shopping') }}"><i class="fa fa-ptab3 mr-2"></i>
                        Video Shopping</a>
                </div>
            </div>
            <div class="menu-box">
                <div class="menu-link">
                    <a href="{{ route('offers.product') }}"><i class="fa fa-ptab3 mr-2"></i>Special Offer</a>
                </div>
            </div>
            <div class="menu-box">
                <div class="menu-link">
                    <a href="{{ route('outlets') }}"><i class="fa fa-ptab3 mr-2"></i>Our outlets</a>
                </div>
            </div>
            <div class="menu-box">
                <div class="menu-link">
                    <a href="{{ route('careers') }}"><i class="fa fa-ptab3 mr-2"></i>Careers</a>
                </div>
            </div>
            {{-- <div class="menu-box mt-2 text-white">

            </div> --}}
        </div>
    </div>
    <div class="menu-link"
        style="
    position: absolute;
    left: 0;
    bottom: 0;
    width: 100%;
    height: auto;
    color: #fff;
">
        @if (auth('customer')->check())
            <a href="{{ route('user-account') }}" class="btn btn-primary"><i class="fa fa-ptab3 mr-2"></i>
                User Dashboard</a>
        @else
            <a href="{{ route('customer.auth.login') }}" class="btn btn-primary"><i
                    class="fa fa-ptab3 mr-2"></i>Login</a>
        @endif
    </div>
</div>
