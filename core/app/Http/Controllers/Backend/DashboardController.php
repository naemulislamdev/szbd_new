<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Http\Request;

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

        $currentRevenue = Order::where('order_status', 'delivered')
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

        $lastRevenue = Order::where('order_status', 'delivered')
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

        return view('admin.dashboard', compact(
            'currentRevenue',
            'currentOrders',
            'currentCanceled',
            'currentAvg',
            'revenueGrowth',
            'orderGrowth',
            'cancelGrowth',
            'avgGrowth'
        ));
    }

    private function calculateGrowth($current, $last)
    {
        if ($last == 0) {
            return $current > 0 ? 100 : 0;
        }

        return (($current - $last) / $last) * 100;
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
