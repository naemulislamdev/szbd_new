<?php

namespace App\Http\Controllers\Front;


use App\Http\Controllers\Controller;
use App\CPU\Helpers;
use App\Models\Color;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function variant_price(Request $request)
    {
        $product = Product::find($request->id);
        $str = '';
        $quantity = 0;
        $price = 0;

        if ($request->has('color')) {
            $str = Color::where('code', $request['color'])->first()->name;
        }

        foreach (json_decode(Product::find($request->id)->choice_options) as $key => $choice) {
            if ($str != null) {
                $str .= '-' . str_replace(' ', '', $request[$choice->name]);
            } else {
                $str .= str_replace(' ', '', $request[$choice->name]);
            }
        }

        if ($str != null) {
            $count = count(json_decode($product->variation));
            for ($i = 0; $i < $count; $i++) {
                if (json_decode($product->variation)[$i]->type == $str) {
                    $tax = Helpers::tax_calculation(json_decode($product->variation)[$i]->price, $product['tax'], $product['tax_type']);
                    $discount = Helpers::get_product_discount($product, json_decode($product->variation)[$i]->price);
                    $price = json_decode($product->variation)[$i]->price - $discount + $tax;
                    $quantity = json_decode($product->variation)[$i]->qty;
                }
            }
        } else {
            $tax = Helpers::tax_calculation($product->unit_price, $product['tax'], $product['tax_type']);
            $discount = Helpers::get_product_discount($product, $product->unit_price);
            $price = $product->unit_price - $discount + $tax;
            $quantity = $product->current_stock;
        }

        return [
            'price' => \App\CPU\Helpers::currency_converter($price * $request->quantity),
            'discount' => \App\CPU\Helpers::currency_converter($discount),
            'tax' => \App\CPU\Helpers::currency_converter($tax),
            'quantity' => $quantity
        ];
    }

    public function addToCartOnSession(Request $request)
    {
        $product = Product::findOrFail($request->id);

        // ---------------------------
        // Quantity check
        // ---------------------------
        if ($product->current_stock < $request->quantity) {
            return response()->json(['data' => 0]);
        }

        $data       = [];
        $variations = [];
        $variantStr = '';
        $price      = 0;

        $data['id'] = $product->id;

        // ---------------------------
        // COLOR HANDLE
        // ---------------------------
        $color_image_path = null;

        if ($request->filled('color')) {
            $data['color'] = $request->color;

            $colorRow = collect($product->color_variant ?? [])
                ->firstWhere('code', $request->color);

            if ($colorRow) {
                $variations['Color'] = $colorRow['color'];
                $variantStr = $colorRow['color'];
                $color_image_path = $colorRow['image'];
            }
        }
    //dd($product->choice_options);
        // ---------------------------
        // SIZE / OTHER OPTIONS
        // ---------------------------
        foreach ($product->choice_options ?? [] as $choice) {
            if ($request->filled($choice['name'])) {
                $data[$choice['name']] = $request[$choice['name']];
                $variations[$choice['title']] = $request[$choice['name']];

                $variantStr .= $variantStr
                    ? '-' . str_replace(' ', '', $request[$choice['name']])
                    : str_replace(' ', '', $request[$choice['name']]);
            }
        }
        // foreach (json_decode(Product::find($request->id)->choice_options) as $key => $choice) {
        //     $data[$choice->name] = $request[$choice->name];
        //     $variations[$choice->title] = $request[$choice->name];
        //     if ($str != null) {
        //         $str .= '-' . str_replace(' ', '', $request[$choice->name]);
        //     } else {
        //         $str .= str_replace(' ', '', $request[$choice->name]);
        //     }
        // }

        $data['variant']    = $variantStr;
        $data['variations'] = $variations;

        // ---------------------------
        // DUPLICATE VARIANT CHECK
        // ---------------------------
        $cart = session()->get('cart', collect());

        foreach ($cart as $item) {
            if ($item['id'] == $product->id && $item['variant'] === $variantStr) {
                return response()->json(['data' => 1]); // already exists
            }
        }

        // ---------------------------
        // VARIATION PRICE & STOCK
        // ---------------------------
        if ($variantStr) {
            $count = count(json_decode($product->variation));
            for ($i = 0; $i < $count; $i++) {
                if (json_decode($product->variation)[$i]->type == $variantStr) {
                    $price = json_decode($product->variation)[$i]->price;
                    if (json_decode($product->variation)[$i]->qty < $request['quantity']) {
                        return response()->json([
                            'data' => 0
                        ]);
                    }
                }
            }
        } else {
            $price = $product->unit_price;
        }

        // ---------------------------
        // FINAL CART DATA
        // ---------------------------
        $tax = ($price * $product->tax) / 100;

        $data['quantity']           = $request->quantity;
        $data['price']              = $price;
        $data['tax']                = $tax;
        $data['discount']           = Helpers::get_product_discount($product, $price);
        $data['shipping_cost']      = 0;
        $data['shipping_method_id'] = 1;
        $data['slug']               = $product->slug;
        $data['name']               = $product->name;
        $data['thumbnail']          = $product->thumbnail;
        $data['color_image']        = $color_image_path;

        // ---------------------------
        // PUSH TO SESSION
        // ---------------------------
        $cart->push($data);
        session()->put('cart', $cart);

        session()->forget('coupon_code');
        session()->forget('coupon_discount');

        return response()->json([
            'data' => $data,
            'status' => 'success',
            'count' => session()->has('cart') ? count(session()->get('cart')) : 0,
            'product' => [
                'id' => $product->id,
                'name' => $product->name,
                'brand' => $product->brand->name ?? '',
                'category' => 'Shopping Zone Bd',
                'variant' => '',
                'price' => $product->unit_price,
                'quantity' => $request['quantity']
            ]
        ]);
    }

    public function updateNavCart()
    {
        return view('web.layouts.partials.cart');
    }

    //removes from Cart

    public function removeFromCart(Request $request)
    {
        if ($request->session()->has('cart')) {
            $cart = $request->session()->get('cart', collect([]));
            $cart->forget($request->key);
            $request->session()->put('cart', $cart);
        }

        session()->forget('coupon_code');
        session()->forget('coupon_discount');
        session()->forget('shipping_method_id');

        return view('web.layouts.partials.cart_details');
    }
    public function totalCartCount()
    {
        $data = session()->has('cart') ? count(session()->get('cart')) : 0;
        return $data;
    }

    //updated the quantity for a cart item
    public function updateQuantity(Request $request)
    {
        $status = 1;
        $qty = 0;
        $cart = $request->session()->get('cart', collect([]));
        $cart = $cart->map(function ($object, $key) use ($request, &$status, &$qty) {
            if ($key == $request->key) {
                $product = Product::find($object['id']);
                $count = count(json_decode($product->variation));
                if ($count) {
                    for ($i = 0; $i < $count; $i++) {
                        if (json_decode($product->variation)[$i]->type == $object['variant']) {
                            if (json_decode($product->variation)[$i]->qty < $request->quantity) {
                                $status = 0;
                                $qty = $object['quantity'];
                            } else {
                                $object['quantity'] = $request->quantity;
                            }
                        }
                    }
                } else if ($product['current_stock'] < $request->quantity) {
                    $status = 0;
                    $qty = $object['quantity'];
                } else {
                    $object['quantity'] = $request->quantity;
                }
            }
            return $object;
        });

        if ($status == 0) {
            return response()->json([
                'data' => $status,
                'qty' => $qty,
            ]);
        }

        $request->session()->put('cart', $cart);

        session()->forget('coupon_code');
        session()->forget('coupon_discount');

        return view('web.layouts.partials.cart_details');
    }
}
