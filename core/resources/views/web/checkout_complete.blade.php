@extends('web.layouts.app')

@section('title', 'Order Complete')

@push('css_or_js')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Montserrat&display=swap');

        body {
            font-family: 'Montserrat', sans-serif;
        }

        .card {
            border: none;
        }

        .table {
            box-shadow: rgba(100, 100, 111, 0.2) 0px 7px 29px 0px;
            border-radius: 10px;
            margin-top: 15px;
            border: 2px solid #f26d21;
        }
    </style>
@endpush

@section('content')
    <div class="container mt-5 mb-5">
        <div class="row d-flex justify-content-center">
            <div class="col-md-10 col-lg-10">
                <div class="card">
                    @if (auth('customer')->check())
                        <div class=" p-5">
                            <div class="row">
                                <div class="col-md-12">
                                    <h5 style="font-size: 20px; font-weight: 900; text-align:center;">
                                        Your order has been placed successfully!</h5>
                                </div>
                            </div>

                            <div class="row mb-4">
                                <div class="col-12">
                                    <center>
                                        <i style="font-size: 100px; color: #0f9d58" class="fa fa-check-circle"></i>
                                    </center>
                                </div>
                            </div>

                            <span class="font-weight-bold d-block mt-4" style="font-size: 17px;text-align:center;">Hello,
                                {{ auth('customer')->user()->f_name }}</span>
                            <span style="text-align:center; display: block; margin-bottom: 8px;">You order has been
                                confirmed and will be shipped according to the method you selected!</span>
                            @php($summary = \App\CPU\OrderManager::order_summary($order))
                            @php($extra_discount = 0)
                            <?php
                            if ($order['extra_discount_type'] == 'percent') {
                                $extra_discount = ($summary['subtotal'] / 100) * $order['extra_discount'];
                            } else {
                                $extra_discount = $order['extra_discount'];
                            }
                            ?>
                            <div class="row mb-3">
                                <div class="col-md-7 mx-auto">
                                    <div class="product-details" style="box-shadow: rgba(99, 99, 99, 0.2) 0px 2px 8px 0px;">
                                        @foreach ($order->details as $key => $detail)
                                            @php($product = json_decode($detail->product_details, true))
                                            <tr>
                                                <div class="row mb-2">
                                                    <div class="col-md-12 d-flex">
                                                        <div class="col-4 for-tab-img">
                                                            @if ($detail['color_image'])
                                                                <img class="d-block mr-2"
                                                                    onerror="this.src='{{ asset('assets/frontend/img/image-place-holder.png') }}'"
                                                                    src="{{ $detail['color_image'] }}" alt="VR Collection"
                                                                    width="60">
                                                            @else
                                                                <img class="d-block mr-2"
                                                                    onerror="this.src='{{ asset('assets/frontend/img/image-place-holder.png') }}'"
                                                                    src="{{ asset('assets/storage/product/thumbnail') }}/{{ $product['thumbnail'] }}"
                                                                    alt="VR Collection" width="60">
                                                            @endif
                                                        </div>
                                                        <div class="col-8 for-glaxy-name" style="vertical-align:middle;">

                                                            <a href="{{ route('product', [$product['slug']]) }}">
                                                                {{ isset($product['name']) ? Str::limit($product['name'], 40) : '' }}
                                                            </a><br>
                                                            @if ($detail->variant)
                                                                <span>variant: </span>
                                                                {{ $detail->variant }}
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </tr>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            <div class="row d-flex justify-content-center">
                                <div class="col-md-8 col-lg-5">
                                    <table class="table table-borderless">
                                        <tbody class="totals">
                                            <tr>
                                                <td>
                                                    <div
                                                        class="text-{{ Session::get('direction') === 'rtl' ? 'right' : 'left' }}">
                                                        <span class="product-qty ">Order Id</span>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div
                                                        class="text-{{ Session::get('direction') === 'rtl' ? 'left' : 'right' }}">
                                                        <span>#{{ $order->id }}</span>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <div
                                                        class="text-{{ Session::get('direction') === 'rtl' ? 'right' : 'left' }}">
                                                        <span class="product-qty ">Item</span>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div
                                                        class="text-{{ Session::get('direction') === 'rtl' ? 'left' : 'right' }}">
                                                        <span>{{ $order->details->count() }}</span>
                                                    </div>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td>
                                                    <div
                                                        class="text-{{ Session::get('direction') === 'rtl' ? 'right' : 'left' }}">
                                                        <span class="product-qty ">Subtotal</span>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div
                                                        class="text-{{ Session::get('direction') === 'rtl' ? 'left' : 'right' }}">
                                                        <span>{{ $summary['subtotal'] }}</span>
                                                    </div>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td>
                                                    <div
                                                        class="text-{{ Session::get('direction') === 'rtl' ? 'right' : 'left' }}">
                                                        <span class="product-qty ">text_fee</span>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div
                                                        class="text-{{ Session::get('direction') === 'rtl' ? 'left' : 'right' }}">
                                                        <span>{{ $summary['total_tax'] }}</span>
                                                    </div>
                                                </td>
                                            </tr>
                                            @if ($order->order_type == 'default_type')
                                                <tr>
                                                    <td>
                                                        <div
                                                            class="text-{{ Session::get('direction') === 'rtl' ? 'right' : 'left' }}">
                                                            <span class="product-qty ">Shipping
                                                                Fee</span>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div
                                                            class="text-{{ Session::get('direction') === 'rtl' ? 'left' : 'right' }}">
                                                            <span>{{ $summary['total_shipping_cost'] }}</span>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endif

                                            <tr>
                                                <td>
                                                    <div
                                                        class="text-{{ Session::get('direction') === 'rtl' ? 'right' : 'left' }}">
                                                        <span class="product-qty "> Discount on product</span>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div
                                                        class="text-{{ Session::get('direction') === 'rtl' ? 'left' : 'right' }}">
                                                        <span>-
                                                            {{ $summary['total_discount_on_product'] }}</span>
                                                    </div>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td>
                                                    <div
                                                        class="text-{{ Session::get('direction') === 'rtl' ? 'right' : 'left' }}">
                                                        <span class="product-qty ">Coupon
                                                            Discount</span>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div
                                                        class="text-{{ Session::get('direction') === 'rtl' ? 'left' : 'right' }}">
                                                        <span>-
                                                            {{ $order->discount_amount }}</span>
                                                    </div>
                                                </td>
                                            </tr>

                                            @if ($order->order_type != 'default_type')
                                                <tr>
                                                    <td>
                                                        <div
                                                            class="text-{{ Session::get('direction') === 'rtl' ? 'right' : 'left' }}">
                                                            <span class="product-qty ">extra
                                                                Discount</span>
                                                        </div>
                                                    </td>

                                                    <td>
                                                        <div
                                                            class="text-{{ Session::get('direction') === 'rtl' ? 'left' : 'right' }}">
                                                            <span>-
                                                                {{ $extra_discount }}</span>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endif

                                            <tr class="border-top border-bottom">
                                                <td>
                                                    <div
                                                        class="text-{{ Session::get('direction') === 'rtl' ? 'right' : 'left' }}">
                                                        <span class="font-weight-bold">Total</span>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div
                                                        class="text-{{ Session::get('direction') === 'rtl' ? 'left' : 'right' }}">
                                                        <span
                                                            class="font-weight-bold amount ">{{ $order->order_amount + $summary['total_shipping_cost'] }}</span>
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="row mt-4">
                                <div class="col-6">
                                    <a href="{{ route('home') }}" class="btn btn-primary w-100">
                                        Go to shopping
                                    </a>
                                </div>
                                <div class="col-6">
                                    <a href="{{ route('account-oder') }}" class="btn btn-secondary pull-right w-100">
                                        Check orders
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        window.dataLayer = window.dataLayer || [];
        dataLayer.push({
            event: "purchase",
            ecommerce: {
                transaction_id: "{{ $order->order_id }}",
                affiliation: "My eCommerce Store",
                value: {{ $order->order_amount ?? 0 }},
                tax: 0.00,
                shipping: {{ $order->shipping_cost ?? 0 }},
                currency: "BDT",
                coupon: "",
                items: [
                    @foreach ($order->details as $detail)
                        {
                            item_id: "{{ $detail->product->id ?? '' }}",
                            item_name: "{{ $detail->product->name ?? '' }}",
                            item_brand: "Shopping Zone BD",
                            item_category: "General",
                            price: {{ $detail->price ?? 0 }},
                            quantity: {{ $detail->qty }}
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
        fbq('track', 'Purchase', {
            contents: [
                @foreach ($order->details as $detail)
                    {
                        id: '{{ $detail->product_id }}',
                        quantity: {{ $detail->qty }},
                        item_price: {{ $detail->price ?? 0 }}
                    },
                @endforeach
            ],
            content_type: 'product',
            value: {{ $order->order_amount ?? 0 }},
            currency: 'BDT'
        });
    </script>

    <script>
        ttq.identify({
            "email": "<hashed_email_address>", // string. The email of the customer if available. It must be hashed with SHA-256 on the client side.
            "phone_number": "<hashed_phone_number>", // string. The phone number of the customer if available. It must be hashed with SHA-256 on the client side.
            "external_id": "<hashed_external_id>" // string. Any unique identifier, such as loyalty membership IDs, user IDs, and external cookie IDs.It must be hashed with SHA-256 on the client side.
        });

        ttq.track('ViewContent', {
            "contents": [{
                "content_id": "<content_identifier>", // string. ID of the product. Example: "1077218".
                "content_type": "<content_type>", // string. Either product or product_group.
                "content_name": "<content_name>" // string. The name of the page or product. Example: "shirt".
            }],
            "value": "<content_value>", // number. Value of the order or items sold. Example: 100.
            "currency": "<content_currency>" // string. The 4217 currency code. Example: "USD".
        });

        ttq.track('Search', {
            "contents": [{
                "content_id": "<content_identifier>", // string. ID of the product. Example: "1077218".
                "content_type": "<content_type>", // string. Either product or product_group.
                "content_name": "<content_name>" // string. The name of the page or product. Example: "shirt".
            }],
            "value": "<content_value>", // number. Value of the order or items sold. Example: 100.
            "currency": "<content_currency>", // string. The 4217 currency code. Example: "USD".
            "search_string": "<search_keywords>" // string. The word or phrase used to search. Example: "SAVE10COUPON".
        });

        ttq.track('ClickButton', {
            "contents": [{
                "content_id": "<content_identifier>", // string. ID of the product. Example: "1077218".
                "content_type": "<content_type>", // string. Either product or product_group.
                "content_name": "<content_name>" // string. The name of the page or product. Example: "shirt".
            }],
            "value": "<content_value>", // number. Value of the order or items sold. Example: 100.
            "currency": "<content_currency>" // string. The 4217 currency code. Example: "USD".
        });

        ttq.track('AddToWishlist', {
            "contents": [{
                "content_id": "<content_identifier>", // string. ID of the product. Example: "1077218".
                "content_type": "<content_type>", // string. Either product or product_group.
                "content_name": "<content_name>" // string. The name of the page or product. Example: "shirt".
            }],
            "value": "<content_value>", // number. Value of the order or items sold. Example: 100.
            "currency": "<content_currency>" // string. The 4217 currency code. Example: "USD".
        });

        ttq.track('Subscribe', {
            "contents": [{
                "content_id": "<content_identifier>", // string. ID of the product. Example: "1077218".
                "content_type": "<content_type>", // string. Either product or product_group.
                "content_name": "<content_name>" // string. The name of the page or product. Example: "shirt".
            }],
            "value": "<content_value>", // number. Value of the order or items sold. Example: 100.
            "currency": "<content_currency>" // string. The 4217 currency code. Example: "USD".
        });

        ttq.track('CompleteRegistration', {
            "contents": [{
                "content_id": "<content_identifier>", // string. ID of the product. Example: "1077218".
                "content_type": "<content_type>", // string. Either product or product_group.
                "content_name": "<content_name>" // string. The name of the page or product. Example: "shirt".
            }],
            "value": "<content_value>", // number. Value of the order or items sold. Example: 100.
            "currency": "<content_currency>" // string. The 4217 currency code. Example: "USD".
        });
    </script>
@endpush
