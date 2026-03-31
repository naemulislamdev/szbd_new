@extends('web.layouts.app')
@section('title', 'Special Offers | ' . $web_config['name']->value)
@section('meta_description',
    'Shop special offers and get premium clothing and skincare products at discounted prices.
    ')
@section('content')
    <section>
        <div class="container">
            {{-- Bredcrumb start  --}}
            <nav class="breadcrumb custom-breadcrumb mt-3">
                <a class="breadcrumb-item" href="{{ route('home') }}">Home</a>
                <span class="breadcrumb-item active" aria-current="page">Special Offer</span>
            </nav>
            {{--  Bredcrumb End --}}
            {{-- Product Filter section --}}
            @include('web.layouts.partials.product_filter')

            <div class="row mb-3">
                <div class="col text-center">
                    <div class="section-heading-title">
                        <h1>Our Special Offers</h1>
                        <div class="heading-border"></div>
                    </div>


                </div>
            </div>
            @if (count($selling_products) > 0)
                <div class="row" id="selling-ajax-products">
                    @include('web.products.selling_ajax_products', ['products' => $selling_products])
                </div>
                <div class="row">
                    <div class="col-12 d-flex align-items-center justify-content-center">
                        <nav class="d-flex justify-content-between pt-2" aria-label="Page navigation">
                            {!! $selling_products->links() !!}
                        </nav>
                    </div>
                </div>
            @else
                <div class="text-center pt-5">
                    <h2>Product Coming Soon!</h2>
                </div>
            @endif
        </div>
    </section>
@endsection
@push('scripts')
    <script>
        function searchByPrice() {
            let min = $('#min_price').val();
            let max = $('#max_price').val();
            $.get({
                url: '{{ url('/') }}/selling-product',
                data: {
                    min_price: min,
                    max_price: max,
                },
                dataType: 'json',
                beforeSend: function() {
                    $('#loading').show();
                },
                success: function(response) {
                    $('#selling-ajax-products').html(response.view);
                },
                complete: function() {
                    $('#loading').hide();
                },
            });
        }

        $("#search-brand").on("keyup", function() {
            var value = this.value.toLowerCase().trim();
            $("#lista1 div>li").show().filter(function() {
                return $(this).text().toLowerCase().trim().indexOf(value) == -1;
            }).hide();
        });
        $("#search-brand-m").on("keyup", function() {
            var value = this.value.toLowerCase().trim();
            $("#lista1 div>li").show().filter(function() {
                return $(this).text().toLowerCase().trim().indexOf(value) == -1;
            }).hide();
        });
    </script>
    <script>
        cartQuantityInitialize();
    </script>
    <script>
        //When scroll display block in filter section other wise display none
        window.addEventListener('scroll', function() {
            const header = document.getElementById('filter-box');
            if (window.scrollY > 100) {
                header.classList.add('scrolled');
            } else {
                header.classList.remove('scrolled');
            }
        });
    </script>
@endpush
