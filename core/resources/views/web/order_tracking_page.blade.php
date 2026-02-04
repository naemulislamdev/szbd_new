@extends('web.layouts.app')

@section('title', 'Track Order Result')

@push('css_or_js')
    <meta property="og:type" content="website">
    <meta property="og:title" content="{{ strip_tags($web_config['name']->value) }}">
    <meta property="og:description" content="{{ substr(strip_tags($web_config['about']->value), 0, 150) }}">
    <meta property="og:image" content="{{ asset('assets/storage/company/' . $web_config['web_logo']->value) }}">
    <meta property="og:url" content="{{ url()->current() }}">

    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ strip_tags($web_config['name']->value) }}">
    <meta name="twitter:description" content="{{ substr(strip_tags($web_config['about']->value), 0, 150) }}">
    <meta name="twitter:image" content="{{ asset('assets/storage/company/' . $web_config['web_logo']->value) }}">
    <meta name="twitter:url" content="{{ url()->current() }}">
    <style>
        .order-track {

            box-shadow: rgba(0, 0, 0, 0.24) 0px 3px 8px;
            border-radius: 10px;
            background: #ff5d00;
        }

        .closet {
            font-size: 1.5rem;
            font-weight: 300;
            line-height: 1;
            color: #4b566b;
            text-shadow: none;
            opacity: .5;
        }

        .breadcrumb {
            background: none;

        }

        .breadcrumb-link {
            color: #f26d21;
            font-weight: 500;
            text-decoration: none;
        }

        .breadcrumb-link:hover {
            color: #d85e1c;

        }

        .breadcrumb-active {
            color: #f26d21;
            font-weight: 600;
        }
    </style>
@endpush

@section('content')
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent px-0 mb-2">
                <li class="breadcrumb-item">
                    <a href="{{ url('/') }}" class="breadcrumb-link">Home</a>
                </li>
                <li class="breadcrumb-item active breadcrumb-active" aria-current="page">
                    Track Your Order
                </li>
            </ol>
        </nav>
    </div>


    <!-- Page Content-->
    <div class="container rtl"
        style="height: 100vh; text-align: {{ Session::get('direction') === 'rtl' ? 'right' : 'left' }};">
        <div class="row">

            <div class="col-md-6 col-lg-5 mx-auto">
                <div class="container py-4 mb-2 mb-md-3">

                    <div class=" order-track">
                        <div style="margin: 0 auto; padding: 15px;">
                            <div class="text-center">
                                <img style="width: 100px; border-radius: 50%"
                                    src="{{ asset('assets/frontend/img/track-result/track.png') }}" alt="">

                                <h2 style="padding: 20px; text-align: center; color:#fff;">
                                    Track Your Order</h2>
                            </div>

                            <form action="{{ route('track-order.result') }}" type="submit" method="post"
                                style="padding: 15px;">
                                @csrf

                                @if (session()->has('Error'))
                                    <div class="alert alert-danger alert-block">
                                        <span type="" class="closet " data-dismiss="alert">×</span>
                                        <strong>{{ session()->get('Error') }}</strong>
                                    </div>
                                @endif

                                <div class="form-group">
                                    <input class="form-control prepended-form-control" type="text" name="order_id"
                                        placeholder="Enter order id" required>
                                </div>
                                <div class="form-group">
                                    <input class="form-control prepended-form-control" type="text" name="phone_number"
                                        placeholder="Enter your phone number" required>
                                </div>
                                <div class="input-group-append mx-auto">
                                    <button class="btn btn-light w-50" type="submit" name="trackOrder">
                                        <i class="fa fa-search" aria-hidden="true"></i>
                                        Track order</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

@endsection

{{--
@push('scripts')
    <script src="{{ asset('assets/frontend') }}/vendor/nouislider/distribute/nouislider.min.js"></script>
@endpush --}}
