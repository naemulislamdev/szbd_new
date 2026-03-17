@extends('web.layouts.app')

@section('title', 'Order Complete')

@push('css_or_js')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Montserrat&display=swap');

        body {
            font-family: 'Montserrat', sans-serif;
        }

        .checkout-card {
            border: none;
            background: #fff;
            box-shadow: rgba(60, 64, 67, 0.3) 0px 1px 2px 0px, rgba(60, 64, 67, 0.15) 0px 2px 6px 2px;
            border-radius: 12px
        }

        .checkout-card .card-title {
            font-weight: 600;
        }

        .checkout-card .order_number .h5 {
            font-size: 16px;
            font-weight: 600;
        }

        .checkout-card .border-bottom {
            border-bottom: 1px solid #eef0f1 !important;
        }

        .order_summery {
            border: 1px dotted #d4d7d8;
            border-radius: 20px;
        }
    </style>
@endpush

@section('content')
    <div class="container mt-5 mb-5">
        <div class="row d-flex justify-content-center">
            <div class="col-md-7 mx-auto">
                <div class="card">
                    @if (auth('customer')->check())

                        {{-- new card --}}
                        @php($summary = \App\CPU\OrderManager::order_summary($order))
                        @php($extra_discount = 0)
                        <?php
                        if ($order['extra_discount_type'] == 'percent') {
                            $extra_discount = ($summary['subtotal'] / 100) * $order['extra_discount'];
                        } else {
                            $extra_discount = $order['extra_discount'];
                        }
                        ?>
                        <div class="card checkout-card border-none">
                            <div class="text-center">
                                <img class="card-img-top" style="max-width: 150px; height: auto;"
                                    src="{{ asset('assets/frontend/images/placed.gif') }}" alt="place image">
                            </div>
                            <div class="card-body ">
                                <div class="text-center">
                                    <h2 style="color: #237227" class="card-title my-0 text-center">Order Confirmed!</h2>
                                    <h5 style="font-weight: 600;">{{ auth('customer')->user()->f_name }}</h5>
                                </div>

                                <div class="d-flex justify-content-between align-items-center border-bottom pb-2">
                                    <div class="order_number">
                                        <h6 style="font-size: 16px; font-weight: 600;">Order Number</h6>
                                        <h6 style="font-size: 14px; color: #ff5d00; font-weight: 600;">
                                            #{{ $order->order_number }}</h6>
                                    </div>
                                    <div class="order_date">
                                        <h6 style="font-size: 16px; font-weight: 600;">Date</h6>
                                        <h6 style="font-size: 14px; font-weight: 600">
                                            <span>{{ $order->created_at->format('d F Y') }}</span>
                                        </h6>
                                    </div>
                                </div>
                                <div class="order_summery p-3 mt-3">
                                    <div class="d-flex">
                                        <i class="fa fa-shopping-bag text-muted" aria-hidden="true"></i>
                                        <h6 class="ml-2" style="font-weight: 600">Order Summery</h6>
                                    </div>
                                    <div class="row my-3 rounded">
                                        <div class="col-md-12 mx-auto">
                                            <div class="product-details"
                                                style="box-shadow: rgba(99, 99, 99, 0.2) 0px 2px 8px 0px;">
                                                @foreach ($order->details as $key => $detail)
                                                    @php($product = json_decode($detail->product_details, true))
                                                    <tr>
                                                        <div class="row mb-2">
                                                            <div class="col-md-12 d-flex pl-0">
                                                                <div class="col-2 for-tab-img">
                                                                    @if ($detail['color_image'])
                                                                        <img class="d-block mr-2 rounded-l"
                                                                            onerror="this.src='{{ asset('assets/frontend/img/placeholder.jpg') }}'"
                                                                            src="{{ $detail['color_image'] }}"
                                                                            alt="VR Collection" width="60">
                                                                    @else
                                                                        <img class="d-block mr-2"
                                                                            onerror="this.src='{{ asset('assets/frontend/img/placeholder.jpg') }}'"
                                                                            src="{{ asset('assets/storage/product/thumbnail') }}/{{ $product['thumbnail'] }}"
                                                                            alt="VR Collection" width="60">
                                                                    @endif
                                                                </div>
                                                                <div class="col-10 for-glaxy-name pt-2"
                                                                    style="vertical-align:middle; font-weight: 600;">

                                                                    <a style="font-weight: 600;" class="text-dark"
                                                                        href="{{ route('product', [$product['slug']]) }}">
                                                                        {{ isset($product['name']) ? Str::limit($product['name'], 50) : '' }}
                                                                    </a><br>
                                                                    @if ($detail->variant)
                                                                        <span>variant: </span>
                                                                        <span style="font-size: 20px">
                                                                            {{ $detail->variant }}</span>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </tr>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                    <table class="table table-borderless">
                                        <tbody class="totals">

                                            <tr>
                                                <td>
                                                    <div
                                                        class="text-{{ Session::get('direction') === 'rtl' ? 'right' : 'left' }}">
                                                        <span class="product-qty ">Quantity</span>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div
                                                        class="text-{{ Session::get('direction') === 'rtl' ? 'left' : 'right' }}">
                                                        <span>{{ $order->details[0]['qty'] }}</span>
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
                                                        <span class="product-qty ">TAX Fee</span>
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
                                                            <span class="product-qty ">Delivery Charge</span>
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
                                                        <span class="product-qty "> Discount on Product</span>
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
                                    <div class="d-flex  mt-4">
                                        <div class="">
                                            <a href="{{ route('home') }}" class="btn btn-primary">
                                                <i class="fa fa-long-arrow-left" aria-hidden="true"></i>
                                                Back to Home
                                            </a>
                                        </div>
                                        <div class="ml-3">
                                            <a href="{{ route('account-oder') }}" class="btn btn-success ">
                                                Check Your orders
                                            </a>
                                        </div>
                                    </div>
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
