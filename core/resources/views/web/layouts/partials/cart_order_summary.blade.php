<h3 class="summary-title">Cart Total</h3>
<table class="table table-summary">
    <tbody>
        @php
            $sub_total = 0;
            $total_tax = 0;
            $total_shipping_cost = 0;
            $total_discount_on_product = 0;
            $promoProducts = [
                'HG1999D',
                'HG1999E',
                'HG1999G',
                'HG1999BL',
                'HG1999BK',
                'HG1999S',
                'HG1999BN',
                'HG1999R',
                'HG1999BY',
            ];
            $promoQty = 0;
            $promoItems = [];
        @endphp

        @if (session()->has('cart') && count(session()->get('cart')) > 0)
            @foreach (session('cart') as $key => $cartItem)
                @php
                    $itemTotal =
                        $cartItem['price'] * $cartItem['quantity'] - $cartItem['quantity'] * $cartItem['discount'];
                    $sub_total += $itemTotal;
                    $total_tax += $cartItem['tax'] * $cartItem['quantity'];
                    $total_shipping_cost += $cartItem['shipping_cost'];
                    $total_discount_on_product += $cartItem['discount'] * $cartItem['quantity'];

                    $sku = $cartItem['code'];
                    if (in_array($sku, $promoProducts)) {
                        $promoQty += $cartItem['quantity'];
                        $promoItems[] = $cartItem;
                    }
                @endphp
            @endforeach
        @else
            <tr>
                <td colspan="2"><span>Empty Cart</span></td>
            </tr>
        @endif

        @php
            // ── Promo discount (buy 3 get cheapest free) ──────────────
            $promoDiscount = 0;
            if ($promoQty >= 3) {
                $freeItems = floor($promoQty / 3);
                $prices = [];
                foreach ($promoItems as $item) {
                    for ($i = 0; $i < $item['quantity']; $i++) {
                        $prices[] = $item['price'];
                    }
                }
                sort($prices);
                for ($i = 0; $i < $freeItems; $i++) {
                    $promoDiscount += $prices[$i];
                }
            }

            // ── Shipping config (DB থেকে dynamic) ────────────────────
            $shippingConfig = \App\Models\ShippingConfig::getConfig();
            $isFreeShipping =
                $shippingConfig->shipping_type === 'free_shipping' &&
                $shippingConfig->free_shipping_type === 'all_products';

            // ── Shipping discount ─────────────────────────────────────
            $shippingDiscountAmt = session()->get('shipping_discount', 0);
            $originalShippingCost = $total_shipping_cost + $shippingDiscountAmt;

            // ── Coupon ────────────────────────────────────────────────
            $coupon_dis = session('coupon_discount', 0);

            // ── Grand total ───────────────────────────────────────────
            $grand_total = $sub_total + $total_tax + $total_shipping_cost - $coupon_dis - $promoDiscount;
        @endphp

        {{-- Subtotal --}}
        <tr class="summary-subtotal">
            <td>Subtotal:</td>
            <td>৳{{ number_format($sub_total, 2) }}</td>
        </tr>

        {{-- Shipping --}}
        @php
            $isWithoutDiscountFree = false;

            if (
                $shippingConfig->shipping_type === 'free_shipping' &&
                $shippingConfig->free_shipping_type === 'without_discount_product'
            ) {
                $freeMin = (float) ($shippingConfig->free_shipping_min_amount ?? 0);

                $nonDiscountedTotal = collect(session('cart', []))->sum(function ($item) {
                    return $item['discount'] > 0 ? 0 : $item['price'] * $item['quantity'];
                });

                $isWithoutDiscountFree = $freeMin > 0 && $nonDiscountedTotal >= $freeMin;
            }
        @endphp

        <tr class="summary-shipping">
            <td>Shipping:</td>
            <td>
                @if ($isFreeShipping || $isWithoutDiscountFree)
                    <span class="text-success fw-bold">Free</span>
                @elseif ($originalShippingCost == 0 && $shippingDiscountAmt == 0)
                    <span class="text-muted">— এরিয়া সিলেক্ট করুন</span>
                @else
                    @if ($shippingDiscountAmt > 0)
                        <del class="text-muted me-1">৳{{ number_format($originalShippingCost, 2) }}</del>
                    @endif
                    ৳{{ number_format($total_shipping_cost, 2) }}
                @endif
            </td>
        </tr>

        {{-- without_discount free badge --}}
        @if ($isWithoutDiscountFree)
            <tr>
                <td colspan="2">
                    <div class="p-2 rounded text-center"
                        style="background:#f0fff4; border:1px dashed #28a745; font-size:13px; color:#198754;">
                        🎉 ডেলিভারি চার্জ ফ্রি!
                    </div>
                </td>
            </tr>
        @endif

        {{-- Shipping Discount row --}}
        @if ($shippingDiscountAmt > 0)
            <tr class="summary-shipping-discount">
                <td style="color:#198754">🎉 Shipping Discount:</td>
                <td style="color:#198754">- ৳{{ number_format($shippingDiscountAmt, 2) }}</td>
            </tr>
        @endif

        {{-- Free Shipping (all_products) badge --}}
        @if ($isFreeShipping)
            <tr>
                <td colspan="2">
                    <div class="p-2 rounded text-center"
                        style="background:#f0fff4; border:1px dashed #28a745; font-size:13px; color:#198754;">
                        🎉 ডেলিভারি চার্জ ফ্রি!
                    </div>
                </td>
            </tr>
        @endif

        {{-- Promo discount --}}
        @if ($promoDiscount > 0)
            <tr>
                <td>🎁 Buy 3 Get 1 Free:</td>
                <td style="color:#198754">- ৳{{ number_format($promoDiscount, 2) }}</td>
            </tr>
        @endif

        {{-- Coupon --}}
        @if (session()->has('coupon_discount'))
            <tr class="summary-subtotal">
                <td>Coupon Discount</td>
                <td id="coupon-discount-amount" style="color:#198754">
                    - ৳{{ number_format($coupon_dis, 2) }}
                </td>
            </tr>
        @else
            <div class="mt-2">
            <form class="needs-validation" method="post" novalidate id="coupon-code-ajax">
                <div class="input-group">
                    <input type="text" class="form-control @error('code') is-invalid @enderror" name="code"
                        id="couponcod" placeholder="কুপন কোড (ঐচ্ছিক)" required>

                    <button class="btn btn-success r" type="button" onclick="couponCode()">
                        প্রয়োগ করুন
                    </button>

                    @error('code')
                        <div class="invalid-feedback">দয়া করে কুপন কোড প্রদান করুন !</div>
                    @enderror
                </div>
            </form>
        </div>
        @endif

        {{-- Grand Total --}}
        <tr class="summary-total">
            <td><strong>Total:</strong></td>
            <td><strong>৳{{ number_format($grand_total, 2) }}</strong></td>
        </tr>

    </tbody>
</table>
