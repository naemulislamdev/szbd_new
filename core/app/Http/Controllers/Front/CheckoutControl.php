<?php

namespace App\Http\Controllers\Front;

use App\CPU\Helpers;
use App\Http\Controllers\Controller;
use App\Models\Color;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use App\Models\ShippingAddress;
use App\Models\ShippingMethod;
use App\Models\UserInfo;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class CheckoutControl extends Controller
{
    public function sendOtp(Request $request)
    {
        $request->validate([
            'phone' => ['required', 'regex:/^(01[3-9]\d{8})$/'],
        ]);

        $userPhone = trim($request->phone);

        $now = now()->timestamp;
        $cooldownUntil = session('otp_resend_cooldown_until');

        // cooldown check
        if ($cooldownUntil && $now < $cooldownUntil) {
            $waitSeconds = $cooldownUntil - $now;
            $minutes = floor($waitSeconds / 60);
            $seconds = $waitSeconds % 60;

            $timeText = $minutes > 0
                ? "{$minutes} মিনিট {$seconds} সেকেন্ড"
                : "{$seconds} সেকেন্ড";

            return response()->json([
                'status' => 'error',
                'message' => "অনুগ্রহ করে {$timeText} পরে আবার OTP চেষ্টা করুন।",
                'retry_after' => $waitSeconds
            ], 429);
        }

        $otp = random_int(1000, 9999);

        $sendSMS = $this->sendOtpSMS($userPhone, $otp);

        if (!$sendSMS) {
            return response()->json([
                'status' => 'error',
                'message' => 'OTP পাঠানো যায়নি। আবার চেষ্টা করুন।'
            ], 500);
        }

        // OTP session save
        session([
            'otp' => hash('sha256', (string) $otp),
            'otp_phone' => $userPhone,
            'otp_verified' => false,
            'otp_expires_at' => now()->addMinutes(5)->timestamp,
            'otp_last_sent_at' => $now,
            'otp_resend_cooldown_until' => now()->addMinute()->timestamp,
            'otp_expired_once' => false,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'OTP sent successfully',
            'otp_expires_at' => session('otp_expires_at'),
            'resend_cooldown_until' => session('otp_resend_cooldown_until'),
        ]);
    }

    public function sendOtpSMS($userPhone, $otpCode)
    {
        // Ensure BD number format: 8801XXXXXXXXX
        if (substr($userPhone, 0, 2) !== '88') {
            $userPhone = '88' . $userPhone;
        }

        $message = "{$otpCode} আপনার অর্ডার যাচাইয়ের OTP কোড। Shopping Zone BD";

        $response = Http::asForm()->post(env('BULKSMSBD_URL'), [
            'api_key'  => env('BULKSMSBD_API_KEY'),
            'type'     => 'text',
            'number'   => $userPhone,
            'senderid' => env('BULKSMSBD_SENDER_ID'), // NON-masking
            'message'  => $message,
        ]);

        $result = $response->json();
        // dd($result);

        // Log if SMS fails
        if (!isset($result['response_code']) || $result['response_code'] != 202) {
            Log::error('SMS Failed', [
                'mobile' => $userPhone,
                'response' => $result
            ]);
            return false;
        }

        return true;
    }
    public function resendOtp(Request $request)
    {
        $request->validate([
            'phone' => ['required', 'regex:/^(01[3-9]\d{8})$/'],
        ]);

        $userPhone = trim($request->phone);
        $now = now()->timestamp;

        $cooldownUntil = session('otp_resend_cooldown_until');

        if ($cooldownUntil && $now < $cooldownUntil) {
            $waitSeconds = $cooldownUntil - $now;
            $minutes = floor($waitSeconds / 60);
            $seconds = $waitSeconds % 60;

            $timeText = $minutes > 0
                ? "{$minutes} মিনিট {$seconds} সেকেন্ড"
                : "{$seconds} সেকেন্ড";

            return response()->json([
                'status' => 'error',
                'message' => "অনুগ্রহ করে {$timeText} পরে আবার OTP চেষ্টা করুন।",
                'retry_after' => $waitSeconds
            ], 429);
        }

        $otp = random_int(1000, 9999);

        $sendSMS = $this->sendOtpSMS($userPhone, $otp);

        if (!$sendSMS) {
            return response()->json([
                'status' => 'error',
                'message' => 'OTP পাঠানো যায়নি। আবার চেষ্টা করুন।'
            ], 500);
        }

        session([
            'otp' => hash('sha256', (string) $otp),
            'otp_phone' => $userPhone,
            'otp_verified' => false,
            'otp_expires_at' => now()->addMinutes(5)->timestamp,
            'otp_last_sent_at' => $now,
            'otp_resend_cooldown_until' => now()->addMinute()->timestamp,
            'otp_expired_once' => false,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'নতুন OTP পাঠানো হয়েছে।',
            'otp_expires_at' => session('otp_expires_at'),
            'resend_cooldown_until' => session('otp_resend_cooldown_until'),
        ]);
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp'   => ['required', 'digits:4'],
        ]);

        if (!session('otp') || !session('otp_phone')) {
            return response()->json([
                'status' => 'error',
                'message' => 'OTP পাওয়া যায়নি। আবার OTP নিন।'
            ], 422);
        }

        if (session('otp_phone') !== $request->phone) {
            return response()->json([
                'status' => 'error',
                'message' => 'ফোন নাম্বার মিলছে না।'
            ], 422);
        }

        if (now()->timestamp > (int) session('otp_expires_at')) {
            session([
                'otp_expired_once' => true,
                'otp_resend_cooldown_until' => null, // first resend immediately allow
            ]);

            session()->forget([
                'otp',
                'otp_expires_at',
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'OTP expired. এখন আবার OTP নিতে পারবেন।',
                'expired' => true,
                'allow_resend_now' => true,
            ], 422);
        }

        if (!hash_equals(session('otp'), hash('sha256', (string) $request->otp))) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid OTP'
            ], 422);
        }

        session([
            'otp_verified' => true
        ]);

        session()->forget([
            'otp',
            'otp_expires_at',
            'otp_resend_cooldown_until',
            'otp_expired_once',
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'OTP verified successfully.'
        ]);
    }
    //End of OTP functions

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
            return response()->json([
                'status' => 1,
                'html'   => view('web.layouts.partials.cart_order_summary')->render()
            ]);
        }
        return response()->json([
            'status' => 0
        ]);
    }

    public function productCheckout(Request $request)
    {
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
                'phone' => 'nullable|regex:/^(01[3-9]\d{8})$/',
                'address' => 'required|string|max:200',
            ];
        }

        $request->validate($rules);

        // ================= CUSTOMER CHECK =================
        $authUser = Helpers::get_customer_check($request);

        if (!$authUser || $authUser === 'offline') {
            return back()->with('error', 'Customer authentication failed');
        }
        $userFind = User::find($authUser->id);

        if ($userFind->is_active == 0) {
            return back()->with('error', 'Your account is inactive.');
        }

        DB::beginTransaction();

        try {

            // ================= Address CREATE =================
            $addressType = $request->input('address_type', 'new');

            if ($addressType === 'new' || !auth('customer')->check()) {
                $shippingAddress = ShippingAddress::create([
                    'customer_id' => auth('customer')->id(),
                    'contact_person_name' => $request->name,
                    'phone' => session()->has('otp_phone') ? session('otp_phone') : $request->phone,
                    'address' => $request->address,
                ]);
            } else {
                $shippingAddress = ShippingAddress::findOrFail($addressType);
            }

            // ================= ORDER CREATE =================
            $discount = session('coupon_discount', 0);
            $coupon_code = session('coupon_code');

            $shippingMethod = ShippingMethod::findOrFail($request->shipping_area);

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
                'shipping_cost' => $shippingMethod->cost,
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

            session(['otp_verified' => false]);

            session()->forget([
                'cart',
                'coupon_code',
                'coupon_discount',
                'payment_method',
                'customer_info',
                'shipping_method_id',
                'otp',
                'otp_phone',
                'otp_expires_at',
                'otp_last_sent_at',
            ]);

            DB::commit();

            return redirect()->route('checkout-complete', $order_id);
        } catch (\Exception $e) {

            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }

    public function singlepCheckout(Request $request)
    {
        //dd($request->all());
        // ================= VALIDATION =================
        $rules = [
            'shipping_method' => 'required',
            // 'payment_method' => 'required|in:cash_on_delivery,online_payment',
        ];

        if ($request->address_type === 'new' || !auth('customer')->check()) {
            $rules += [
                'name' => 'required|string',
                'phone' => 'nullable|regex:/^(01[3-9]\d{8})$/',
                'address' => 'required|string|max:200',
                'customer_note' => 'nullable|string|max:200',
            ];
        }

        $request->validate($rules);

        // ================= CUSTOMER CHECK =================
        $authUser = Helpers::get_customer_check($request);
        //dd($authUser);

        if (!$authUser || $authUser === 'offline') {
            return back()->with('error', 'Customer authentication failed');
        }
        $userFind = User::find($authUser->id);

        if ($userFind->is_active == 0) {
            return back()->with('error', 'Your account is inactive.');
        }

        if ($authUser) {
            // ================= Address CREATE =================
            $addressType = $request->input('address_type', 'new');

            if ($addressType === 'new' || !auth('customer')->check()) {
                $shippingAddress = ShippingAddress::create([
                    'customer_id' => auth('customer')->id(),
                    'contact_person_name' => $request->name,
                    'phone' => session()->has('otp_phone') ? session('otp_phone') : $request->phone,
                    'address' => $request->address,
                ]);
            } else {
                $shippingAddress = ShippingAddress::findOrFail($addressType);
            }

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
            // $product = Product::find($request->product_id);
            $choiceOptions = is_array($product->choice_options)
                ? $product->choice_options
                : json_decode($product->choice_options ?? '[]', true);
            //Gets all the choice values of customer choice option and generate a string like Black-S-Cotton
            foreach ($choiceOptions as $key => $choice) {
                $data[$choice['name']] = $request[$choice['name']];

                $variations[$choice['title']] = $request[$choice['name']];
                if ($str != null) {
                    $str .= '-' . str_replace(' ', '', $request[$choice['name']]);
                } else {
                    $str .= str_replace(' ', '', $request[$choice['name']]);
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
            $shippingMethod = ShippingMethod::findOrFail($request->shipping_method);
            $or = [
                'order_number' => rand(100000, 999999),
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
                'shipping_address_data' => json_encode($shippingAddress),
                'shipping_method_id' => $request->shipping_method,
                'shipping_cost' => $shippingMethod->cost,

                'created_at' => now()
            ];

            $order = Order::create($or);

            // $product = Product::where(['id' => $request['product_id']])->first();
            $orderDetails = [
                'order_id' => $order->id,
                'product_id' => $request['product_id'],
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

            OrderDetail::create($orderDetails);

            $userInfo = UserInfo::where('session_id', $request->input('session_id'))
                ->where('order_process', 'pending')
                ->first();

            if ($userInfo) {
                $userInfo->delete();
            }

            session(['otp_verified' => false]);

            session()->forget([
                'cart',
                'coupon_code',
                'coupon_discount',
                'payment_method',
                'customer_info',
                'shipping_method_id',
                'otp',
                'otp_phone',
                'otp_expires_at',
                'otp_last_sent_at',
            ]);

            return redirect()->route('checkout-complete', ['id' => $order->id]);
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
