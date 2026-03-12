<?php

namespace App\CPU;

use App\Models\BusinessSetting;
use App\Models\Color;
use App\Models\Review;
use App\Models\User;

class Helpers
{
    public static function combinations($arrays)
    {
        $result = [[]];
        foreach ($arrays as $property => $property_values) {
            $tmp = [];
            foreach ($result as $result_item) {
                foreach ($property_values as $property_value) {
                    $tmp[] = array_merge($result_item, [$property => $property_value]);
                }
            }
            $result = $tmp;
        }
        return $result;
    }
    public static function rating_count($product_id, $rating)
    {
        return Review::where(['product_id' => $product_id, 'rating' => $rating])->count();
    }
    public static function pagination_limit()
    {
        $pagination_limit = BusinessSetting::where('type', 'pagination_limit')->first();
        if ($pagination_limit != null) {
            return $pagination_limit->value;
        } else {
            return 25;
        }
    }
    public static function get_settings($object, $type)
    {
        $config = null;
        foreach ($object as $setting) {
            if ($setting['type'] == $type) {
                $config = $setting;
            }
        }
        return $config;
    }
    public static function get_business_settings($name)
    {
        $data = BusinessSetting::where('type', $name)->first();

        if (!$data) {
            return null;
        }

        return json_decode($data->value, true) ?? $data->value;
    }
    // public static function get_business_settings($name)
    // {
    //     $config = null;
    //     $check = ['currency_model', 'currency_symbol_position', 'system_default_currency', 'language', 'company_name', 'decimal_point_settings', 'company_web_logo', 'company_mobile_logo', 'company_footer_logo', 'company_fav_icon'];
    //     session()->forget('company_mobile_logo');
    //     session()->forget('company_web_logo');
    //     session()->forget('company_footer_logo');
    //     if (in_array($name, $check) == true && session()->has($name)) {
    //         // $config = session($name);
    //     } else {
    //         $data = BusinessSetting::where(['type' => $name])->first();
    //         if (isset($data)) {
    //             $config = json_decode($data['value'], true);
    //             if (is_null($config)) {
    //                 $config = $data['value'];
    //             }
    //         }

    //         if (in_array($name, $check) == true) {
    //             session()->put($name, $config);
    //         }
    //     }

    //     return $config;
    // }
    public static function get_product_discount($product, $price)
    {
        $discount = 0;
        if ($product->discount_type == 'percent') {
            $discount = ($price * $product->discount) / 100;
        } elseif ($product->discount_type == 'flat') {
            $discount = $product->discount;
        }

        return floatval($discount);
    }
    public static function get_rating($reviews)
    {
        $rating5 = 0;
        $rating4 = 0;
        $rating3 = 0;
        $rating2 = 0;
        $rating1 = 0;
        foreach ($reviews as $key => $review) {
            if ($review->rating == 5) {
                $rating5 += 1;
            }
            if ($review->rating == 4) {
                $rating4 += 1;
            }
            if ($review->rating == 3) {
                $rating3 += 1;
            }
            if ($review->rating == 2) {
                $rating2 += 1;
            }
            if ($review->rating == 1) {
                $rating1 += 1;
            }
        }
        return [$rating5, $rating4, $rating3, $rating2, $rating1];
    }
    public static function get_overall_rating($reviews)
    {
        $totalRating = count($reviews);
        $rating = 0;
        foreach ($reviews as $key => $review) {
            $rating += $review->rating;
        }
        if ($totalRating == 0) {
            $overallRating = 0;
        } else {
            $overallRating = number_format($rating / $totalRating, 2);
        }

        return [$overallRating, $totalRating];
    }
    public static function get_customer_check($request = null)
    {
        // Already logged in (session + remember me)
        if (auth('customer')->check()) {
            return auth('customer')->user();
        }

        $phoneNumber = session('otp_phone') ?? ($request?->phone ?? null);



        // API authenticated user
        if ($request && $request->user()) {
            return $request->user();
        }

        $remember = true; // always remember customer

        //  Find customer by phone
        $user = User::where('phone', $phoneNumber)->first();

        // If not found, try email
        if (!$user && $request->email) {
            $user = User::where('email', $request->email)->first();
        }
        //dd($phoneNumber);
        // If still not found → create customer
        if (!$user) {
            $user = User::create([
                'name'  => $request->name ?? 'Guest',
                'email'   => $request->email ?? ($phoneNumber . '_bd@gmail.com'),
                'phone'   => $phoneNumber,
                'password' => bcrypt($phoneNumber),
            ]);
        }

        // Login customer (remember forever)
        auth()->guard('customer')->login($user, $remember);

        return $user;
    }
    public static function cart_grand_total($cart) // needed
    {
        $total = 0;
        if (!empty($cart)) {
            foreach ($cart as $item) {
                $product_subtotal = ($item['price'] * $item['quantity'])
                    + ($item['tax'] * $item['quantity'])
                    - $item['discount'] * $item['quantity'];
                $total += $product_subtotal;
            }
        }
        return $total;
    }
    public static function set_data_format($data)
    {
        try {
            $variation = [];
            // $data['category_ids'] = json_decode($data['category_ids']);
            $data['images'] = json_decode($data['images']);
            $data['colors'] = Color::whereIn('code', json_decode($data['colors']))->get(['name', 'code']);
            $attributes = [];
            if (json_decode($data['attributes']) != null) {
                foreach (json_decode($data['attributes']) as $attribute) {
                    $attributes[] = (int)$attribute;
                }
            }
            $data['attributes'] = $attributes;
            $data['choice_options'] = json_decode($data['choice_options']);
            foreach (json_decode($data['variation'], true) as $var) {
                $variation[] = [
                    'type' => $var['type'],
                    'price' => (float)$var['price'],
                    'sku' => $var['sku'],
                    'qty' => (int)$var['qty'],
                ];
            }
            $data['variation'] = $variation;
        } catch (\Exception $exception) {
            info($exception);
        }

        return $data;
    }

    public static function product_data_formatting($data, $multi_data = false)
    {
        $storage = [];
        if ($multi_data == true) {
            foreach ($data as $item) {
                $storage[] = Helpers::set_data_format($item);
            }
            $data = $storage;
        } else {
            $data = Helpers::set_data_format($data);
        }

        return $data;
    }
    public static function error_processor($validator)
    {
        $err_keeper = [];
        foreach ($validator->errors()->getMessages() as $index => $error) {
            $err_keeper[] = ['code' => $index, 'message' => $error[0]];
        }
        return $err_keeper;
    }
}
