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
                        @if (!$customer && !session('otp_verified'))
                            <input type="hidden" name="session_id" id="session_id" value="{{ session()->getId() }}">

                            <div id="otpWrapper">
                                <input type="hidden" id="otp_expires_at" value="{{ session('otp_expires_at', '') }}">
                                <input type="hidden" id="session_phone" value="{{ session('otp_phone', '') }}">

                                <div class="row {{ session()->has('otp_phone') ? 'd-none' : '' }}" id="phoneRow">
                                    <div class="col-lg-8 mx-auto">
                                        <label>অর্ডার করার জন্য আপনার ফোন নম্বর দিয়ে ভেরিফাই করুন। <span
                                                class="text-danger">*</span></label>
                                        <div class="input-group mb-3">
                                            <input type="text" name="otp_phone"
                                                class="form-control otp-phone-save check-phone @error('otp_phone') is-invalid @enderror"
                                                id="otp_phone" placeholder="ফোন নম্বর লিখুন (01XXXXXXXXX)">
                                            <button type="button" id="send_otp" class="btn btn-info btn-sm">
                                                ওটিপি পাঠান
                                            </button>
                                        </div>
                                        <span class="phone-feedback small"></span>
                                    </div>
                                </div>

                                <div class="row {{ session()->has('otp') ? '' : 'd-none' }}" id="otpRow">
                                    <div class="col-md-6 mx-auto">
                                        <div class="otp-card">
                                            <h2 class="otp-title">OTP Verification</h2>
                                            <p class="otp-subtitle">আপনার মোবাইলে পাঠানো ৪ সংখ্যার কোডটি দিন</p>

                                            <div class="otp-timer" id="otpTimer"></div>
                                            <div class="otp-expired text-danger d-none" id="otpExpiredMsg">
                                                OTP এর সময় শেষ। আবার OTP পাঠান।
                                            </div>

                                            <div class="otp-inputs" id="otpBoxesWrap">
                                                <input type="text" inputmode="numeric" maxlength="1" class="otp-box"
                                                    autofocus>
                                                <input type="text" inputmode="numeric" maxlength="1"
                                                    class="otp-box">
                                                <input type="text" inputmode="numeric" maxlength="1"
                                                    class="otp-box">
                                                <input type="text" inputmode="numeric" maxlength="1"
                                                    class="otp-box">
                                            </div>

                                            <input type="hidden" id="otp" autocomplete="one-time-code">

                                            <div class="mt-3 d-flex gap-2 justify-content-center">
                                                <button type="button" class="btn btn-success"
                                                    id="verify_otp">ভেরিফাই
                                                    করুন</button>
                                                <button type="button" class="btn btn-secondary d-none"
                                                    id="resend_otp">আবার ওটিপি পাঠান</button>
                                            </div>

                                            <div id="otpMessage" class="mt-2 small"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <form style="position: relative" action="{{ route('product.checkout') }}" method="POST"
                            id="userInfoForm"
                            class="checkoutForm {{ !$customer && !session('otp_verified') ? 'd-none' : '' }} ">
                            @csrf

                            <input type="hidden" name="session_id" value="{{ session()->getId() }}">
                            <div class="row">
                                <div class="col-md-12">
                                    <label class="d-block mb-2">আপনার এরিয়া সিলেক্ট করুন</label>
                                    <div class="row">
                                        @foreach (\App\Models\ShippingMethod::where(['status' => 1])->get() as $shipping)
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
                                                id="phone" name="phone" placeholder="ফোন নম্বর লিখুন"
                                                value="{{ session('otp_phone') }}"
                                                {{ session()->has('otp_phone') ? 'readonly' : '' }}>
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
                                <button type="submit" class="btn btn-primary float-right w-100">অর্ডার করুন</button>
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
<script></script>

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

@if (session()->has('cart') && count(session()->get('cart')) > 0)
    <script>
        window.dataLayer = window.dataLayer || [];

        dataLayer.push({
            event: "begin_checkout",
            ecommerce: {
                currency: "BDT",
                value: {{ $gTotal }},
                items: [
                    @foreach (session('cart') as $item)
                        {
                            item_id: "{{ $item['id'] }}",
                            item_name: "{{ $item['name'] }}",
                            item_brand: "{{ $item['brand'] ?? '' }}",
                            item_category: "{{ $item['category'] ?? '' }}",
                            item_variant: "{{ $item['variant'] ?? '' }}",
                            price: {{ $item['price'] - $item['discount'] }},
                            quantity: {{ $item['quantity'] }}
                        }
                        @if (!$loop->last)
                            ,
                        @endif
                    @endforeach
                ]
            }
        });
    </script>
@endif
