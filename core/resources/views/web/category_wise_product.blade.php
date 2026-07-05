@extends('web.layouts.app')
@php
    $categoryTitle = 'All Products'; // default

    if (!empty($data['cat']->name) && !empty($data['subCat']->name) && !empty($data['childCat']->name)) {
        $categoryTitle = $data['cat']->name . ' | ' . $data['subCat']->name . ' | ' . $data['childCat']->name;
    } elseif (!empty($data['cat']->name) && !empty($data['subCat']->name)) {
        $categoryTitle = $data['cat']->name . ' | ' . $data['subCat']->name;
    } elseif (!empty($data['cat']->name)) {
        $categoryTitle = $data['cat']->name;
    }
@endphp

@section('title', Str::limit($categoryTitle . ' | ' . $web_config['name']->value, 60))
@section('meta_description',
    Str::limit(
    'Explore ' .
    $categoryTitle .
    ' products and shop premium clothing and skincare
    items.',
    100,
    ))

    @push('css_or_js')
        <meta property="og:image" content="{{ asset('assets/storage/company') }}/{{ $web_config['web_logo'] }}" />
        <meta property="og:title" content="Products of {{ $web_config['name'] }} " />
        <meta property="og:url" content="{{ env('APP_URL') }}">
        <meta property="og:description" content="{!! substr(strip_tags($web_config['about']->value), 0, 100) !!}">

        <meta property="twitter:card" content="{{ asset('assets/storage/company') }}/{{ $web_config['web_logo'] }}" />
        <meta property="twitter:title" content="Products of {{ $web_config['name'] }}" />
        <meta property="twitter:url" content="{{ env('APP_URL') }}">
        <meta property="twitter:description" content="{!! substr(strip_tags($web_config['about']->value), 0, 100) !!}">
    @endpush
@section('content')
    <section class="py-3">
        <div class="container">
            {{-- Product Filter section --}}
            @include('web.layouts.partials.product_filter')
            {{-- Product grid system section --}}
            {{-- Breadcrumb start --}}
            <nav class="breadcrumb custom-breadcrumb mt-2 mb-3 bg-white">
                <a class="breadcrumb-item" href="{{ route('home') }}">HOME</a>

                @if (!empty($data['cat']->name))
                    @if (!empty($data['subCat']->name) || !empty($data['childCat']->name))
                        <a class="breadcrumb-item text-capitalize"
                            href="{{ route('category.products', $data['cat']->slug) }}">
                            {{ $data['cat']->name }}
                        </a>
                    @else
                        <span class="breadcrumb-item text-capitalize active"
                            aria-current="page">{{ $data['cat']->name }}</span>
                    @endif
                @endif

                @if (!empty($data['subCat']->name))
                    @if (!empty($data['childCat']->name))
                        <a class="breadcrumb-item text-capitalize"
                            href="{{ route('category.products', [$data['cat']->slug, $data['subCat']->slug]) }}">
                            {{ $data['subCat']->name }}
                        </a>
                    @else
                        <span class="breadcrumb-item text-capitalize active"
                            aria-current="page">{{ $data['subCat']->name }}</span>
                    @endif
                @endif

                @if (!empty($data['childCat']->name))
                    <span class="breadcrumb-item text-capitalize active"
                        aria-current="page">{{ $data['childCat']->name }}</span>
                @endif

                @if (empty($data['cat']->name))
                    <span class="breadcrumb-item text-capitalize active" aria-current="page">All Products</span>
                @endif
            </nav>
            {{--  Bredcrumb End --}}


            @if (count($products) > 0)
                <div class="row " id="ajax-products">
                    <!-- Your product columns go here -->
                    @include('web.products._ajax-products', ['products' => $products])
                </div>
                <hr class="my-3">
                <!-- Pagination-->
                <div class="row">
                    <div class="col-12 d-flex align-items-center justify-content-center">
                        <nav class="d-flex justify-content-between pt-2" aria-label="Page navigation">
                            {!! $products->links() !!}
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
        window.dataLayer = window.dataLayer || [];

        /* Product List View (Category Page) */
        dataLayer.push({
            event: "view_item_list",
            ecommerce: {
                item_list_id: "category_{{ $category->slug ?? 'default' }}",
                item_list_name: "{{ $data['data_from'] ?? 'Product List' }}",
                items: [
                    @foreach ($products as $index => $product)
                        {
                            item_id: "{{ $product->id }}",
                            item_name: "{{ $product->name }}",
                            item_brand: "{{ $product->brand->name ?? 'No Brand' }}",
                            item_category: "{{ $brand_name ?? 'General' }}",
                            item_variant: "{{ $product->variant ?? 'Default' }}",
                            price: {{ $product->unit_price }},
                            currency: "BDT",
                            index: {{ $loop->iteration }}
                        }
                        @if (!$loop->last)
                            ,
                        @endif
                    @endforeach
                ]
            }
        });
    </script>

    <script>
        function filter(value) {
            $.get({
                url: '{{ url('/') }}/products',
                data: {
                    sort_by: value
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

        function searchByPrice() {
            let min = $('#min_price').val();
            let max = $('#max_price').val();
            $.get({
                url: '{{ url('/') }}/products',
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
@endpush
