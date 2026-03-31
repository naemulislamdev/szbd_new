@extends('web.layouts.app')
@section('title', Str::limit($discount_offers->title, 60))
@section('meta_description',
    'Shop discount offers and enjoy special deals on premium clothing and skincare items.
    ')

    @push('css_or_js')
        <meta property="og:image" content="{{ asset('storage/company') }}/{{ $web_config['web_logo']->value }}" />
        <meta property="og:title" content="Best Online Marketplace In Bangladesh {{ $web_config['name']->value }} Home" />
        <meta property="og:url" content="{{ env('APP_URL') }}">
        <meta property="og:description" content="{!! substr(strip_tags($web_config['about']->value), 0, 100) !!}">

        <meta property="twitter:card" content="{{ asset('storage/company') }}/{{ $web_config['web_logo']->value }}" />
        <meta property="twitter:title" content="Welcome To {{ $web_config['name']->value }} Home" />
        <meta property="twitter:url" content="{{ env('APP_URL') }}">
        <meta property="twitter:description" content="{!! substr(strip_tags($web_config['about']->value), 0, 100) !!}">
    @endpush
@section('content')
    <section>
        <div class="container mt-4">
            <div class="mb-4">
                <div class=" text-center">
                    <div class="section-heading-title position-relative z-30 ">
                        <h1>{{ $discount_offers->title }}</h1>
                        <div class="heading-border"></div>
                    </div>

                </div>
            </div>
            <div class="row ">
                @php $decimal_point_settings = \App\CPU\Helpers::get_business_settings('decimal_point_settings'); @endphp
                <!-- Your product columns go here -->
                @if ($discount_offers->product_ids != null)
                    @php
                        $productIds = json_decode($discount_offers->product_ids, true) ?? [];
                        $discountOfferProducts = \App\Models\Product::whereIn('id', $productIds)->get();
                    @endphp
                    @foreach ($discountOfferProducts as $product)
                        @include('web.products.product_box', ['dataCategory' => 'category1'])
                    @endforeach
                @endif
            </div>
        </div>
    </section>
@endsection
