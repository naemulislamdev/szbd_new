@extends('web.layouts.app')

@section('title', 'Customer Dahboard')

@push('css_or_js')
    <style>
        .headerTitle {
            font-size: 24px;
            font-weight: 600;
            margin-top: 1rem;
        }

        body {
            font-family: 'Titillium Web', sans-serif
        }

        .product-qty span {
            font-size: 14px;
            color: #6A6A6A;
        }

        .font-nameA {
            font-weight: 600;
            display: inline-block;
            margin-bottom: 0;
            font-size: 17px;
            color: #030303;
        }

        .spandHeadO {
            color: #FFFFFF !important;
            font-weight: 600 !important;
            font-size: 14px !important;

        }

        .tdBorder {
            text-align: center;
        }

        .bodytr {
            text-align: center;
        }

        .modal-footer {
            border-top: none;
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
    @stack('c_css')
@endpush
@section('content')

    <div class="container pb-5 mb-2 mb-md-4 mt-3">
        <div class="row">
            <!-- Sidebar-->
            @include('customer.partials.sidebar')
            <!-- Content  -->
            <section class="col-lg-9 col-md-9">
                @yield('customer_content')
            </section>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"
        integrity="sha256-+qdLaH1K0p3uFj2Fq6jrbC8dZv1f2G1Oq5qS6VGk0yk=" crossorigin="anonymous"></script>
    @stack('c_script')
@endpush
