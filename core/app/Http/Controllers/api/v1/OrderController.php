<?php

namespace App\Http\Controllers\api\v1;

use App\CPU\Helpers;
use App\CPU\OrderManager;
use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    public function track_order(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'order_id' => 'required',
            'phone_number' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        return response()->json(OrderManager::track_order($request), 200);
    }
    public function order_cancel(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'order_id' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }
        $order = Order::where(['order_number' => $request->order_id])->first();

        if ($order['payment_method'] == 'cash_on_delivery' && $order['order_status'] == 'pending') {
            OrderManager::stock_update_on_order_status_change($order, 'canceled');
            Order::where(['order_number' => $request->order_id])->update([
                'order_status' => 'canceled'
            ]);

            return response()->json('order_canceled_successfully', 200);
        }

        return response()->json('status_not_changable_now', 302);
    }
    public function place_order(Request $request)
    {

        $order_ids = [];
            $data = [
                'order_status' => 'pending',
                'order_type' => 'apps',
                'payment_status' => 'unpaid',
                'transaction_ref' => '',
                'request' => $request,
            ];

            $order_id = OrderManager::generate_order($data);

            array_push($order_ids, $order_id);

        // Additive + non-fatal: mint an auth token for the order's customer so
        // the app can auto-login after a guest order. get_customer_check() is
        // idempotent (find by phone/email, else create), so re-fetching is safe.
        // A failure here must never block order placement; older app clients and
        // the web simply ignore this extra field.
        $token = null;
        try {
            $customer = Helpers::get_customer_check($request);
            if ($customer) {
                $token = $customer->createToken('LaravelAuthApp')->accessToken;
            }
        } catch (\Throwable $e) {
            $token = null;
        }

        return response()->json([
            'order_id' => $order_id,
            'message' => 'order_placed_successfully',
            'token' => $token,
        ], 200);
    }


    public function getOrderDetails($order_id)
    {
        $order = Order::with('details')->find($order_id);

        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        return response()->json([
            'order' => $order,
            'order_details' => $order->orderDetails
        ]);
    }
}
