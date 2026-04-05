<h3 class="summary-title">Cart Total</h3><!-- End .summary-title -->
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

                    // ✅ SKU detect (important)
                    $sku = $cartItem['code'];

                    if (in_array($sku, $promoProducts)) {
                        $promoQty += $cartItem['quantity'];
                        $promoItems[] = $cartItem;
                    }
                @endphp
            @endforeach
        @else
            <span>Empty Cart</span>
        @endif

        @php
            $promoDiscount = 0;

            if ($promoQty >= 3) {
                $freeItems = floor($promoQty / 3);

                // ✅ cheapest item free (important business logic)
                $prices = [];

                foreach ($promoItems as $item) {
                    for ($i = 0; $i < $item['quantity']; $i++) {
                        $prices[] = $item['price'];
                    }
                }

                sort($prices); // lowest first

                for ($i = 0; $i < $freeItems; $i++) {
                    $promoDiscount += $prices[$i];
                }
            }
        @endphp
        <tr class="summary-subtotal">
            <td>Subtotal:</td>
            <td>{{ $sub_total }}</td>
        </tr>
        <tr class="summary-shipping">
            <td>Shipping:</td>
            <td>{{ $total_shipping_cost }}</td>
        </tr>
        <tr class="summary-subtotal">
            @if (session()->has('coupon_discount'))
                <td class="d-flex">Coupon Discount</td>
                <td id="coupon-discount-amount">
                    -
                    {{ session()->has('coupon_discount') ? session('coupon_discount') : 0 }}
                </td>

                @php($coupon_dis = session('coupon_discount'))
        </tr>
    @else
        <div class="mt-2">
            <form class="needs-validation" method="post" novalidate id="coupon-code-ajax">
                <div class="form-group">
                    <input class="form-control input_code @error('code') is-invalid @enderror" type="text"
                        name="code" id="couponcod" placeholder="Coupon code (Optional)" required>
                    @error('code')
                        <div class="invalid-feedback">Please provide coupon code.</div>
                    @enderror
                </div>
                <button class="btn btn-primary btn-block" type="button" onclick="couponCode()">Apply Code
                </button>
            </form>
        </div>

        @php($coupon_dis = 0)
        @endif

        <tr class="summary-total">
            <td>Total:</td>
            <td>{{ $sub_total + $total_tax + $total_shipping_cost - $coupon_dis - $promoDiscount }}
            </td>
        </tr><!-- End .summary-total -->
    </tbody>
</table><!-- End .table table-summary -->
