<?php

namespace App\Http\Controllers\Front;

use App\CPU\Helpers;
use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\Order;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    public function apply(Request $request)
    {
        $couponLimit = Order::where('customer_id', auth('customer')->id())
            ->where('coupon_code', $request['code'])->count();

        $coupon = Coupon::where(['code' => $request['code']])
            ->where('limit', '>', $couponLimit)
            ->where('status', '=', 1)
            ->whereDate('start_date', '<=', date('y-m-d'))
            ->whereDate('expire_date', '>=', date('y-m-d'))->first();

        if ($coupon) {
            $total = 0;
            $carts = session()->has('cart') ? session()->get('cart') : 0;
            foreach ($carts as $cart) {
                $product_subtotal = $cart['price'] * $cart['quantity'];
                $total += $product_subtotal;
            }
            if ($total >= $coupon['min_purchase']) {
                if ($coupon['discount_type'] == 'percentage') {
                    $discount = (($total / 100) * $coupon['discount']);
                    // $discount = (($total / 100) * $coupon['discount']) > $coupon['max_discount'] ? $coupon['max_discount'] : (($total / 100) * $coupon['discount']);
                } else {
                    $discount = $coupon['discount'];
                }

                session()->put('coupon_code', $request['code']);
                session()->put('coupon_discount', $discount);

                return response()->json([
                    'status' => 1,
                    'discount' => $discount,
                    'total' => $total - $discount,
                    'messages' => ['0' => 'Coupon Applied Successfully!']
                ]);
            } else {
                return response()->json([
                    'status' => 0,
                    'messages' => ['0' => 'Minimum purchase amount is ' . $coupon['min_purchase']]
                ]);
            }
        }

        return response()->json([
            'status' => 0,
            'messages' => ['0' => 'Invalid Coupon']
        ]);
    }
}
