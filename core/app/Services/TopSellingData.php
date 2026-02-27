<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Facades\DB;

class TopSellingData
{
    public static function topSellingProductsData($request)
    {
        $productList = Product::where('status', 1)->get();
        $from = $request->from_date ? $request->from_date . " 00:00:00" : null;
        $to = $request->to_date ? $request->to_date . " 23:59:59" : null;

        // Simple query with only essential columns
        $query = DB::table('order_details as od')
            ->select([
                'p.id',
                'p.name',
                'p.code',
                'p.thumbnail',
                DB::raw('SUM(od.qty) as total_quantity'),
                DB::raw('SUM(od.price * od.qty) as total_sales'),
                DB::raw('COUNT(DISTINCT od.order_id) as order_count'),
            ])
            ->join('orders as o', 'od.order_id', '=', 'o.id')
            ->join('products as p', 'od.product_id', '=', 'p.id')
            ->where('p.status', 1);

        // Apply date range
        if ($from && $to) {
            $query->whereBetween('o.created_at', [$from, $to]);
        }

        // Apply basic filters
        if ($request->filled('category_id')) {
            $query->where('p.category_id', $request->category_id);
        }

        if ($request->filled('product_id')) {
            $query->where('p.id', $request->product_id);
        }


        // Group and order
        $query->groupBy('p.id', 'p.name', 'p.code')
            ->orderByDesc('total_quantity')
            ->limit($request->limit ?? 20);

        $results = $query->get();
        $total_sales_all = $results->sum('total_sales');

        $data = [
            'topSellingProducts' => $results,
            'from' => $from,
            'to' => $to,
            'total_sales_all' => $total_sales_all,
            'productList' => $productList,
            'limit' => $request->limit ?? 20,
            'selectedProductId' => $request->product_id,
        ];

        return $data;
    }
}
