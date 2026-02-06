<?php

namespace App\Http\Controllers\Customer;

use App\CPU\Helpers;
use App\Http\Controllers\Controller;
use App\Models\CartShipping;
use App\Models\Color;
use App\Models\Order;
use App\Models\Product;
use App\Models\ShippingAddress;
use App\Models\ShippingMethod;
use App\Models\UserInfo;
use Apps\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SystemController extends Controller
{
    public function set_payment_method($name)
    {
        if (auth('customer')->check() || session()->has('mobile_app_payment_customer_id')) {
            session()->put('payment_method', $name);
            return response()->json([
                'status' => 1
            ]);
        }
        return response()->json([
            'status' => 0
        ]);
    }

    public function set_shipping_method(Request $request)
    {
        //dd($request->all());
        if ($request['id'] != 0) {
            session()->put('shipping_method_id', $request['id']);

            $cart = $request->session()->get('cart', collect([]));
            $cart = $cart->map(function ($object, $key) use ($request) {
                if ($key == $request['key']) {
                    $object['shipping_method_id'] = $request['id'];
                    $object['shipping_cost'] = ShippingMethod::find($request['id'])->cost;
                }
                return $object;
            });
            $data = $request->session()->put('cart', $cart);
            //dd($data);

            return response()->json([
                'status' => 1,
                'html'   => view('web.layouts.partials.cart_order_summary')->render()
            ]);
        }
        return response()->json([
            'status' => 0
        ]);
    }
    public function set_pos_shipping_method(Request $request)
    {
        if ($request['id'] != 0) {
            session()->put('shipping_method_id', $request['id']);


            $shipping_cost = ShippingMethod::find($request['id'])->cost;

            $request->session()->put('shipping_cost', $shipping_cost);

            return response()->json([
                'status' => 1
            ]);
        }
        return response()->json([
            'status' => 0
        ]);
    }

    public static function insert_into_cart_shipping($request)
    {
        $shipping = CartShipping::where(['cart_group_id' => $request['cart_group_id']])->first();
        if (isset($shipping) == false) {
            $shipping = new CartShipping();
        }
        $shipping['cart_group_id'] = $request['cart_group_id'];
        $shipping['shipping_method_id'] = $request['id'];
        $shipping['shipping_cost'] = ShippingMethod::find($request['id'])->cost;
        $shipping->save();
    }

    public function choose_shipping_address(Request $request)
    {
        $shipping = [];
        $billing = [];
        parse_str($request->shipping, $shipping);
        parse_str($request->billing, $billing);

        if (isset($shipping['save_address']) && $shipping['save_address'] == 'on') {

            if ($shipping['contact_person_name'] == null || $shipping['address'] == null || $shipping['city'] == null) {
                return response()->json([
                    'errors' => ['']
                ], 403);
            }

            $address_id = DB::table('shipping_addresses')->insertGetId([
                'customer_id' => auth('customer')->id(),
                'contact_person_name' => $shipping['contact_person_name'],
                'address_type' => $shipping['address_type'],
                'address' => $shipping['address'],
                'city' => $shipping['city'],
                'zip' => $shipping['zip'],
                'phone' => $shipping['phone'],
                'latitude' => $shipping['latitude'],
                'longitude' => $shipping['longitude'],
                'is_billing' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } else if ($shipping['shipping_method_id'] == 0) {

            if ($shipping['contact_person_name'] == null || $shipping['address'] == null || $shipping['city'] == null) {
                return response()->json([
                    'errors' => ['']
                ], 403);
            }

            $address_id = DB::table('shipping_addresses')->insertGetId([
                'customer_id' => 0,
                'contact_person_name' => $shipping['contact_person_name'],
                'address_type' => $shipping['address_type'],
                'address' => $shipping['address'],
                'city' => $shipping['city'],
                'zip' => $shipping['zip'],
                'phone' => $shipping['phone'],
                'latitude' => $shipping['latitude'],
                'longitude' => $shipping['longitude'],
                'is_billing' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } else {
            $address_id = $shipping['shipping_method_id'];
        }


        if ($request->billing_addresss_same_shipping == 'false') {

            if (isset($billing['save_address_billing']) && $billing['save_address_billing'] == 'on') {

                if ($billing['billing_contact_person_name'] == null || $billing['billing_address'] == null || $billing['billing_city'] == null) {
                    return response()->json([
                        'errors' => ['']
                    ], 403);
                }

                $billing_address_id = DB::table('shipping_addresses')->insertGetId([
                    'customer_id' => auth('customer')->id(),
                    'contact_person_name' => $billing['billing_contact_person_name'],
                    'address_type' => $billing['billing_address_type'],
                    'address' => $billing['billing_address'],
                    'city' => $billing['billing_city'],
                    'zip' => $billing['billing_zip'],
                    'phone' => $billing['billing_phone'],
                    'latitude' => $billing['billing_latitude'],
                    'longitude' => $billing['billing_longitude'],
                    'is_billing' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            } else if ($billing['billing_method_id'] == 0) {

                if ($billing['billing_contact_person_name'] == null || $billing['billing_address'] == null || $billing['billing_city'] == null) {
                    return response()->json([
                        'errors' => ['']
                    ], 403);
                }

                $billing_address_id = DB::table('shipping_addresses')->insertGetId([
                    'customer_id' => 0,
                    'contact_person_name' => $billing['billing_contact_person_name'],
                    'address_type' => $billing['billing_address_type'],
                    'address' => $billing['billing_address'],
                    'city' => $billing['billing_city'],
                    'zip' => $billing['billing_zip'],
                    'phone' => $billing['billing_phone'],
                    'latitude' => $billing['billing_latitude'],
                    'longitude' => $billing['billing_longitude'],
                    'is_billing' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            } else {
                $billing_address_id = $billing['billing_method_id'];
            }
        } else {
            $billing_address_id = $shipping['shipping_method_id'];
        }

        session()->put('address_id', $address_id);
        session()->put('billing_address_id', $billing_address_id);

        return response()->json([], 200);
    }
    public function productCheckout(Request $request)
    {
       // dd($request->all());
        if (!session()->has('cart') || count(session('cart')) == 0) {
            return redirect()->route('home');
        }

        // ================= VALIDATION =================
        $rules = [
            'shipping_area' => 'required',
            'payment_method' => 'required|in:cash_on_delivery,online_payment',
        ];

        if ($request->address_type === 'new' || !auth('customer')->check()) {
            $rules += [
                'name' => 'required|string',
                'phone' => 'required|regex:/^(01[3-9]\d{8})$/',
                'address' => 'required|string|max:200',
            ];
        }

        $request->validate($rules);

        // ================= CUSTOMER CHECK =================
        $authUser = Helpers::get_customer_check($request);

        if (!$authUser || $authUser === 'offline') {
            return back()->with('error', 'Customer authentication failed');
        }

        if ($authUser->is_active == 0) {
            return back()->with('error', 'Your account is inactive.');
        }

        DB::beginTransaction();

        try {

            // ================= Address CREATE =================
            $addressType = $request->input('address_type', 'new'); // default to 'new'
            //dd($addressType);

            if ($addressType === 'new' || !auth('customer')->check()) {
                $shippingAddress = ShippingAddress::create([
                    'customer_id' => auth('customer')->id(), // guest হলে null
                    'contact_person_name' => $request->name,
                    'phone' => $request->phone,
                    'address' => $request->address,
                ]);
            } else {
                $shippingAddress = ShippingAddress::findOrFail($addressType);
            }


            // ================= ORDER CREATE =================
            $discount = session('coupon_discount', 0);
            $coupon_code = session('coupon_code');

            $order_id = DB::table('orders')->insertGetId([
                'order_number' => rand(100000, 999999),
                'verification_code' => rand(100000, 999999),
                'customer_id' => auth('customer')->id(),
                'customer_type' => 'customer',
                'payment_status' => 'unpaid',
                'order_status' => 'pending',
                'payment_method' => $request->payment_method,
                'customer_note' => $request->order_note,
                'coupon_code' => $coupon_code,
                'discount_amount' => $discount,
                'discount_type' => $discount ? 'coupon_discount' : null,
                'order_amount' => Helpers::cart_grand_total(session('cart')) - $discount,
                'shipping_address' => $shippingAddress->id,
                'shipping_address_data' => json_encode($shippingAddress),
                'shipping_method_id' => $request->shipping_area,
                'created_at' => now(),
            ]);

            // ================= ORDER DETAILS =================
            foreach (session('cart') as $c) {

                $product = Product::lockForUpdate()->findOrFail($c['id']);

                if ($product->current_stock < $c['quantity']) {
                    throw new \Exception('Stock not available');
                }

                DB::table('order_details')->insert([
                    'order_id' => $order_id,
                    'product_id' => $c['id'],
                    'product_details' => json_encode($product),
                    'qty' => $c['quantity'],
                    'price' => $c['price'],
                    'tax' => $c['tax'] * $c['quantity'],
                    'discount' => $c['discount'] * $c['quantity'],
                    'discount_type' => 'discount_on_product',
                    'variant' => $c['variant'],
                    'variation' => json_encode($c['variations']),
                    'delivery_status' => 'pending',
                    'payment_status' => 'unpaid',
                    'created_at' => now(),
                ]);

                $product->decrement('current_stock', $c['quantity']);
            }

            // ================= CLEANUP =================
            UserInfo::where('session_id', $request->session_id)
                ->where('order_process', 'pending')
                ->delete();

            session()->forget([
                'cart',
                'coupon_code',
                'coupon_discount',
                'payment_method',
                'customer_info',
                'shipping_method_id',
            ]);

            DB::commit();

            return redirect()->route('customer.checkout-complete', $order_id);
        } catch (\Exception $e) {

            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }

    public function singlepCheckout(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string|max:150',
            'email' => 'nullable|email',
            'address' => 'required|string|max:255',
            'phone' => 'required|regex:/^(01[3-9]\d{8})$/',
            'customer_note' => 'nullable|string|max:200',
            'shipping_method' => 'required',
        ]);

        $authUser = Helpers::get_customer_check($request);
        if ($authUser) {
            $shippingAddress = new ShippingAddress();
            $shippingAddress->customer_id = auth('customer')->id();
            $shippingAddress->contact_person_name = $request->name;
            $shippingAddress->address = $request->address;
            $shippingAddress->city = 'city';
            $shippingAddress->phone = $request->phone;
            $shippingAddress->created_at = now();
            $shippingAddress->save();

            $product = Product::find($request->product_id);
            $str = '';
            $variations = [];
            //check the color enabled or disabled for the product
            if ($request->has('color')) {
                $data['color'] = $request['color'];
                $str = Color::where('code', $request['color'])->first()->name;
                $variations[] = $str;
            }
            $color_image_path = null;
            if ($request->has('color')) {
                $color_image = json_decode($product->color_variant, true);
                $selected_color_code = $request->color;

                // Find matching color
                $matched = collect($color_image)->firstWhere('code', $selected_color_code);

                if ($matched) {
                    $color_image_path = $matched['image'];
                } else {
                    dd('No matching color found');
                }
            }
            //Gets all the choice values of customer choice option and generate a string like Black-S-Cotton
            foreach (json_decode(Product::find($request->product_id)->choice_options) as $key => $choice) {
                $data[$choice->name] = $request[$choice->name];

                $variations[$choice->title] = $request[$choice->name];
                if ($str != null) {
                    $str .= '-' . str_replace(' ', '', $request[$choice->name]);
                } else {
                    $str .= str_replace(' ', '', $request[$choice->name]);
                }
            }
            $variations[] = $variations;
            $variant = $str;


            ///order table code
            $coupon_code = 0;
            $price = $request->price;
            $quantity = $request->quantity;
            $tax = $request->tax ?? 0;
            $discount = $request->discount ?? 0;
            if ($discount > 0) {
                if ($product->discount_type == 'flat') {
                    $discount = $request->discount;
                } else {
                    $discount = ($price / 100) * $request->discount;
                }
            }

            $f_discount = $discount * $quantity;
            $granTotal = ($price * $quantity) + ($tax * $quantity) - $f_discount;

            $or = [
                'id' => 100000 + Order::all()->count() + 1,
                'verification_code' => rand(100000, 999999),
                'customer_id' => auth('customer')->id(),
                'customer_type' => 'customer',
                'payment_status' => 'unpaid',
                'order_status' => 'pending',
                'payment_method' => $request->payment_method,
                'customer_note' => $request->customer_note,
                'transaction_ref' => null,
                'coupon_code' => $coupon_code,
                'discount_amount' => 0,
                'discount_type' => null,
                'order_amount' => $granTotal,
                'shipping_address' => $shippingAddress->id,
                'shipping_address_data' => ShippingAddress::find($shippingAddress->id),
                'shipping_method_id' => $request->shipping_method,
                'shipping_cost' => CartManager::get_shipping_cost($request->shipping_method),
                'created_at' => now()
            ];

            $order_id = DB::table('orders')->insertGetId($or);

            $product = Product::where(['id' => $request['product_id']])->first();
            $or_d = [
                'order_id' => $order_id,
                'product_id' => $request['product_id'],
                'seller_id' => $product->added_by == 'seller' ? $product->user_id : '0',
                'product_details' => $product,
                'color_image' => $color_image_path,
                'qty' => $request['quantity'],
                'price' => $request['price'],
                'tax' => $request['tax'] * $request['quantity'],
                'discount' => $discount * $request['quantity'],
                'discount_type' => 'discount_on_product',
                'variant' => $variant,
                'variation' => json_encode($variations),
                'delivery_status' => 'pending',
                'shipping_method_id' => $request['shipping_method'],
                'payment_status' => 'unpaid',
                'created_at' => now()
            ];

            if ($variant != null) {
                $type = $variant;
                $var_store = [];
                foreach (json_decode($product['variation'], true) as $var) {
                    if ($type == $var['type']) {
                        $var['qty'] -= $request['quantity'];
                    }
                    array_push($var_store, $var);
                }
                Product::where(['id' => $product['product_id']])->update([
                    'variation' => json_encode($var_store),
                ]);
            }
            if ($product['current_stock'] > 2) {
                Product::where(['id' => $product['product_id']])->update([
                    'current_stock' => $product['current_stock'] - $request['quantity']
                ]);
            }

            Product::where(['id' => $product['product_id']])->update([
                'current_stock' => $product['current_stock'] - $request['quantity']
            ]);

            DB::table('order_details')->insert($or_d);

            $userInfo = UserInfo::where('session_id', $request->input('session_id'))
                ->where('order_process', 'pending')
                ->first();

            if ($userInfo) {
                $userInfo->delete();
            }

            try {
                $fcm_token = User::where(['id' => auth('customer')->id()])->first()->cm_firebase_token;
                $value = \App\CPU\Helpers::order_status_update_message('pending');
                if ($value) {
                    $data = [
                        'title' => 'Order',
                        'description' => $value,
                        'order_id' => $order_id,
                        'image' => '',
                    ];
                    Helpers::send_push_notif_to_device($fcm_token, $data);
                }
            } catch (\Exception $e) {
                Toastr::error('FCM token config issue.');
            }


            session()->forget('cart');
            session()->forget('coupon_code');
            session()->forget('coupon_discount');
            session()->forget('payment_method');
            session()->forget('customer_info');
            session()->forget('shipping_method_id');
            $order = Order::find($order_id);

            return redirect()->route('customer.checkout-complete', ['id' => $order_id]);
        } else {
            return "something went wrong please try again";
        }
    }
    public function checkoutComplete($id)
    {
        $order = Order::find($id);
        if (!$order) {
            return redirect()->route('home');
        }
        return view('web.checkout_complete', compact('order'));
    }
}
