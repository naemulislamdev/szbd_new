<?php

namespace App\CPU;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Support\Str;

class CartManager
{
    public static function cart_to_db()
    {
        $user = Helpers::get_customer();
        if (session()->has('offline_cart')) {
            $cart = session('offline_cart');
            $storage = [];
            foreach ($cart as $item) {
                $db_cart = Cart::where(['customer_id' => $user->id, 'seller_id' => $item['seller_id'], 'seller_is' => $item['seller_is']])->first();
                $storage[] = [
                    'customer_id' => $user->id,
                    'cart_group_id' => isset($db_cart) ? $db_cart['cart_group_id'] : str_replace('offline', $user->id, $item['cart_group_id']),
                    'product_id' => $item['product_id'],
                    'color' => $item['color'],
                    'choices' => $item['choices'],
                    'variations' => $item['variations'],
                    'variant' => $item['variant'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'tax' => $item['tax'],
                    'discount' => $item['discount'],
                    'slug' => $item['slug'],
                    'name' => $item['name'],
                    'thumbnail' => $item['thumbnail'],
                    'seller_id' => ($item['seller_is'] == 'admin') ? 1 : $item['seller_id'],
                    'seller_is' => $item['seller_is'],
                    'shop_info' => $item['shop_info'],
                    'shipping_cost' => $item['shipping_cost'],
                    'shipping_type' => $item['shipping_type'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
            Cart::insert($storage);
            session()->put('offline_cart', collect([]));
        }
    }
    public static function get_cart_group_ids($request = null)
    {
        $user = Helpers::get_customer_check($request);
        if ($user == 'offline') {
            if (session()->has('offline_cart') == false) {
                session()->put('offline_cart', collect([]));
            }
            $cart = session('offline_cart');
            $cart_ids = array_unique($cart->pluck('cart_group_id')->toArray());
        } else {
            $cart_ids = Cart::where(['customer_id' => $user->id])->groupBy('cart_group_id')->pluck('cart_group_id')->toArray();
        }
        return $cart_ids;
    }
    public static function cart_to_db_api($request, $from_api = false)
    {
        $user = Helpers::get_customer_check($request);
        if ($request->all()) {
            $cart = $request->all();
            foreach ($cart as $item) {
                var_dump($item);
            }

            // $product = Product::find($request->id);
            // $storage = [];

            $db_cart = Cart::where(['customer_id' => $user->id, 'seller_id' => $request['seller_id'], 'seller_is' => $request['seller_is']])->first();
            $data = ([
                'customer_id' => $user->id,
                'cart_group_id' => isset($db_cart) ? $db_cart['cart_group_id'] : str_replace('offline', $user->id, $request['cart_group_id']),
                'product_id' => $request['product_id'],
                'color' => $request['color'],
                'choices' => $request['choices'],
                'variations' => $request['variations'],
                'variant' => $request['variant'],
                'quantity' => $request['quantity'],
                'price' => $request['price'],
                'tax' => $request['tax'],
                'discount' => $request['discount'],
                'slug' => $request['slug'],
                'name' => $request['name'],
                'thumbnail' => $request['thumbnail'],
                'seller_id' => ($request['seller_is'] == 'admin') ? 1 : $request['seller_id'],
                'seller_is' => $request['seller_is'],
                'shop_info' => $request['shop_info'],
                'shipping_cost' => $request['shipping_cost'],
                'shipping_type' => $request['shipping_type'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $data = Cart::insert($data);
            return [
                'status' => 1,
                'data' => $data,
                'message' => 'successfully_added!'
            ];
        }
    }

    public static function add_to_cart($request)
    {
        $user = Helpers::get_customer_check($request);
        $product = Product::find($request->id);

        if (!$product) {
            return ['status' => 0, 'message' => 'product_not_found'];
        }

        $db_cart = Cart::where(['customer_id' => $user->id, 'product_id' => $product->id])->first();

        if (isset($db_cart)) {
            $db_cart->quantity = $db_cart->quantity + $request->quantity;
            $db_cart->save();
            return ['status' => 1, 'message' => 'successfully_updated!'];
        }

        $data = [
            'customer_id' => $user->id,
            'cart_group_id' => Str::random(5),
            'product_id' => $product->id,
            'color' => $request->color ?? '',
            'choices' => json_encode([]),
            'variations' => json_encode([]),
            'variant' => $request->variant ?? '',
            'quantity' => $request->quantity,
            'price' => $product->unit_price,
            'tax' => $product->tax ?? 0,
            'discount' => Helpers::get_product_discount($product, $product->unit_price),
            'slug' => $product->slug,
            'name' => $product->name,
            'thumbnail' => $product->thumbnail,
            'seller_id' => $product->user_id ?? 1,
            'seller_is' => $product->added_by ?? 'admin',
            'shop_info' => '',
            'shipping_cost' => 0,
            'shipping_type' => 'flat',
            'created_at' => now(),
            'updated_at' => now(),
        ];

        Cart::insert($data);
        return ['status' => 1, 'message' => 'successfully_added!'];
    }

    public static function update_cart_qty($request)
    {
        $user = Helpers::get_customer_check($request);
        $cart = Cart::where(['id' => $request->key, 'customer_id' => $user->id])->first();
        if ($cart) {
            $cart->quantity = $request->quantity;
            $cart->save();
        }
        return ['status' => 1, 'message' => 'successfully_updated!'];
    }

    public static function cart_grand_total($cart)
    {
        $total = 0;
        foreach ($cart as $item) {
            $qty = $item['quantity'] ?? $item['qty'] ?? 1;
            $total += ($item['price'] * $qty) + ($item['tax'] * $qty) - ($item['discount'] * $qty);
        }
        return $total;
    }

    public static function get_shipping_cost($shipping_method_id)
    {
        $method = \App\Models\ShippingMethod::find($shipping_method_id);
        return $method ? $method->cost : 0;
    }
}
