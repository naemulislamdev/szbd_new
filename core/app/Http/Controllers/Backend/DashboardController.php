<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Order;
use App\Models\OrderDetail;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function dashboard()
    {
        $now = Carbon::now();

        $currentMonthStart = $now->copy()->startOfMonth();
        $currentMonthEnd   = $now->copy()->endOfMonth();

        $lastMonthStart = $now->copy()->subMonth()->startOfMonth();
        $lastMonthEnd   = $now->copy()->subMonth()->endOfMonth();

        /*
        |--------------------------------------------------------------------------
        | CURRENT MONTH DATA
        |--------------------------------------------------------------------------
        */

        $currentRevenue = Order::where('order_status', 'confirmed')
            ->whereBetween('created_at', [$currentMonthStart, $currentMonthEnd])
            ->sum('order_amount');

        $currentOrders = Order::whereBetween('created_at', [$currentMonthStart, $currentMonthEnd])
            ->count();

        $currentCanceled = Order::where('order_status', 'canceled')
            ->whereBetween('created_at', [$currentMonthStart, $currentMonthEnd])
            ->count();

        $currentDeliveredCount = Order::where('order_status', 'delivered')
            ->whereBetween('created_at', [$currentMonthStart, $currentMonthEnd])
            ->count();

        $currentAvg = $currentDeliveredCount > 0
            ? $currentRevenue / $currentDeliveredCount
            : 0;


        /*
        |--------------------------------------------------------------------------
        | LAST MONTH DATA
        |--------------------------------------------------------------------------
        */

        $lastRevenue = Order::where('order_status', 'confirmed')
            ->whereBetween('created_at', [$lastMonthStart, $lastMonthEnd])
            ->sum('order_amount');

        $lastOrders = Order::whereBetween('created_at', [$lastMonthStart, $lastMonthEnd])
            ->count();

        $lastCanceled = Order::where('order_status', 'canceled')
            ->whereBetween('created_at', [$lastMonthStart, $lastMonthEnd])
            ->count();

        $lastDeliveredCount = Order::where('order_status', 'delivered')
            ->whereBetween('created_at', [$lastMonthStart, $lastMonthEnd])
            ->count();

        $lastAvg = $lastDeliveredCount > 0
            ? $lastRevenue / $lastDeliveredCount
            : 0;


        /*
        |--------------------------------------------------------------------------
        | GROWTH CALCULATION
        |--------------------------------------------------------------------------
        */

        $revenueGrowth  = $this->calculateGrowth($currentRevenue, $lastRevenue);
        $orderGrowth    = $this->calculateGrowth($currentOrders, $lastOrders);
        $cancelGrowth   = $this->calculateGrowth($currentCanceled, $lastCanceled);
        $avgGrowth      = $this->calculateGrowth($currentAvg, $lastAvg);

        //top selling products
        $filter = 'this_month';
        $topSellingProducts = $this->getTopSellingProducts($filter);

        return view('admin.dashboard', compact(
            'currentRevenue',
            'currentOrders',
            'currentCanceled',
            'currentAvg',
            'revenueGrowth',
            'orderGrowth',
            'cancelGrowth',
            'avgGrowth',
            'topSellingProducts',
            'filter'
        ));
    }

    private function calculateGrowth($current, $last)
    {
        if ($last == 0) {
            return $current > 0 ? 100 : 0;
        }

        return (($current - $last) / $last) * 100;
    }

    public function topSellingProducts(Request $request)
    {
        $filter = $request->filter ?? 'this_month';
        $startDate = $request->start_date;
        $endDate = $request->end_date;

        $topSellingProducts = $this->getTopSellingProducts($filter, $startDate, $endDate);

        $html = view('admin.partials.top_selling_products_list', compact('topSellingProducts'))->render();

        return response()->json([
            'status' => true,
            'html' => $html,
        ]);
    }

    private function getTopSellingProducts($filter = 'this_month', $startDate = null, $endDate = null)
    {
        if ($filter === 'custom_range' && !empty($startDate) && !empty($endDate)) {
            $startDate = Carbon::parse($startDate)->startOfDay();
            $endDate = Carbon::parse($endDate)->endOfDay();
        } else {
            switch ($filter) {
                case 'today':
                    $startDate = Carbon::today()->startOfDay();
                    $endDate = Carbon::today()->endOfDay();
                    break;

                case 'yesterday':
                    $startDate = Carbon::yesterday()->startOfDay();
                    $endDate = Carbon::yesterday()->endOfDay();
                    break;

                case 'last_week':
                    $startDate = Carbon::now()->subWeek()->startOfWeek();
                    $endDate = Carbon::now()->subWeek()->endOfWeek();
                    break;

                case 'last_month':
                    $startDate = Carbon::now()->subMonth()->startOfMonth();
                    $endDate = Carbon::now()->subMonth()->endOfMonth();
                    break;

                case 'this_year':
                    $startDate = Carbon::now()->startOfYear();
                    $endDate = Carbon::now()->endOfYear();
                    break;

                case 'this_month':
                default:
                    $startDate = Carbon::now()->startOfMonth();
                    $endDate = Carbon::now()->endOfMonth();
                    break;
            }
        }

        $products = OrderDetail::select(
            'products.id',
            'products.name',
            'products.code',
            'products.unit_price',
            'products.thumbnail',
            DB::raw('SUM(order_details.qty) as total_qty'),
            DB::raw('SUM(order_details.price * order_details.qty) as total_amount')
        )
            ->join('products', 'products.id', '=', 'order_details.product_id')
            ->join('orders', 'orders.id', '=', 'order_details.order_id')
            ->where('orders.order_status', 'confirmed')
            ->whereBetween('orders.created_at', [$startDate, $endDate])
            ->groupBy(
                'products.id',
                'products.name',
                'products.code',
                'products.unit_price',
                'products.thumbnail'
            )
            ->orderByDesc('total_qty')
            ->take(20)
            ->get();

        $maxQty = $products->max('total_qty') ?: 1;

        $products->transform(function ($item) use ($maxQty) {
            $item->progress = ($item->total_qty / $maxQty) * 100;
            return $item;
        });

        return $products;
    }

    public function monthlyIncome(Request $request)
    {
        $filter = $request->filter;

        $start = null;
        $end   = null;

        switch ($filter) {
            case 'today':
                $start = Carbon::today();
                $end   = Carbon::today()->endOfDay();
                break;

            case 'last_week':
                $start = Carbon::now()->subWeek()->startOfWeek();
                $end   = Carbon::now()->subWeek()->endOfWeek();
                break;

            case 'last_month':
                $start = Carbon::now()->subMonth()->startOfMonth();
                $end   = Carbon::now()->subMonth()->endOfMonth();
                break;

            case 'this_year':
                $start = Carbon::now()->startOfYear();
                $end   = Carbon::now()->endOfYear();
                break;

            default: // this_month
                $start = Carbon::now()->startOfMonth();
                $end   = Carbon::now()->endOfMonth();
        }

        $data = Order::where('order_status', 'delivered')
            ->whereBetween('created_at', [$start, $end])
            ->selectRaw('MONTH(created_at) as month, SUM(order_amount) as total')
            ->groupBy('month')
            ->pluck('total', 'month');

        $months = [];
        $totals = [];

        for ($i = 1; $i <= 12; $i++) {
            $months[] = date("M", mktime(0, 0, 0, $i, 1));
            $totals[] = $data[$i] ?? 0;
        }

        return response()->json([
            'months' => $months,
            'totals' => $totals
        ]);
    }

    public function business_overview(Request $request)
    {
        // Logic for business overview
    }

    public function OrderReportFilter(Request $request)
    {
        // Logic for order report filter
    }
}
