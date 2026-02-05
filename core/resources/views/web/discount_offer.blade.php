@extends('layouts.front-end.app')
@section('title', \App\CPU\translate('Welcome To') . ' ' . $web_config['name']->value)

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
            <div class="row product-grid">
                @php $decimal_point_settings = \App\CPU\Helpers::get_business_settings('decimal_point_settings'); @endphp
                <!-- Your product columns go here -->
                    @if ($discount_offers->product_ids != null)
                        @php
                            $productIds = json_decode($discount_offers->product_ids, true) ?? [];
                            $discountOfferProducts = \App\Model\Product::whereIn('id', $productIds)->get();
                        @endphp
                        @foreach ($discountOfferProducts as $product)
                            @include('web-views.products.product_box', ['product' => $product])
                        @endforeach
                    @endif
            </div>
        </div>
    </section>
@endsection
