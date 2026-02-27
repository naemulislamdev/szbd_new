<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;

class GlobalSearchController extends Controller
{
    public function search(Request $request)
    {
        $q = trim($request->q);

        if (!$q) {
            return response()->json([]);
        }

        // Bangladesh phone regex
        $phoneRegex = '/^(01[3-9]\d{8})$/';

        // ✅ Check phone or not
        if (preg_match($phoneRegex, $q)) {

            //Phone → Search Order table
            $orders = Order::with(['customer', 'shippingAddress', 'details'])
                ->whereHas('shippingAddress', function ($query) use ($q) {
                    $query->where('phone', $q);
                })
                ->latest()
                ->limit(5)
                ->get()
                ->map(function ($order) {
                    return [
                        'id'          => $order->id,
                        'order_number'          => $order->order_number,
                        'customer'    => $order->customer,
                        'shipping_address' => $order->shippingAddress,
                        'order_amount' => $order->order_amount,
                        'total_qty'   => $order->details->sum('qty'), // total items
                        'created_at'  => $order->created_at->format('d M Y') . ',' . $order->created_at->format('h:i A'), // only date
                        'order_status' => $order->order_status,
                        'total_orders' => $order->count(),
                    ];
                });

            return response()->json([
                'type' => 'order',
                'data' => $orders,
            ]);
        } else {

            // Not phone → Search Product table
            $products = Product::where('code', 'LIKE', "%$q%")
                ->orWhere('name', 'LIKE', "%$q%")
                ->limit(5)
                ->get();

            return response()->json([
                'type' => 'product',
                'data' => $products
            ]);
        }
    }
}
