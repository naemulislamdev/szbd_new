@extends('web.layouts.app')

@section('title', 'Order Complete | ' . $web_config['name']->value)
@section('meta_description',
    'Order complete! Thank you for purchasing premium clothing and skincare products.
    ')
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
    <div class="container mt-5 mb-5 px-0 px-lg-2">
        <div class="row d-flex justify-content-center">
            <div class="col-lg-7 mx-auto">
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
                        <div class="card checkout-card border-none" style="max-height: 100%">
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
                                        <div class="col-md-12 mx-auto px-0 px-md-2">
                                            <div class="product-details w-100"
                                                style="box-shadow: rgba(99, 99, 99, 0.2) 0px 2px 8px 0px;">
                                                @foreach ($order->details as $key => $detail)
                                                    @php($product = json_decode($detail->product_details, true))
                                                    <tr>
                                                        <div class="row mb-2">
                                                            <div class="col-md-12 d-flex ">
                                                                <div class="col-2 for-tab-img">
                                                                    @if ($detail['color_image'])
                                                                        <img class="d-block mr-2 rounded-l"
                                                                            onerror="this.src='{{ asset('assets/frontend/img/placeholder.jpg') }}'"
                                                                            src="{{ $detail['color_image'] }}"
                                                                            alt="{{ isset($product['name']) ? Str::limit($product['name'], 50) : '' }}"
                                                                            width="60">
                                                                    @else
                                                                        <img class="d-block mr-2"
                                                                            onerror="this.src='{{ asset('assets/frontend/img/placeholder.jpg') }}'"
                                                                            src="{{ asset('assets/storage/product/thumbnail') }}/{{ $product['thumbnail'] }}"
                                                                            alt="{{ isset($product['name']) ? Str::limit($product['name'], 50) : '' }}"
                                                                            width="60">
                                                                    @endif
                                                                </div>
                                                                <div class="col-10 for-glaxy-name pt-2 ml-2 ml-md-0 "
                                                                    style="vertical-align:middle; font-weight: 600;">

                                                                    <a style="font-weight: 600;" class="text-dark"
                                                                        href="{{ route('product', [$product['slug']]) }}">
                                                                        {{ isset($product['name']) ? Str::limit($product['name'], 50) : '' }}
                                                                    </a><br>
                                                                    @if ($detail->variant)
                                                                        <span style="font-size: 15px;">variant: </span>
                                                                        <span style="font-size: 20px">
                                                                            {{ $detail->variant }}</span>
                                                                    @endif
                                                                    <div>
                                                                        <span style="font-size: 15px;">Quantity: </span>

                                                                        <span style="font-size: 18px; font-weight: 800;">
                                                                            {{ $detail->qty }}
                                                                        </span>
                                                                    </div>
                                                                    <div>
                                                                        <span style="font-size: 15px;">Price: </span>

                                                                        {{-- @dd($detail) --}}
                                                                        <span style="font-size: 18px; font-weight: 800;">
                                                                            {{ $detail->price - $detail->discount }} X
                                                                            {{ $detail->qty }} =
                                                                            {{ ($detail->price - $detail->discount) * $detail->qty }}
                                                                        </span>
                                                                    </div>

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
                                                        <span>{{ $order['order_amount'] }}</span>
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
                                    <div class="d-flex checkout-complete-button  mt-4 ">
                                        <div class="">
                                            <a href="{{ route('home') }}" class="btn btn-primary mb-3 mb-md-0">
                                                <i class="fa fa-long-arrow-left" aria-hidden="true"></i>
                                                Back to Home
                                            </a>
                                        </div>
                                        <div class="ml-md-3">
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
    @if (session()->has('order') || isset($order))
        <script>
            window._pixelUserData = {
                em: '<?php echo auth('customer')->check() ? auth('customer')->user()->email : ''; ?>',
                ph: '<?php echo preg_replace('/\D/', '', $shippingData['phone'] ?? ''); ?>'
            };
        </script>
    @endif
    <script>
        var fbp = (document.cookie.match('(^|;)\\s*_fbp\\s*=\\s*([^;]+)') || [])[2] || '';
        var fbc = (document.cookie.match('(^|;)\\s*_fbc\\s*=\\s*([^;]+)') || [])[2] || '';

        <?php
        $firstDetail = $order->details->first();
        $firstProduct = json_decode($firstDetail->product_details, true);
        $shippingData = json_decode($order->shipping_address_data, true);
        $i = 0;
        $total = count($order->details);

        $fullName = $shippingData['contact_person_name'] ?? '';
        $nameParts = explode(' ', trim($fullName), 2);
        $firstName = $nameParts[0] ?? '';
        $lastName = $nameParts[1] ?? '';

        foreach ($order->details as $detail) {
            $prod = json_decode($detail->product_details, true);
            $categoryId = $prod['category_id'] ?? null;
            $detail->category_name = $categoryId ? \App\Models\Category::find($categoryId)?->name ?? '' : '';
        }
        $firstCategoryName = $firstDetail->category_name ?? '';
        $customerEmail = auth('customer')->check() ? auth('customer')->user()->email : '';
        $phoneClean = preg_replace('/\D/', '', $shippingData['phone'] ?? '');
        ?>

        // GA4
        window.dataLayer = window.dataLayer || [];
        window.dataLayer.push({
            'event': 'purchase',
            '_fbp': fbp,
            '_fbc': fbc,
            'ecommerce': {
                'transaction_id': '{{ $order->order_number }}',
                'currency': 'BDT',
                'value': {{ (float) ($order->order_amount + $summary['total_shipping_cost']) }},
                'tax': {{ (float) $summary['total_tax'] }},
                'shipping': {{ (float) $summary['total_shipping_cost'] }},
                'coupon': '{{ $order->coupon_code ?? '' }}',
                'items': [
                    <?php $i = 0; foreach ($order->details as $detail):
                    $product = json_decode($detail->product_details, true);
                    $i++;
                ?> {
                        'item_id': '<?php echo $detail->product_id; ?>',
                        'item_name': '<?php echo addslashes($product['name'] ?? ''); ?>',
                        'item_category': '<?php echo addslashes($detail->category_name ?? ''); ?>',
                        'price': <?php echo (float) ($detail->price - $detail->discount); ?>,
                        'quantity': <?php echo (int) $detail->qty; ?>,
                        'content_type': 'product'
                    }
                    <?php echo $i < $total ? ',' : ''; ?>
                    <?php endforeach; ?>
                ]
            },
            'user_data': {
                'email_address': '<?php echo addslashes($customerEmail); ?>',
                'phone_number': '<?php echo $phoneClean; ?>',
                'first_name': '<?php echo addslashes($firstName); ?>',
                'last_name': '<?php echo addslashes($lastName); ?>',
                'country': '<?php echo addslashes($shippingData['country'] ?? 'BD'); ?>',
                'city': '<?php echo addslashes($shippingData['city'] ?? ''); ?>',
                'postal_code': '<?php echo $shippingData['zip'] ?? ''; ?>',
                'coupon': '{{ $order->coupon_code ?? '' }}'
            }
        });

        // Meta Pixel
        if (typeof fbq !== 'undefined') {
            fbq('track', 'Purchase', {
                currency: 'BDT',
                value: {{ (float) ($order->order_amount + $summary['total_shipping_cost']) }},
                content_type: 'product',
                content_name: '<?php echo addslashes($firstProduct['name'] ?? ''); ?>',
                content_ids: [
                    <?php $i = 0; foreach ($order->details as $detail): $i++; ?> '<?php echo $detail->product_id; ?>'
                    <?php echo $i < $total ? ',' : ''; ?>
                    <?php endforeach; ?>
                ],
                x_fb_cd_content_category: '<?php echo addslashes($firstCategoryName); ?>',
                x_fb_ck_fbp: fbp,
                x_fb_ck_fbc: fbc,
                order_id: '{{ $order->order_number }}',
                contents: [
                    <?php $i = 0; foreach ($order->details as $detail):
                    $product = json_decode($detail->product_details, true);
                    $i++;
                ?> {
                        id: '<?php echo $detail->product_id; ?>',
                        quantity: <?php echo (int) $detail->qty; ?>,
                        item_price: <?php echo (float) ($detail->price - $detail->discount); ?>
                    }
                    <?php echo $i < $total ? ',' : ''; ?>
                    <?php endforeach; ?>
                ],
                user_data: {
                    em: '<?php echo addslashes($customerEmail); ?>',
                    ph: '<?php echo $phoneClean; ?>',
                    fn: '<?php echo addslashes($firstName); ?>',
                    ln: '<?php echo addslashes($lastName); ?>',
                    country: '<?php echo addslashes($shippingData['country'] ?? 'Bangladesh'); ?>',
                    city: '<?php echo addslashes($shippingData['city'] ?? ''); ?>',
                    zp: '<?php echo $shippingData['zip'] ?? ''; ?>',
                    coupon: '{{ $order->coupon_code ?? '' }}'
                }
            });
        }

        // TikTok Pixel
        if (typeof ttq !== 'undefined') {
            ttq.track('PlaceAnOrder', {
                contents: [
                    <?php $i = 0; foreach ($order->details as $detail):
                    $product = json_decode($detail->product_details, true);
                    $i++;
                ?> {
                        content_id: '<?php echo $detail->product_id; ?>',
                        content_type: 'product',
                        content_name: '<?php echo addslashes($product['name'] ?? ''); ?>',
                        quantity: <?php echo (int) $detail->qty; ?>,
                        price: <?php echo (float) ($detail->price - $detail->discount); ?>
                    }
                    <?php echo $i < $total ? ',' : ''; ?>
                    <?php endforeach; ?>
                ],
                value: {{ (float) ($order->order_amount + $summary['total_shipping_cost']) }},
                currency: 'BDT'
            });
        }
    </script>
@endpush
