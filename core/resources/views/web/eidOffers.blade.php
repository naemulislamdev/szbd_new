@extends('web.layouts.app')
@section('title', Str::limit($eidoffer->title, 60) . ' | ' . $web_config['name']->value)
@section('meta_description',
    'Celebrate Eid 2026 with exclusive deals on clothing and original skincare products.
    ')

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
            {{-- Bredcrumb start  --}}
            <nav class="breadcrumb custom-breadcrumb mt-3">
                <a class="breadcrumb-item" href="{{ route('home') }}">Home</a>
                <span class="breadcrumb-item active" aria-current="page">{{ $eidoffer->title }}</span>
            </nav>
            {{--  Bredcrumb End --}}
            <div class="mb-4">
                <div class=" text-center">
                    <div class="section-heading-title position-relative z-30 ">
                        <h1>{{ $eidoffer->title }}</h1>
                        <div class="heading-border"></div>
                    </div>


                </div>
            </div>
            <div class="row  ">
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
