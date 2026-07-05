@extends('customer.layouts.master')

@section('title', 'My Wishlists')

@push('css_or_js')
    <style>
        .headerTitle {
            font-size: 24px;
            font-weight: 600;
            margin-top: 1rem;
        }

        body {
            font-family: sans-serif !important;
        }

        @media (max-width: 600px) {
            .sidebar_heading {
                background: {{ $web_config['primary_color'] }};
            }

            .sidebar_heading h1 {
                text-align: center;
                color: aliceblue;
                padding-bottom: 17px;
                font-size: 19px;
            }
        }
    </style>
@endpush

@section('customer_content')
    {{-- Bredcrumb start  --}}
    <nav class="breadcrumb custom-breadcrumb mt-3">
        <a class="breadcrumb-item" href="{{ route('home') }}">Home</a>
        <span class="breadcrumb-item active" aria-current="page">Wishlist</span>
    </nav>
    {{--  Bredcrumb End --}}
    <!-- Page Title-->
    <div class="row">
        <div class="col-md-3"></div>
        <div class="col-md-9 sidebar_heading">
            <h1 class="h3  mb-0 headerTitle">WISHLIST</h1>
        </div>
    </div>
    <!-- Page Content-->
    @include('customer.partials.wish_list_data', ['wishlists' => $wishlists])
@endsection
