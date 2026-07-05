<?php

namespace App\Http\Controllers\Front;


use App\Http\Controllers\Controller;
use App\CPU\Helpers;
use App\Models\Color;
use App\Models\EidOffer;
use App\Models\Product;
use App\Models\ShippingMethod;
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
        // COLOR HANDLE (ONLY UI, NOT IN VARIANT)
        // ---------------------------
        $color_image_path = null;

        if ($request->filled('color')) {

            $data['color'] = $request->color;

            $colorVariants = is_array($product->color_variant)
                ? $product->color_variant
                : json_decode($product->color_variant ?? '[]', true);

            $colorRow = collect($colorVariants)->firstWhere('code', $request->color);

            if ($colorRow) {
                $variations['Color'] = $colorRow['color'];
                $color_image_path = $colorRow['image'];
            }
        }

        // ---------------------------
        // SIZE / OTHER OPTIONS
        // ---------------------------
        $choiceOptions = is_array($product->choice_options)
            ? $product->choice_options
            : json_decode($product->choice_options ?? '[]', true);

        foreach ($choiceOptions as $choice) {

            if ($request->filled($choice['name'])) {

                $value = $request[$choice['name']];

                $data[$choice['name']] = $value;
                $variations[$choice['title']] = $value;

                // ✅ ONLY SIZE (no color)
                $variantStr .= $variantStr
                    ? '-' . str_replace(' ', '', $value)
                    : str_replace(' ', '', $value);
            }
        }

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
        $variationsData = json_decode($product->variation ?? '[]', true);

        if ($variantStr) {

            foreach ($variationsData as $var) {

                if (($var['type'] ?? '') == $variantStr) {

                    $price = $var['price'] ?? 0;

                    if (($var['qty'] ?? 0) < $request->quantity) {
                        return response()->json(['data' => 0]);
                    }
                }
            }
        } else {
            $price = $product->unit_price;
        }

        // ---------------------------
        // SAFETY (price fallback)
        // ---------------------------
        if ($price <= 0) {
            $price = $product->unit_price;
        }
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
        $data['code']               = $product->code;
        $data['category']               = $product->category->name;

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
    public function summaryHtml()
    {
        $methodId = session('shipping_method_id');

        if ($methodId) {
            $shippingMethod = ShippingMethod::find($methodId);

            if ($shippingMethod) {
                $config   = \App\Models\ShippingConfig::getConfig();
                $cart     = session()->get('cart', collect([]));
                $subTotal = $cart->sum(fn($i) => ($i['price'] - $i['discount']) * $i['quantity']);

                // ── Base cost calculate ──
                $baseCost = $shippingMethod->cost;

                if ($config->shipping_type === 'free_shipping') {
                    if ($config->free_shipping_type === 'all_products') {
                        $baseCost = 0;
                    } elseif ($config->free_shipping_type === 'without_discount_product') {
                        $freeMin = (float) ($config->free_shipping_min_amount ?? 0);
                        $nonDiscountedTotal = $cart->sum(
                            fn($i) =>
                            $i['discount'] > 0 ? 0 : ($i['price'] * $i['quantity'])
                        );
                        $baseCost = ($freeMin > 0 && $nonDiscountedTotal >= $freeMin) ? 0 : $shippingMethod->cost;
                    }
                }

                // ── Method level discount ──
                $methodDiscount = 0;
                if ($shippingMethod->discount_amount > 0 && $baseCost > 0) {
                    $methodDiscount = $shippingMethod->discount_type === 'percent'
                        ? round($baseCost * $shippingMethod->discount_amount / 100, 2)
                        : $shippingMethod->discount_amount;
                    $methodDiscount = min($methodDiscount, $baseCost);
                }

                // ── Global order-amount discount ──
                $globalDiscount = 0;
                $minAmount   = \App\Models\BusinessSetting::where('type', 'free_shipping_min_amount')->value('value');
                $discountAmt = \App\Models\BusinessSetting::where('type', 'free_shipping_discount')->value('value');
                if ($minAmount && $discountAmt && $subTotal >= (float) $minAmount) {
                    $globalDiscount = min((float) $discountAmt, $baseCost - $methodDiscount);
                }

                $finalCost = max(0, $baseCost - $methodDiscount - $globalDiscount);

                session()->put('shipping_discount', $methodDiscount + $globalDiscount);

                // ── Cart এর সব item এ shipping_cost update ──
                $cart = $cart->map(function ($item) use ($finalCost) {
                    $item['shipping_cost'] = $finalCost;
                    return $item;
                });
                session()->put('cart', $cart);
            }
        }

        return view('web.layouts.partials.cart_order_summary');
    }
}
