<?php

namespace App\Services;

use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class TopSellingData
{
    public static function topSellingProductsData($request)
    {
        // ✅ Filter receive
        $filter     = $request->filter ?? 'this_month';
        $startDate  = $request->from_date;
        $endDate    = $request->to_date;

        // ✅ Date logic (your function reused)
        if ($filter === 'custom_range' && !empty($startDate) && !empty($endDate)) {
            $startDate = Carbon::parse($startDate)->startOfDay();
            $endDate   = Carbon::parse($endDate)->endOfDay();
        } else {
            switch ($filter) {
                case 'today':
                    $startDate = Carbon::today()->startOfDay();
                    $endDate   = Carbon::today()->endOfDay();
                    break;

                case 'yesterday':
                    $startDate = Carbon::yesterday()->startOfDay();
                    $endDate   = Carbon::yesterday()->endOfDay();
                    break;

                case 'last_week':
                    $startDate = Carbon::now()->subWeek()->startOfWeek();
                    $endDate   = Carbon::now()->subWeek()->endOfWeek();
                    break;

                case 'last_month':
                    $startDate = Carbon::now()->subMonth()->startOfMonth();
                    $endDate   = Carbon::now()->subMonth()->endOfMonth();
                    break;

                case 'this_year':
                    $startDate = Carbon::now()->startOfYear();
                    $endDate   = Carbon::now()->endOfYear();
                    break;

                case 'this_month':
                default:
                    $startDate = Carbon::now()->startOfMonth();
                    $endDate   = Carbon::now()->endOfMonth();
                    break;
            }
        }

        // ✅ Main Query (NO get())
        $query = DB::table('order_details')
            ->join('products', 'products.id', '=', 'order_details.product_id')
            ->join('orders', 'orders.id', '=', 'order_details.order_id')
            ->where('orders.order_status', 'confirmed')
            ->whereBetween('orders.created_at', [$startDate, $endDate])
            ->select([
                'products.id',
                'products.name',
                'products.code',
                'products.unit_price',
                'products.thumbnail',
                DB::raw('SUM(order_details.qty) as total_qty'),
                DB::raw('SUM(order_details.price * order_details.qty) as total_amount')
            ])
            ->groupBy(
                'products.id',
                'products.name',
                'products.code',
                'products.unit_price',
                'products.thumbnail'
            )->orderByDesc(DB::raw('SUM(order_details.qty)'));

        // ✅ Max Qty (for progress)
        $maxQty = DB::table('order_details')
            ->join('products', 'products.id', '=', 'order_details.product_id')
            ->join('orders', 'orders.id', '=', 'order_details.order_id')
            ->where('orders.order_status', 'confirmed')
            ->whereBetween('orders.created_at', [$startDate, $endDate])
            ->select(DB::raw('SUM(order_details.qty) as total_qty'))
            ->groupBy('products.id')
            ->pluck('total_qty')
            ->max() ?? 1;

        // ✅ DataTable Response
        return DataTables::of($query)

            ->addIndexColumn()

            ->addColumn('thumbnail', function ($row) {
                $img = $row->thumbnail
                    ? asset('assets/storage/product/thumbnail/' . $row->thumbnail)
                    : asset('assets/admin/img/160x160/img1.jpg');

                return '<img src="' . $img . '" style="width:60px">';
            })

            ->addColumn('name', function ($row) {
                return '<a href="' . route('admin.product.show', $row->id) . '">' . $row->name . '</a>';
            })

            ->addColumn('price', function ($row) {
                return number_format($row->unit_price, 2);
            })

            ->addColumn('qty', function ($row) {
                return '<strong>' . $row->total_qty . '</strong><br><small>units</small>';
            })

            ->addColumn('orders', function ($row) {
                return '-'; // optional: add order count later
            })

            ->addColumn('sales', function ($row) {
                return number_format($row->total_amount, 2);
            })

            ->addColumn('percentage', function ($row) use ($maxQty) {

                $percentage = ($row->total_qty / $maxQty) * 100;

                return '
                <div class="progress" style="height:10px;">
                    <div class="progress-bar bg-success progress-bar-striped progress-bar-animated" style="width:' . $percentage . '%"></div>
                </div>
                <small>' . round($percentage, 2) . '%</small>
            ';
            })

            ->addColumn('action', function ($row) {
                return '
                <a href="' . route('admin.product.show', $row->id) . '" class="btn btn-sm btn-primary">
                    <i class="la la-eye"></i>
                </a>
                <a href="' . route('admin.product.edit', $row->id) . '" class="btn btn-sm btn-info">
                    <i class="la la-edit"></i>
                </a>
            ';
            })

            ->rawColumns(['thumbnail', 'name', 'qty', 'percentage', 'action'])

            ->orderColumn('total_qty', function ($query, $order) {
                $query->orderBy(DB::raw('SUM(order_details.qty)'), $order);
            })

            ->make(true);
    }
}
