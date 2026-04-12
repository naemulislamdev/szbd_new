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
        [$from_date, $to_date] = self::getDateRange($request);

        $statuses = $request->order_status ?? ['confirmed'];

        $query = DB::table('order_details')
            ->join('orders', 'orders.id', '=', 'order_details.order_id')
            ->join('products', 'products.id', '=', 'order_details.product_id')
            ->select(
                'products.thumbnail',
                'products.name',
                'products.code',
                'products.choice_options',
                'products.color_variant',
                'order_details.variation',
                DB::raw('SUM(order_details.qty) as total_qty'),
                DB::raw('SUM(order_details.price * order_details.qty) as total_amount')
            )
            ->whereBetween('orders.created_at', [$from_date, $to_date])
            ->when(!empty($statuses), function ($q) use ($statuses) {
                $q->whereIn('orders.order_status', $statuses);
            })
            ->groupBy(
                'products.thumbnail',
                'products.name',
                'products.code',
                'products.choice_options',
                'products.color_variant',
                'order_details.variation'
            );

        return DataTables::of($query)
            ->addIndexColumn()

            ->editColumn('thumbnail', function ($row) {
                return '<img src="' . asset('assets/storage/product/thumbnail/' . $row->thumbnail) . '" width="50">';
            })

            ->editColumn('variation', function ($row) {

                $variation = json_decode($row->variation, true);

                // ✅ ensure array
                if (!is_array($variation)) {
                    return 'N/A';
                }

                $output = [];

                // Size
                if (!empty($variation['Size'])) {
                    $output[] = 'Size: ' . $variation['Size'];
                }

                // Color
                if (!empty($variation['color'])) {
                    $output[] = 'Color: ' . $variation['color'];
                }

                return implode('<br>', $output);
            })

            ->editColumn('total_amount', function ($row) {
                return number_format($row->total_amount, 2);
            })

            ->rawColumns(['thumbnail'])
            ->make(true);
    }

    public static function getDailySalesSummary($request)
    {
        $statuses = $request->order_status ?? ['confirmed'];

        $todaySales = self::getSalesAmount(
            Carbon::today()->startOfDay(),
            Carbon::today()->endOfDay(),
            $statuses
        );

        $yesterdaySales = self::getSalesAmount(
            Carbon::yesterday()->startOfDay(),
            Carbon::yesterday()->endOfDay(),
            $statuses
        );

        $last7DaysSales = self::getSalesAmount(
            Carbon::now()->subDays(6)->startOfDay(),
            Carbon::today()->endOfDay(),
            $statuses
        );

        $monthlySales = self::getSalesAmount(
            Carbon::now()->startOfMonth(),
            Carbon::now()->endOfMonth(),
            $statuses
        );

        return response()->json([
            'today_sales' => number_format($todaySales, 2),
            'yesterday_sales' => number_format($yesterdaySales, 2),
            'last_7_days_sales' => number_format($last7DaysSales, 2),
            'monthly_sales' => number_format($monthlySales, 2),
        ]);
    }
    public static function getDateRange($request)
    {
        $reportType = $request->report_type ?? 'today';

        switch ($reportType) {
            case 'today':
                $from_date = Carbon::today()->startOfDay();
                $to_date = Carbon::today()->endOfDay();
                break;

            case 'yesterday':
                $from_date = Carbon::yesterday()->startOfDay();
                $to_date = Carbon::yesterday()->endOfDay();
                break;

            case 'last_7_days':
                $from_date = Carbon::now()->subDays(6)->startOfDay();
                $to_date = Carbon::today()->endOfDay();
                break;

            case 'monthly':
                $from_date = Carbon::now()->startOfMonth();
                $to_date = Carbon::now()->endOfMonth();
                break;

            case 'custom':
                $from_date = $request->from_date
                    ? Carbon::parse($request->from_date)->startOfDay()
                    : Carbon::today()->startOfDay();

                $to_date = $request->to_date
                    ? Carbon::parse($request->to_date)->endOfDay()
                    : Carbon::today()->endOfDay();
                break;

            default:
                $from_date = Carbon::today()->startOfDay();
                $to_date = Carbon::today()->endOfDay();
                break;
        }

        return [$from_date, $to_date];
    }

    public static function getSalesAmount($from, $to, $statuses = [])
    {
        return DB::table('order_details')
            ->join('orders', 'orders.id', '=', 'order_details.order_id')
            ->whereBetween('orders.created_at', [$from, $to])
            ->when(!empty($statuses), function ($q) use ($statuses) {
                $q->whereIn('orders.order_status', $statuses);
            })
            ->sum(DB::raw('order_details.price * order_details.qty'));
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
