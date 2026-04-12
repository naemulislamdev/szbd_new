<?php

namespace App\Http\Controllers\api\v1;

use App\CPU\Helpers;
use App\CPU\ProductManager;
use App\Http\Controllers\Controller;
use App\Models\Color;
use App\Models\Currency;
use App\Models\HelpTopic;
use App\Models\ShippingType;

class ConfigController extends Controller
{
    public function configuration()
    {
        $currency = Currency::all()->map(function($c) {
            $c->exchange_rate = (double)($c->exchange_rate ?? 1);
            return $c;
        });

        // Sort currencies so that the system default currency (BDT, id=2)
        // appears at BOTH index 0 and index 1. The Flutter app's PriceConverter
        // reads exchange_rate from currencyList[0] and symbol from currencyList[1].
        $defaultCurrencyId = (int)Helpers::get_business_settings('system_default_currency');
        $defaultCurrency = $currency->firstWhere('id', $defaultCurrencyId);
        if ($defaultCurrency) {
            $others = $currency->where('id', '!=', $defaultCurrencyId)->values();
            $currency = collect([$defaultCurrency, $defaultCurrency])->merge($others);
        }
        $social_login = [];
        foreach (Helpers::get_business_settings('social_login') as $social) {
            $config = [
                'login_medium' => $social['login_medium'],
                'status' => (boolean)$social['status']
            ];
            array_push($social_login, $config);
        }

        $languages = Helpers::get_business_settings('pnc_language');
        $lang_array = [];
        foreach ($languages as $language) {
            array_push($lang_array, [
                'code' => $language,
                'name' => 'test'
                // 'name' => Helpers::get_language_name($language)
            ]);
        }

        $admin_shipping = ShippingType::where('seller_id',0)->first();
        $shipping_type = isset($admin_shipping)==true?$admin_shipping->shipping_type:'order_wise';

        return response()->json([
            'system_default_currency' => (int)Helpers::get_business_settings('system_default_currency'),
            'digital_payment' => (boolean)Helpers::get_business_settings('digital_payment')['status'] ?? 0,
            'cash_on_delivery' => (boolean)Helpers::get_business_settings('cash_on_delivery')['status'] ?? 0,
            'base_urls' => [
                'product_image_url' => ProductManager::product_image_path('product'),
                'product_thumbnail_url' => ProductManager::product_image_path('thumbnail'),
                'brand_image_url' => asset('assets/storage/brand'),
                'customer_image_url' => asset('assets/storage/profile'),
                'banner_image_url' => asset('assets/storage/banner'),
                'category_image_url' => asset('assets/storage/category'),
                'review_image_url' => asset('assets/storage/app/public'),
                'seller_image_url' => asset('assets/storage/seller'),
                'shop_image_url' => asset('assets/storage/shop'),
                'notification_image_url' => asset('storage/notification'),
            ],
            'static_urls' => [
                'contact_us' => route('contacts'),
                'brands' => route('brands'),
                'categories' => route('categories'),
                'customer_account' => route('user-account'),
            ],
            'about_us' => Helpers::get_business_settings('about_us'),
            'privacy_policy' => Helpers::get_business_settings('privacy_policy'),
            'faq' => HelpTopic::all(),
            'terms_&_conditions' => Helpers::get_business_settings('terms_condition'),
            'currency_list' => $currency,
            'currency_symbol_position' => Helpers::get_business_settings('currency_symbol_position') ?? 'right',
            'business_mode'=> Helpers::get_business_settings('business_mode'),
            'maintenance_mode' => (boolean)Helpers::get_business_settings('maintenance_mode') ?? 0,
            'language' => $lang_array,
            'colors' => Color::all(),
            'unit' => ['kg', 'pc', 'gms', 'ltrs'],
            'shipping_method' => Helpers::get_business_settings('shipping_method'),
            'email_verification' => (boolean)Helpers::get_business_settings('email_verification'),
            'phone_verification' => (boolean)Helpers::get_business_settings('phone_verification'),
            'country_code' => Helpers::get_business_settings('country_code'),
            'social_login' => $social_login,
            'currency_model' => Helpers::get_business_settings('currency_model'),
            'forgot_password_verification' => Helpers::get_business_settings('forgot_password_verification'),
            'announcement'=> Helpers::get_business_settings('announcement'),
            'pixel_analytics'=> Helpers::get_business_settings('pixel_analytics'),
            'software_version'=>env('SOFTWARE_VERSION'),
            'decimal_point_settings'=>Helpers::get_business_settings('decimal_point_settings'),
            'inhouse_selected_shipping_type'=>$shipping_type,
            'billing_input_by_customer'=>Helpers::get_business_settings('billing_input_by_customer'),
            'minimum_order_limit'=>Helpers::get_business_settings('minimum_order_limit'),
            'wallet_status'=>Helpers::get_business_settings('wallet_status'),
            'loyalty_point_status'=>Helpers::get_business_settings('loyalty_point_status'),
            'loyalty_point_exchange_rate'=>Helpers::get_business_settings('loyalty_point_exchange_rate'),
            'loyalty_point_minimum_point'=>Helpers::get_business_settings('loyalty_point_minimum_point')

        ]);
    }
}

