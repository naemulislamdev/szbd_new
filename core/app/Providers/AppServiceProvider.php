<?php

namespace App\Providers;

use App\Models\BusinessSetting;
use App\Models\Order;
use App\Models\UserInfo;
use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrap();
        Schema::defaultStringLength(191);
        try {
            $web = BusinessSetting::all();

            // $settings = Helpers::get_settings($web, 'colors');
            // $data = json_decode($settings['value'] ?? '{}', true);

            // $web_config = [
            //     'primary_color' => $data['primary'] ?? '#0d6efd',
            //     'secondary_color' => $data['secondary'] ?? '#6c757d',
            //     'name' => Helpers::get_settings($web, 'company_name'),
            // ];

            $language = BusinessSetting::where('type', 'language')->first();

            $userInfoCounts = UserInfo::selectRaw("
            COUNT(*) as total,
            SUM(CASE WHEN order_status = 'pending' THEN 1 ELSE 0 END) as pending,
            SUM(CASE WHEN order_status = 'confirmed' THEN 1 ELSE 0 END) as confirmed,
            SUM(CASE WHEN order_status = 'canceled' THEN 1 ELSE 0 END) as canceled
        ")->first();
            $todayUserinfos = UserInfo::whereDate('created_at', now())
                ->where('order_status', 'pending')
                ->count();
            $orderCounts = Order::selectRaw("
            COUNT(*) as total,
            SUM(CASE WHEN order_status = 'pending' THEN 1 ELSE 0 END) as pending,
            SUM(CASE WHEN order_status = 'confirmed' THEN 1 ELSE 0 END) as confirmed,
            SUM(CASE WHEN order_status = 'processing' THEN 1 ELSE 0 END) as processing,
            SUM(CASE WHEN order_status = 'out_for_delivery' THEN 1 ELSE 0 END) as out_for_delivery,
            SUM(CASE WHEN order_status = 'delivered' THEN 1 ELSE 0 END) as delivered,
            SUM(CASE WHEN order_status = 'returned' THEN 1 ELSE 0 END) as returned,
            SUM(CASE WHEN order_status = 'failed' THEN 1 ELSE 0 END) as failed,
            SUM(CASE WHEN order_status = 'canceled' THEN 1 ELSE 0 END) as canceled
        ")->first();


            $todayOrders = Order::whereDate('created_at', now())
                ->where('order_status', 'pending')
                ->count();
        } catch (\Throwable $e) {
            logger()->error('AppServiceProvider error: ' . $e->getMessage());
        }

        // ✅ ALWAYS shared — no undefined variable
        View::share([
            // 'web_config'  => $web_config,
            'language'    => $language,
            'orderCounts' => $orderCounts,
            'todayOrders' => $todayOrders,
            'userInfoCounts' => $userInfoCounts,
            'todayUserinfos' => $todayUserinfos,
        ]);
    }
}
