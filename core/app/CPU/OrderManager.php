<?php

namespace App\CPU;

use App\Models\AdminWallet;
use App\Models\User;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\OrderTransaction;
use App\Models\Product;
use App\Models\SellerWallet;
use App\Models\ShippingAddress;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;


class OrderManager
{
    public static function track_order($request)
    {
        $user_id = User::where('phone', $request['phone_number'])->first()->id;
        $data['order'] = Order::where('id', $request['order_id'])->where(function ($query) use ($user_id) {
            $query->where('customer_id', $user_id);
        })->first();

        if (isset($data['order'])) {
            $data['details'] = OrderDetail::where(['order_id' => $request['order_id']])->get();
            $data['details']->map(function ($query) {
                $query['variation'] = json_decode($query['variation'], true);
                $query['product_details'] = Helpers::product_data_formatting(json_decode($query['product_details'], true));
                return $query;
            });
        }


        if (!isset($data['order'])) {
            return response()->json(['errors' => "Order Not Found"], 404);
        }
        return $data;
    }

    public static function gen_unique_id()
    {
        return rand(1000, 9999) . '-' . Str::random(5) . '-' . time();
    }

    public static function order_summary($order)
    {
        $sub_total = 0;
        $total_tax = 0;
        $total_discount_on_product = 0;
        foreach ($order->details as $key => $detail) {
            $sub_total += $detail->price * $detail->qty;
            $total_tax += $detail->tax;
            $total_discount_on_product += $detail->discount;
        }
        $total_shipping_cost = $order['shipping_cost'];
        return [
            'subtotal' => $sub_total,
            'total_tax' => $total_tax,
            'total_discount_on_product' => $total_discount_on_product,
            'total_shipping_cost' => $total_shipping_cost,
        ];
    }

    public static function stock_update_on_order_status_change($order, $status)
    {
        if ($status == 'returned' || $status == 'failed' || $status == 'canceled') {
            foreach ($order->details as $detail) {
                if ($detail['is_stock_decreased'] == 1) {
                    $product = Product::find($detail['product_id']);
                    $type = $detail['variant'];
                    $var_store = [];
                    foreach (json_decode($product['variation'], true) as $var) {
                        if ($type == $var['type']) {
                            $var['qty'] += $detail['qty'];
                        }
                        array_push($var_store, $var);
                    }
                    Product::where(['id' => $product['id']])->update([
                        'variation' => json_encode($var_store),
                        'current_stock' => $product['current_stock'] + $detail['qty'],
                    ]);
                    OrderDetail::where(['id' => $detail['id']])->update([
                        'is_stock_decreased' => 0
                    ]);
                }
            }
        } else {
            foreach ($order->details as $detail) {
                if ($detail['is_stock_decreased'] == 0) {
                    $product = Product::find($detail['product_id']);

                    $type = $detail['variant'];
                    $var_store = [];
                    foreach (json_decode($product['variation'], true) as $var) {
                        if ($type == $var['type']) {
                            $var['qty'] -= $detail['qty'];
                        }
                        array_push($var_store, $var);
                    }
                    Product::where(['id' => $product['id']])->update([
                        'variation' => json_encode($var_store),
                        'current_stock' => $product['current_stock'] - $detail['qty'],
                    ]);
                    OrderDetail::where(['id' => $detail['id']])->update([
                        'is_stock_decreased' => 1
                    ]);
                }
            }
        }
    }

    public static function generate_order($data)
    {
        $order_id = 100000 + (Order::max('id') ?? 0) + 1;
        if (Order::where('order_number',$order_id)->first()) {
            $order_id = Order::orderBy('id', 'DESC')->first()->id + 1;
        }

        $req = array_key_exists('request', $data) ? $data['request'] : null;
        // return response()->json(['order'=>$req['cart'],translate('order_placed_successfully')], 200);
        if ($req != null) {
            if (session()->has('coupon_code') == false) {
                $coupon_code = $req->has('coupon_code') ? $req['coupon_code'] : null;
                $discount = $req->has('coupon_code') ? Helpers::coupon_discount($req) : 0;
            }
        }
        $user = Helpers::get_customer_check($req);
        $shippingAddress = new ShippingAddress();
        $shippingAddress->customer_id = auth('customer')->id();
        $shippingAddress->contact_person_name = $req->name;
        $shippingAddress->address = $req->address;
        $shippingAddress->city = 'city';
        $shippingAddress->phone = $req->phone;
        $shippingAddress->created_at = now();
        $shippingAddress->save();

        $or = [
            'order_number' => $order_id,
            'verification_code' => rand(100000, 999999),
            'customer_id' => $user->id,
            'customer_type' => 'customer',
            'payment_status' => $data['payment_status'],
            'order_status' => $data['order_status'],
            'order_type' => $data['order_type'],
            'payment_method' => $req->payment_method,
            'transaction_ref' => $data['transaction_ref'],
            'discount_amount' => $discount,
            'discount_type' => $discount == 0 ? null : 'coupon_discount',
            'coupon_code' => $coupon_code,
            'order_amount' => CartManager::cart_grand_total($req['cart']) - $discount,
            'shipping_address' => $shippingAddress->id,
            'shipping_address_data' => ShippingAddress::find($shippingAddress->id),
            'billing_address' => $shippingAddress->id,
            'billing_address_data' => ShippingAddress::find($shippingAddress->id),
            'shipping_cost' => CartManager::get_shipping_cost($req->shipping_method_id),
            'shipping_method_id' => $req->shipping_method_id,
            'created_at' => now(),
            'updated_at' => now(),
            'order_note' => $req->order_note
        ];

        DB::table('orders')->insertGetId($or);

        foreach ($req['cart'] as $c) {
            $product = Product::where(['id' => $c['product_id']])->first();
            $or_d = [
                'order_id' => $order_id,
                'product_id' => $c['product_id'],
                'seller_id' => $product->added_by == 'seller' ? $product->user_id : '0',
                'product_details' => $product,
                'qty' => $c['quantity'],
                'price' => $c['price'],
                'tax' => $c['tax'] * $c['quantity'],
                'discount' => $c['discount'] * $c['quantity'],
                'discount_type' => 'discount_on_product',
                'variant' => $c['variant'],
                'variation' => json_encode($c['variations']),
                'delivery_status' => 'pending',
                'shipping_method_id' => null,
                'payment_status' => 'unpaid',
                'created_at' => now(),
                'updated_at' => now()
            ];

            if ($c['variant'] != null) {
                $type = $c['variant'];
                $var_store = [];
                foreach (json_decode($product['variation'], true) as $var) {
                    if ($type == $var['type']) {
                        $var['qty'] -= $c['quantity'];
                    }
                    array_push($var_store, $var);
                }
                Product::where(['id' => $product['id']])->update([
                    'variation' => json_encode($var_store),
                ]);
            }

            Product::where(['id' => $product['id']])->update([
                'current_stock' => $product['current_stock'] - $c['quantity']
            ]);

            DB::table('order_details')->insert($or_d);
        }

        return $order_id;
    }
}
