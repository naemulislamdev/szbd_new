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
        $order_id = 100000 + Order::all()->count() + 1;
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

        if ($or['payment_method'] != 'cash_on_delivery') {
            $order = Order::find($order_id);
            $order_summary = OrderManager::order_summary($order);
            $order_amount = $order_summary['subtotal'] - $order_summary['total_discount_on_product'] - $order['discount'];
            $commission = Helpers::sales_commission($order);

            DB::table('order_transactions')->insert([
                'transaction_id' => OrderManager::gen_unique_id(),
                'customer_id' => $order['customer_id'],
                'order_id' => $order_id,
                'order_amount' => $order_amount,
                'seller_amount' => $order_amount - $commission,
                'admin_commission' => $commission,
                'received_by' => 'admin',
                'status' => 'hold',
                'delivery_charge' => $order['shipping_cost'],
                'tax' => $order_summary['total_tax'],
                'delivered_by' => 'admin',
                'payment_method' => $or['payment_method'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            if (AdminWallet::where('admin_id', 1)->first() == false) {
                DB::table('admin_wallets')->insert([
                    'admin_id' => 1,
                    'withdrawn' => 0,
                    'commission_earned' => 0,
                    'inhouse_earning' => 0,
                    'delivery_charge_earned' => 0,
                    'pending_amount' => 0,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
            DB::table('admin_wallets')->where('admin_id', $order['seller_id'])->increment('pending_amount', $order['order_amount']);
        }

        // if ($order->seller_is == 'admin') {
        //     $seller = Admin::find($order->seller_id);
        // } else {
        //     $seller = Seller::find($order->seller_id);
        // }

        try {
            $fcm_token = $user->cm_firebase_token;
            // $seller_fcm_token = $seller->cm_firebase_token;
            if ($data['payment_method'] != 'cash_on_delivery') {
                $value = Helpers::order_status_update_message('confirmed');
            } else {
                $value = Helpers::order_status_update_message('pending');
            }

            if ($value) {
                $data = [
                    'title' => translate('order'),
                    'description' => $value,
                    'order_id' => $order_id,
                    'image' => '',
                ];
                Helpers::send_push_notif_to_device($fcm_token, $data);
                // Helpers::send_push_notif_to_device($seller_fcm_token, $data);
            }

            $emailServices_smtp = Helpers::get_business_settings('mail_config');
            if ($emailServices_smtp['status'] == 0) {
                $emailServices_smtp = Helpers::get_business_settings('mail_config_sendgrid');
            }
            if ($emailServices_smtp['status'] == 1) {
                Mail::to($user->email)->send(new \App\Mail\OrderPlaced($order_id));
                // Mail::to($seller->email)->send(new \App\Mail\OrderReceivedNotifySeller($order_id));
            }
        } catch (\Exception $exception) {
            //echo $exception;
        }

        return $order_id;
    }
}
