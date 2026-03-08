@extends('web.layouts.app')
@section('title', 'Eid Offers')

@push('css_or_js')
    <meta property="og:image" content="{{ asset('storage/company') }}/{{ $web_config['web_logo']->value }}" />
    <meta property="og:title" content="Premium Clothing & Original Skincare |" />
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
                        <h3> Eid offers ✨🌙</h3>
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
                        <button class="grid-btn" data-columns="5" data-category="category1">
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
            <div class="row product-grid ">
                @php $decimal_point_settings = \App\CPU\Helpers::get_business_settings('decimal_point_settings'); @endphp
                <!-- Your product columns go here -->
                @if ($eidoffer->product_ids != null)
                    @php
                        $productIds = json_decode($eidoffer->product_ids, true) ?? [];
                        $eidOfferProducts = \App\Models\Product::whereIn('id', $productIds)->get();
                    @endphp
                    @foreach ($eidOfferProducts as $product)
                        @include('web.products.product_box', ['dataCategory' => 'category1'])
                    @endforeach
                @endif
            </div>
        </div>
    </section>
@endsection
