@php
    $customer = auth('customer')->user();
    if ($customer) {
        $shippingAddresses = \App\Models\ShippingAddress::where('customer_id', $customer->id)->get();
    }
@endphp

{{-- @dd(session('otp')) --}}

<div class="row">
    <div class="col-md-10 mx-auto my-3">
        <div class="row">
            <div class="col-lg-8">
                <div style="overflow-y: auto; width:100%;">
                    <table class="table table-cart table-mobile">
                        <thead>
                            <tr>
                                <th>ছবি</th>
                                <th>পণ্যের নাম</th>
                                <th>মূল্য</th>
                                <th>পরিমাণ</th>
                                <th>মোট</th>
                                <th>অ্যাকশন</th>
                            </tr>
                        </thead>

                        <tbody>
                            @php
                                $gTotal = 0;
                            @endphp
                            @if (session()->has('cart') && count(session()->get('cart')) > 0)
                                @foreach (session()->get('cart') as $key => $cartItem)
                                    @php
                                        $gTotal += ($cartItem['price'] - $cartItem['discount']) * $cartItem['quantity'];
                                    @endphp
                                    <tr>
                                        <td class="product-col">
                                            <div class="checkout-product">
                                                <a href="{{ route('product', $cartItem['slug']) }}">
                                                    @if ($cartItem['color_image'])
                                                        <img src="{{ $cartItem['color_image'] }}" alt="Product image">
                                                    @else
                                                        <img src="{{ asset('assets/storage/product/thumbnail') }}/{{ $cartItem['thumbnail'] }}"
                                                            alt="Product image">
                                                    @endif
                                                </a>
                                            </div>
                                        </td>
                                        <td style="text-align: left;">
                                            <a
                                                href="{{ route('product', $cartItem['slug']) }}">{{ Str::limit($cartItem['name'], 30) }}</a><br>
                                            @if (!empty($cartItem['variations']))
                                                @foreach ($cartItem['variations'] as $vKey => $variation)
                                                    <span style="font-size: 14px;">{{ $vKey }} :
                                                        {{ $variation }}</span>
                                                @endforeach
                                            @endif
                                        </td>
                                        <td class="price-col">
                                            {{ $cartItem['price'] - $cartItem['discount'] }}
                                        </td>
                                        <td class="quantity-col">
                                            <div class="product-quantity d-flex align-items-center">

                                                <button type="button" class="qty-btn btn btn-sm btn-danger"
                                                    onclick="changeQty('{{ $key }}', -1)">-</button>

                                                <input type="number" id="cartQuantity{{ $key }}"
                                                    value="{{ $cartItem['quantity'] }}" min="1" readonly
                                                    class="qty-input">

                                                <button type="button" class="qty-btn btn btn-sm btn-success"
                                                    onclick="changeQty('{{ $key }}', 1)">+</button>

                                            </div>
                                        </td>

                                        <td class="total-col">
                                            {{ ($cartItem['price'] - $cartItem['discount']) * $cartItem['quantity'] }}
                                        </td>
                                        <td class="remove-col"><a href="javascript:voide(0);"
                                                onclick="removeFromCart({{ $key }})" class="btn-remove"><i
                                                    class="fa fa-trash-o"></i></a></td>
                                    </tr>
                                @endforeach
                            @else
                                <div class="empty-cart-box">
                                    <i class="fa fa-shopping-bag"></i>
                                    <h4>Your cart is empty.</h4>
                                    <a href="{{ route('home') }}" class="btn btn-dark">Return to shop</a>
                                </div>
                            @endif
                        </tbody>
                    </table>
                </div>
                <div class="card mb-3 ">
                    <div class="card-header">
                        <h2 class="address-title mb-0">আপনার ঠিকানা</h2>
                    </div>
                    <div class="card-body">
                        <form style="position: relative" action="{{ route('product.checkout') }}" method="POST"
                            id="userInfoForm">
                            @csrf

                            <input type="hidden" name="session_id" value="{{ session()->getId() }}">
                            {{-- <div class="row">
                                @php
                                    $cartAddedTotalPrice = 0;
                                    if (session()->has('cart') && count(session()->get('cart')) > 0) {
                                        foreach (session('cart') as $key => $cartItem) {
                                            $itemTotal =
                                                $cartItem['price'] * $cartItem['quantity'] -
                                                $cartItem['quantity'] * $cartItem['discount'];
                                            $cartAddedTotalPrice += $itemTotal;
                                        }
                                    }
                                @endphp
                                <div class="col-md-12">
                                    <label class="d-block mb-2">আপনার এরিয়া সিলেক্ট করুন</label>

                                    <div class="row">
                                        @foreach (\App\Models\ShippingMethod::where(['status' => 1])->get() as $shipping)
                                            <div class="col-md-4">
                                                <label class="shipping-box">
                                                    <input type="radio" name="shipping_area"
                                                        value="{{ $shipping['id'] }}"
                                                        {{ $shipping['cost'] == 0 ? 'checked' : '' }}
                                                        onchange="set_shipping_id(this.value)"
                                                        {{ session()->has('shipping_method_id') && session('shipping_method_id') == $shipping['id'] ? 'checked' : '' }}>
                                                    <span class="shipping-title">
                                                        {{ $shipping['title'] }}
                                                    </span>
                                                    <span class="shipping-cost">
                                                        {{ $shipping['cost'] }}
                                                    </span>
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div> --}}

                            <div class="row">
                                {{-- free delivery calculation php block --}}
                                @php
                                    $cartAddedTotalPrice = 0;
                                    if (session()->has('cart') && count(session()->get('cart')) > 0) {
                                        foreach (session('cart') as $key => $cartItem) {
                                            if ($cartItem['discount'] == 0) {
                                                $itemTotal = $cartItem['price'] * $cartItem['quantity'];
                                                $cartAddedTotalPrice += $itemTotal;
                                            }
                                        }
                                    }

                                    $shippingMethods = \App\Models\ShippingMethod::where(['status' => 1])->get();

                                    if ($cartAddedTotalPrice > 999) {
                                        $shippingMethods = $shippingMethods->where('cost', 0);
                                    } else {
                                        $shippingMethods = $shippingMethods->where('cost', '>', 0);
                                    }
                                @endphp

                                <div class="col-md-12">
                                    <label class="d-block mb-2">আপনার এরিয়া সিলেক্ট করুন</label>

                                    <div class="row">
                                        {{-- @foreach (\App\Models\ShippingMethod::where(['status' => 1])->get() as $shipping)
                                            <div class="col-md-6">
                                                <label class="shipping-box">
                                                    <input type="radio" name="shipping_area"
                                                        value="{{ $shipping['id'] }}"
                                                        onchange="set_shipping_id(this.value)"
                                                        {{ session()->has('shipping_method_id') && session('shipping_method_id') == $shipping['id'] ? 'checked' : '' }}>
                                                    <span class="shipping-title">
                                                        {{ $shipping['title'] }}
                                                    </span>
                                                    <span class="shipping-cost">
                                                        {{ $shipping['cost'] }}
                                                    </span>
                                                </label>
                                            </div>
                                        @endforeach --}}
                                        @foreach ($shippingMethods as $shipping)
                                            <div class="col-md-6">
                                                <label class="shipping-box">
                                                    <input type="radio" name="shipping_area"
                                                        value="{{ $shipping['id'] }}"
                                                        onchange="set_shipping_id(this.value)">
                                                    <span class="shipping-title">
                                                        {{ $shipping['title'] }}
                                                    </span>
                                                    <span class="shipping-cost">
                                                        {{ $shipping['cost'] == 0 ? '' : '৳' . $shipping['cost'] }}
                                                    </span>
                                                </label>
                                            </div>
                                        @endforeach

                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        {{-- <label>পেমেন্ট পদ্ধতি নির্বাচন করুন</label> --}}
                                        <select class="form-control" name="payment_method" hidden>
                                            <option value="cash_on_delivery" selected>ক্যাশ অন ডেলিভারি</option>
                                            {{-- <option value="online_payment">অনলাইন পেমেন্ট</option> --}}
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-4 ">
                                <div class="col-md-12">
                                    @if ($customer && $shippingAddresses->count() > 0)
                                        <div class="checkout-card">
                                            @foreach ($shippingAddresses as $key => $address)
                                                <div class=" address-box {{ $key == 0 ? 'active' : '' }} mr-2">
                                                    <input type="radio" id="address_{{ $address->id }}"
                                                        name="address_type" value="{{ $address->id }}"
                                                        {{ $key == 0 ? 'checked' : '' }}
                                                        onclick="selectAddress(false,this)">
                                                    <label for="address_{{ $address->id }}">
                                                        <strong>Name: {{ $address->contact_person_name }}</strong>
                                                        <br>
                                                        📞 Phone: {{ $address->phone }}<br>
                                                        🏠 Address: {{ $address->address }}
                                                    </label>
                                                    <button type="button"
                                                        class="btn btn-sm btn-outline-primary edit-btn"
                                                        onclick="openEditModal({{ $address }})">✏️</button>
                                                </div>
                                            @endforeach
                                        </div>
                                        <label class="address-box">
                                            <input type="radio" name="address_type" value="new"
                                                onclick="selectAddress(true,this)">
                                            ➕ নতুন ঠিকানা যোগ করুন
                                        </label>
                                    @endif
                                </div>
                            </div>
                            <div id="newAddressForm" style="{{ $customer ? 'display:none;' : '' }}" class="mb-4">
                                @if (!$customer)
                                    <input type="hidden" name="address_type" value="new">
                                @endif
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <div class="form-group">
                                            <label>নাম <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control auto-save"
                                                placeholder="আপনার নাম লিখুন" name="name"
                                                value="{{ old('name') }}">
                                            @error('name')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <div class="form-group">
                                            <label for="phone">ফোন নম্বর <span class="text-danger">*</span></label>
                                            <input type="number" class="form-control auto-save check-phone"
                                                value="{{ old('phone') }}" id="phone" name="phone"
                                                placeholder="ফোন নম্বর লিখুন">
                                            <span id="phoneFeedback" class="small text-danger"></span>
                                            @error('phone')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12 mb-3">
                                        <div class="form-group">
                                            <label>আপনার ঠিকানা <span class="text-danger">*</span></label>
                                            <textarea class="form-control auto-save" placeholder="আপনার শিপিং ঠিকানা লিখুন" name="address">{{ old('address') }}</textarea>
                                            @error('address')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="sticky-btn ">
                                <button id="orderBtn" type="submit"
                                    class="btn btn-primary float-right w-100">অর্ডার করুন</button>
                            </div>
                        </form>
                    </div>
                </div>

            </div><!-- End .col-lg-9 -->
            <aside class="col-lg-4">
                <div class="summary summary-cart">
                    @include('web.layouts.partials.cart_order_summary')
                </div>
            </aside><!-- End .col-lg-3 -->
        </div><!-- End .row -->
    </div>
</div>

<script>
    document
        .getElementById('userInfoForm')
        .addEventListener('submit', function() {

            let btn = document.getElementById('orderBtn');

            btn.disabled = true;

            btn.innerHTML = `
                অর্ডার করা হচ্ছে...
                <span class="spinner-border spinner-border-sm"></span>
            `;
        });
</script>

<script>
    function set_shipping_id(id) {
        @foreach (session()->get('cart') as $key => $item)
            let key = '{{ $key }}';
            @break
        @endforeach
        $.get({
            url: '{{ url('/') }}/set-shipping-method',
            dataType: 'json',
            data: {
                id: id,
                key: key
            },
            beforeSend: function() {
                $('#loading').show();
            },
            success: function(data) {
                if (data.status == 1) {
                    toastr.success('Shipping area is selected', {
                        CloseButton: true,
                        ProgressBar: true
                    });
                    $('.summary-cart').empty().html(data.html);
                    // setInterval(function () {
                    //     location.reload();
                    // }, 2000);
                } else {
                    toastr.error('Choose proper shipping area.', {
                        CloseButton: true,
                        ProgressBar: true
                    });
                }
            },
            complete: function() {
                $('#loading').hide();
            },
        });
    }
</script>

@php
    $checkoutTotal = 0;
    $cartItems = session('cart', []);
    foreach ($cartItems as $cartItem) {
        $checkoutTotal += ($cartItem['price'] - $cartItem['discount']) * $cartItem['quantity'];
    }
    $cartArray = is_array($cartItems) ? $cartItems : $cartItems->toArray();
    $firstItem = reset($cartArray);
@endphp

@if (session()->has('cart') && count(session()->get('cart')) > 0)
    <script>
        var fbp = (document.cookie.match('(^|;)\\s*_fbp\\s*=\\s*([^;]+)') || [])[2] || '';
        var fbc = (document.cookie.match('(^|;)\\s*_fbc\\s*=\\s*([^;]+)') || [])[2] || '';

        // GA4
        window.dataLayer = window.dataLayer || [];
        window.dataLayer.push({
            'event': 'begin_checkout',
            '_fbp': fbp,
            '_fbc': fbc,
            'ecommerce': {
                'currency': 'BDT',
                'value': {{ $checkoutTotal }},
                'items': [
                    @foreach ($cartArray as $item)
                        {
                            'item_id': '{{ $item['id'] }}',
                            'item_name': '{{ addslashes($item['name']) }}',
                            'item_category': '{{ $item['category'] ?? '' }}'
                            'price': {{ $item['price'] - $item['discount'] }},
                            'quantity': {{ $item['quantity'] }},
                            'content_type': 'product'
                        }
                        @if (!$loop->last)
                            ,
                        @endif
                    @endforeach
                ]
            }
        });

        // Meta Pixel
        fbq('track', 'InitiateCheckout', {
            currency: 'BDT',
            value: {{ $checkoutTotal }},
            content_type: 'product',
            content_name: '{{ addslashes($firstItem['name'] ?? '') }}',
            content_ids: [
                @foreach ($cartArray as $item)
                    '{{ $item['id'] }}'
                    @if (!$loop->last)
                        ,
                    @endif
                @endforeach
            ],
            x_fb_cd_content_category: '{{ $firstItem['category'] ?? '' }}'
            x_fb_ck_fbp: fbp,
            x_fb_ck_fbc: fbc,
            contents: [
                @foreach ($cartArray as $item)
                    {
                        id: '{{ $item['id'] }}',
                        quantity: {{ $item['quantity'] }},
                        item_price: {{ $item['price'] - $item['discount'] }}
                    }
                    @if (!$loop->last)
                        ,
                    @endif
                @endforeach
            ],
            num_items: {{ collect($cartArray)->sum('quantity') }}
        });

        // TikTok Pixel
        ttq.track('InitiateCheckout', {
            contents: [
                @foreach ($cartArray as $item)
                    {
                        content_id: '{{ $item['id'] }}',
                        content_type: 'product',
                        content_name: '{{ addslashes($item['name']) }}',
                        quantity: {{ $item['quantity'] }},
                        price: {{ $item['price'] - $item['discount'] }}
                    }
                    @if (!$loop->last)
                        ,
                    @endif
                @endforeach
            ],
            value: {{ $checkoutTotal }},
            currency: 'BDT'
        });
    </script>
    @php
        $firstCartItem = collect(session('cart'))->first();
    @endphp
    <script>
        console.log(<?php echo json_encode($firstCartItem); ?>);
    </script>
@endif
