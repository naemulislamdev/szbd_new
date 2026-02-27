<?php

namespace App\Services;

use App\Exports\DataExport;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class DailySalesData
{
    public static function getDailySalesData($request)
    {
        $from_date = $request->from_date
            ? Carbon::parse($request->from_date)->startOfDay()
            : Carbon::today()->startOfDay();

        $to_date = $request->to_date
            ? Carbon::parse($request->to_date)->endOfDay()
            : Carbon::today()->endOfDay();

        $query = DB::table('order_details')
            ->join('orders', 'orders.id', '=', 'order_details.order_id')
            ->join('products', 'products.id', '=', 'order_details.product_id')
            ->select(
                'products.thumbnail',
                'products.name',
                'products.code',
                'order_details.variation',
                DB::raw('SUM(order_details.qty) as total_qty'),
                DB::raw('SUM(order_details.price * order_details.qty) as total_amount')
            )
            ->whereBetween('orders.created_at', [$from_date, $to_date])
            ->where('orders.order_status', 'confirmed')
            ->groupBy(
                'products.thumbnail',
                'products.name',
                'products.code',
                'order_details.variation'
            );

        return DataTables::of($query)
            ->addIndexColumn()

            ->editColumn('thumbnail', function ($row) {
                return '<img src="' . asset('assets/storage/product/thumbnail/' . $row->thumbnail) . '" width="50">';
            })

            ->editColumn('variation', function ($row) {
                return $row->variation ?? 'N/A';
            })

            ->editColumn('total_amount', function ($row) {
                return number_format($row->total_amount, 2);
            })

            ->rawColumns(['thumbnail'])
            ->make(true);
    }

    public static function getExportDailySales($request)
    {
        $from_date = Carbon::parse($request->from_date)->startOfDay();
        $to_date   = Carbon::parse($request->to_date)->endOfDay();

        $sales = DB::table('order_details')
            ->join('orders', 'orders.id', '=', 'order_details.order_id')
            ->join('products', 'products.id', '=', 'order_details.product_id')
            ->select(
                'products.name',
                'products.code',
                'order_details.variation',
                DB::raw('SUM(order_details.qty) as total_qty'),
                DB::raw('SUM(order_details.price * order_details.qty) as total_amount')
            )
            ->whereBetween('orders.created_at', [$from_date, $to_date])
            ->where('orders.order_status', 'confirmed')
            ->groupBy(
                'products.name',
                'products.code',
                'order_details.variation'
            )
            ->get();

        //dd($sales);

        $data = $sales->map(function ($item) {
            return [
                $item->name,
                $item->variation ?? 'N/A',
                $item->code,
                $item->total_qty,
                $item->total_amount,
            ];
        })->toArray();

        $headings = [
            'Product Name',
            'Attributes',
            'Product Code',
            'Total Qty',
            'Total Selling Amount',
        ];

        $filename = 'Daily_Sales_Report_' . $request->from_date . '_to_' . $request->to_date . '.xlsx';

        return Excel::download(new DataExport($headings, $data), $filename);
    }

}
