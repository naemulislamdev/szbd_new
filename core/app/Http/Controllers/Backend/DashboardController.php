<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\AdminWallet;
use App\Models\Brand;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use App\Models\User;
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

        $currentDeliveredCount = Order::where('order_status', 'confirmed')
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
        // Fetch data
        // Dashboard overview Order report data
        $from = date('Y-m-d') . " 00:00:00";
        $to   = date('Y-m-d') . " 23:59:59";
        $results = Order::selectRaw("
                COUNT(*) as total_orders,
                SUM(order_amount) as total_amount,
                SUM(CASE WHEN order_status='pending' THEN 1 END) as pending_qty,
                SUM(CASE WHEN order_status='pending' THEN order_amount END) as pending_amount,
                SUM(CASE WHEN order_status='confirmed' THEN 1 END) as confirmed_qty,
                SUM(CASE WHEN order_status='confirmed' THEN order_amount END) as confirmed_amount,
                SUM(CASE WHEN order_status='canceled' THEN 1 END) as canceled_qty,
                SUM(CASE WHEN order_status='canceled' THEN order_amount END) as canceled_amount
        ")
            ->whereBetween('created_at', [$from, $to])
            ->first();
        $results->total_amount        = $results->total_amount ?? 0;
        $results->pending_amount      = $results->pending_amount ?? 0;
        $results->confirmed_amount    = $results->confirmed_amount ?? 0;
        $results->canceled_amount     = $results->canceled_amount ?? 0;

        // dashboard overview order stats
        $top_sell = OrderDetail::with(['product'])
            ->select('product_id', DB::raw('SUM(qty) as count'))
            ->groupBy('product_id')
            ->orderBy("count", 'desc')
            ->take(6)
            ->get();

        $most_rated_products = Product::rightJoin('reviews', 'reviews.product_id', '=', 'products.id')
            ->groupBy('product_id')
            ->select([
                'product_id',
                DB::raw('AVG(reviews.rating) as ratings_average'),
                DB::raw('count(*) as total')
            ])
            ->orderBy('total', 'desc')
            ->take(6)
            ->get();



        $top_customer = Order::with(['customer'])
            ->select('customer_id', DB::raw('COUNT(customer_id) as count'))
            ->groupBy('customer_id')
            ->orderBy("count", 'desc')
            ->take(6)
            ->get();

        $top_store_by_order_received = Order::where('seller_is', 'seller')
            ->select('seller_id', DB::raw('COUNT(id) as count'))
            ->groupBy('seller_id')
            ->orderBy("count", 'desc')
            ->take(6)
            ->get();

        $data = self::order_stats_data();
        $data['customer'] = User::count();

        $data['product'] = Product::count();
        $data['order'] = Order::count();
        $data['brand'] = Brand::count();

        $data['top_sell'] = $top_sell;
        $data['most_rated_products'] = $most_rated_products;

        $data['top_customer'] = $top_customer;
        $data['top_store_by_order_received'] = $top_store_by_order_received;

        $admin_wallet = AdminWallet::where('admin_id', 1)->first();
        $data['inhouse_earning'] = $admin_wallet != null ? $admin_wallet->inhouse_earning : 0;
        $data['commission_earned'] = $admin_wallet != null ? $admin_wallet->commission_earned : 0;
        $data['delivery_charge_earned'] = $admin_wallet != null ? $admin_wallet->delivery_charge_earned : 0;
        $data['pending_amount'] = $admin_wallet != null ? $admin_wallet->pending_amount : 0;
        $data['total_tax_collected'] = $admin_wallet != null ? $admin_wallet->total_tax_collected : 0;


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
            'filter',
            'results',
            'data'
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
        $from = $request->from_date . " 00:00:00";
        $to   = $request->to_date . " 23:59:59";

        // Fetch data
        $results = Order::selectRaw("
                COUNT(*) as total_orders,
                SUM(order_amount) as total_amount,
                SUM(CASE WHEN order_status='pending' THEN 1 END) as pending_qty,
                SUM(CASE WHEN order_status='pending' THEN order_amount END) as pending_amount,
                SUM(CASE WHEN order_status='confirmed' THEN 1 END) as confirmed_qty,
                SUM(CASE WHEN order_status='confirmed' THEN order_amount END) as confirmed_amount,
                SUM(CASE WHEN order_status='canceled' THEN 1 END) as canceled_qty,
                SUM(CASE WHEN order_status='canceled' THEN order_amount END) as canceled_amount
        ")
            ->whereBetween('created_at', [$from, $to])
            ->first();

        $results->total_amount        = $results->total_amount ?? 0;
        $results->pending_amount      = $results->pending_amount ?? 0;
        $results->confirmed_amount    = $results->confirmed_amount ?? 0;
        $results->canceled_amount     = $results->canceled_amount ?? 0;

        // Return the updated card HTML
        $html = view('admin.dashboard-order_report', compact('results'))->render();

        return response()->json([
            'view' => $html
        ]);
    }
    public function order_stats_data()
    {
        $today = session()->has('statistics_type') && session('statistics_type') == 'today' ? 1 : 0;
        $this_month = session()->has('statistics_type') && session('statistics_type') == 'this_month' ? 1 : 0;

        $pending = Order::where(['order_status' => 'pending'])
            ->when($today, function ($query) {
                return $query->whereDate('created_at', Carbon::today());
            })
            ->when($this_month, function ($query) {
                return $query->whereMonth('created_at', Carbon::now());
            })
            ->count();
        $confirmed = Order::where(['order_status' => 'confirmed'])
            ->when($today, function ($query) {
                return $query->whereDate('created_at', Carbon::today());
            })
            ->when($this_month, function ($query) {
                return $query->whereMonth('created_at', Carbon::now());
            })
            ->count();
        $processing = Order::where(['order_status' => 'processing'])
            ->when($today, function ($query) {
                return $query->whereDate('created_at', Carbon::today());
            })
            ->when($this_month, function ($query) {
                return $query->whereMonth('created_at', Carbon::now());
            })
            ->count();
        $out_for_delivery = Order::where(['order_status' => 'out_for_delivery'])
            ->when($today, function ($query) {
                return $query->whereDate('created_at', Carbon::today());
            })
            ->when($this_month, function ($query) {
                return $query->whereMonth('created_at', Carbon::now());
            })
            ->count();
        $delivered = Order::where(['order_status' => 'delivered'])
            ->where('order_type', 'default_type')
            ->when($today, function ($query) {
                return $query->whereDate('created_at', Carbon::today());
            })
            ->when($this_month, function ($query) {
                return $query->whereMonth('created_at', Carbon::now());
            })
            ->count();
        $canceled = Order::where(['order_status' => 'canceled'])
            ->when($today, function ($query) {
                return $query->whereDate('created_at', Carbon::today());
            })
            ->when($this_month, function ($query) {
                return $query->whereMonth('created_at', Carbon::now());
            })
            ->count();
        $returned = Order::where(['order_status' => 'returned'])
            ->when($today, function ($query) {
                return $query->whereDate('created_at', Carbon::today());
            })
            ->when($this_month, function ($query) {
                return $query->whereMonth('created_at', Carbon::now());
            })
            ->count();
        $failed = Order::where(['order_status' => 'failed'])
            ->when($today, function ($query) {
                return $query->whereDate('created_at', Carbon::today());
            })
            ->when($this_month, function ($query) {
                return $query->whereMonth('created_at', Carbon::now());
            })
            ->count();

        $data = [
            'pending' => $pending,
            'confirmed' => $confirmed,
            'processing' => $processing,
            'out_for_delivery' => $out_for_delivery,
            'delivered' => $delivered,
            'canceled' => $canceled,
            'returned' => $returned,
            'failed' => $failed
        ];

        return $data;
    }
    public function order_stats(Request $request)
    {
        session()->put('statistics_type', $request['statistics_type']);
        $data = self::order_stats_data();

        return response()->json([
            'view' => view('admin.dashboard-order-stats', compact('data'))->render()
        ], 200);
    }

    public function variantProducts()
    {
        // no need
        $products = Product::where('status', 1)->get();

        $data = [];

        foreach ($products as $product) {

            $productColors = is_array($product->colors)
                ? $product->colors
                : json_decode($product->colors ?? '[]', true);

            if (!empty($productColors)) {
                $data[] = [
                    'product_name' => $product->name,
                    'product_code' => $product->code,
                ];
            }
        }

        return $data;
    }
}
