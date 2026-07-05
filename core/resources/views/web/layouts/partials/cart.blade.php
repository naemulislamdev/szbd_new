@php
    $shippingConfig = \App\Models\ShippingConfig::getConfig();

    // Global shipping discount config
    $freeShippingMinAmount =
        (float) (\App\Models\BusinessSetting::where('type', 'free_shipping_min_amount')->value('value') ?? 0);
    $shippingDiscount =
        (float) (\App\Models\BusinessSetting::where('type', 'free_shipping_discount')->value('value') ?? 0);

    $cartItems = session()->get('cart', collect([]));
    $currentSubTotal = 0;
    foreach ($cartItems as $item) {
        $currentSubTotal += ($item['price'] - $item['discount']) * $item['quantity'];
    }

    $showProgress =
        $freeShippingMinAmount > 0 && $shippingDiscount > 0 && $shippingConfig->shipping_type !== 'free_shipping';

    $remaining = max(0, $freeShippingMinAmount - $currentSubTotal);
    $progress = $freeShippingMinAmount > 0 ? min(100, ($currentSubTotal / $freeShippingMinAmount) * 100) : 0;
    $unlocked = $currentSubTotal >= $freeShippingMinAmount && $freeShippingMinAmount > 0;
@endphp

{{-- Progress Bar: শুধু তখনই দেখাবে যখন admin discount config set করা আছে --}}
@if ($showProgress)
    <div class="shipping-progress-box mb-3">
        @if ($unlocked)
            <p class="shipping-msg unlocked">
                🎉 আপনি <strong>৳{{ number_format($shippingDiscount) }} ডেলিভারি চার্জ ডিসকাউন্ট</strong> পেয়েছেন!
            </p>
        @else
            <p class="shipping-msg">
                আর মাত্র <strong>৳{{ number_format($remaining) }}</strong> কিনলে
                <strong>৳{{ number_format($shippingDiscount) }} ডেলিভারি চার্জ ডিসকাউন্ট</strong> পাবেন!
            </p>
        @endif
        <div class="progress-track">
            <div class="progress-fill" style="width: {{ $progress }}%"></div>
            @if (!$unlocked)
                <span class="progress-icon" style="left: calc({{ $progress }}% - 10px)">🛒</span>
            @else
                <span class="progress-icon unlocked-icon">✅</span>
            @endif
        </div>
    </div>
@elseif ($shippingConfig->shipping_type === 'free_shipping')
    {{-- Free shipping active থাকলে সরাসরি message --}}
    <div class="shipping-progress-box mb-3">
        <p class="shipping-msg unlocked">🎉 ডেলিভারি চার্জ ফ্রি চলছে!</p>
    </div>
@endif

{{-- Cart Items --}}
@if (session()->has('cart') && count(session()->get('cart')) > 0)
    @php
        $sub_total = 0;
        $total_tax = 0;
    @endphp

    <div class="cart-items-wrapper">
        @foreach (session('cart') as $keyId => $cartItem)
            <div class="header-cart-product d-flex mb-3">
                <div class="img">
                    @if ($cartItem['color_image'])
                        <img src="{{ $cartItem['color_image'] }}" alt="Product image">
                    @else
                        <img src="{{ asset('assets/storage/product/thumbnail') }}/{{ $cartItem['thumbnail'] }}"
                            alt="Product image">
                    @endif
                </div>
                <div class="header-cart-p-details">
                    <h5>{{ Str::limit($cartItem['name'], 30) }}</h5>
                    @if (!empty($cartItem['variations']))
                        @foreach ($cartItem['variations'] as $key => $variation)
                            <span>{{ $key }} : {{ $variation }}</span>
                        @endforeach
                    @endif
                    <p>৳{{ number_format(($cartItem['price'] - $cartItem['discount']) * $cartItem['quantity']) }}</p>
                    <a href="javascript:void(0)" onclick="removeFromCart({{ $keyId }})">
                        <i class="fa fa-trash"></i>
                    </a>
                </div>
            </div>
            @php
                $sub_total += ($cartItem['price'] - $cartItem['discount']) * $cartItem['quantity'];
                $total_tax += $cartItem['tax'] * $cartItem['quantity'];
            @endphp
        @endforeach
    </div>

    <div class="cart-header-bottom-box">
        <div class="cart-header-subtotal d-flex justify-content-between">
            <h4>Subtotal</h4>
            <h4>৳{{ number_format($sub_total) }}</h4>
        </div>
        <div class="button-section d-flex">
            <a href="{{ route('checkout') }}">Check Out</a>
        </div>
    </div>
@else
    <div class="empty-cart-box">
        <i class="fa fa-shopping-bag"></i>
        <h4>Your cart is empty.</h4>
        <a href="#" class="btn btn-dark">Return to shop</a>
    </div>
@endif
