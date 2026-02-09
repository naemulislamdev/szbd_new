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
