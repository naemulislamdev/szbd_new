@extends('web.layouts.app')
@section('title', 'New Arrival | ' . $web_config['name']->value)

@section('meta_description',
    'Discover the latest new arrivals with fresh styles, top quality, and unbeatable prices.
    Shop now before stock runs out.')
@section('content')
    <section>
        <div class="container">

            {{-- Bredcrumb start  --}}
            <nav class="breadcrumb custom-breadcrumb mt-3 bg-white">
                <a class="breadcrumb-item" href="{{ route('home') }}">Home</a>
                <span class="breadcrumb-item active" aria-current="page">New Arrival</span>
            </nav>
            {{--  Bredcrumb End --}}


            {{-- Product Filter section --}}
            @include('web.layouts.partials.product_filter')

            {{-- <div class="row mb-3">
                <div class="col text-center">
                    <div class="section-heading-title">
                        <h1>New Arrivals</h1>
                        <div class="heading-border"></div>
                    </div>

                </div>
            </div> --}}
            @if (count($shop_products) > 0)
                <div class="row " id="ajax-products">
                    <!-- Your product columns go here -->
                    @include('web.products._ajax-products', ['products' => $shop_products])
                </div>
                <hr class="my-3">
                <div class="row">
                    <div class="col-12 d-flex align-items-center justify-content-center">
                        <nav class="d-flex justify-content-between pt-2" aria-label="Page navigation">
                            {!! $shop_products->links() !!}
                        </nav>
                    </div>
                </div>
            @else
                <div class="text-center pt-5">
                    <h2>Product Coming Soon!</h2>
                </div>
            @endif

            <div class="row my-3">
                <div class="col-md-12">
                    <div class="big-banner">
                        <img src="{{ asset('assets/frontend') }}/images/product-banner/main-banner4.jpg" alt="Banner Image"
                            style="width: 100%;">
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@push('scripts')
    <script>
        function searchByPrice() {
            let min = $('#min_price').val();
            let max = $('#max_price').val();
            $.get({
                url: '{{ url('/') }}/shop',
                data: {
                    min_price: min,
                    max_price: max,
                },
                dataType: 'json',
                beforeSend: function() {
                    $('#loading').show();
                },
                success: function(response) {
                    $('#ajax-products').html(response.view);
                },
                complete: function() {
                    $('#loading').hide();
                },
            });
        }
    </script>
    <script>
        cartQuantityInitialize();
    </script>
@endpush
