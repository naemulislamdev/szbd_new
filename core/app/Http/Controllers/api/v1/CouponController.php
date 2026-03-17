<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    public function apply(Request $request)
    {
        try {
            $couponLimit = Order::where('customer_id', $request->user()->id)
                ->where('coupon_code', $request['code'])->count();

            $coupon = Coupon::where(['code' => $request['code']])
                ->where('limit', '>', $couponLimit)
                ->where('status', '=', 1)
                ->whereDate('start_date', '<=', date('y-m-d'))
                ->whereDate('expire_date', '>=', date('y-m-d'))->first();
            //$coupon = Coupon::where(['code' => $request['code']])->first();
        } catch (\Exception $e) {
            return response()->json(['errors' => $e], 403);
        }

        return response()->json($coupon, 200);
    }

    public function couponList(Request $request)
    {

        try {
            $coupon = Coupon::where('status', '=', 1)
                ->whereDate('start_date', '<=', date('y-m-d'))
                ->whereDate('expire_date', '>=', date('y-m-d'))->get();
            //$coupon = Coupon::where(['code' => $request['code']])->first();
        } catch (\Exception $e) {
            return response()->json(['errors' => $e], 403);
        }

        return response()->json($coupon, 200);
    }
}
