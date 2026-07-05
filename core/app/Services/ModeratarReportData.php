<?php

namespace App\Services;

use App\Exports\DataExport;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class ModeratarReportData
{
    public static function getModeratarReportData($request)
    {
        $from = $request->from ?? now()->format('Y-m-d');
        $to = $request->to ?? now()->format('Y-m-d');

        // Fetch orders grouped by moderator
        $query = Order::selectRaw('
                moderator_id,
                COUNT(*) as invoice_qty,
                SUM(total_products) as total_products_qty,
                SUM(total_amount) as sale_amount,
                SUM(discount) as provided_discount
            ')
            ->whereBetween('created_at', [$from . ' 00:00:00', $to . ' 23:59:59'])
            ->groupBy('moderator_id');

        return DataTables::of($query)
            ->addColumn('name', function ($row) {
                $moderator = User::find($row->moderator_id);
                return $moderator ? $moderator->name : 'Unknown';
            })
            ->editColumn('sale_amount', function ($row) {
                return number_format($row->sale_amount);
            })
            ->editColumn('provided_discount', function ($row) {
                return number_format($row->provided_discount);
            })
            ->make(true);
    }

    public static function getExportModeratarReport($request)
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

        $filename = 'Products_Report_' . $request->from_date . '_to_' . $request->to_date . '.xlsx';

        return Excel::download(new DataExport($headings, $data), $filename);
    }
}
