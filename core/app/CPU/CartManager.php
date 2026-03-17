<?php

namespace App\CPU;

use App\Models\Cart;

class CartManager
{
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

}
