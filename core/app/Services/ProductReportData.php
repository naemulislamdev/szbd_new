<?php

namespace App\Services;

use App\Exports\DataExport;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class ProductReportData
{
    public static function getProductsReportData($request)
    {
        [$from_date, $to_date] = self::getProductReportDateRange($request);

        $from_date_sql = $from_date->format('Y-m-d H:i:s');
        $to_date_sql   = $to_date->format('Y-m-d H:i:s');

        $statuses = $request->order_status ?? [];

        $query = Product::leftJoin('order_details', 'order_details.product_id', '=', 'products.id')
            ->leftJoin('orders', 'orders.id', '=', 'order_details.order_id')
            ->select(
                'products.id',
                'products.thumbnail',
                'products.name',
                'products.code',
                'products.unit_price',
                'products.purchase_price',
                'products.current_stock',
                'products.choice_options',
                'products.color_variant',
                DB::raw('COALESCE(order_details.variation, "N/A") as variation'),

                DB::raw("
                    COALESCE(SUM(
                        CASE
                            WHEN orders.created_at BETWEEN '{$from_date_sql}' AND '{$to_date_sql}'
                            AND orders.order_status = 'confirmed'
                            THEN order_details.qty
                            ELSE 0
                        END
                    ), 0) as sales_qty
                "),

                DB::raw("
                    COALESCE(SUM(
                        CASE
                            WHEN orders.created_at BETWEEN '{$from_date_sql}' AND '{$to_date_sql}'
                            AND orders.order_status = 'confirmed'
                            THEN (order_details.price - products.purchase_price) * order_details.qty
                            ELSE 0
                        END
                    ), 0) as profit
                "),

                DB::raw("
                    COALESCE(SUM(
                        CASE
                            WHEN orders.created_at BETWEEN '{$from_date_sql}' AND '{$to_date_sql}'
                            AND orders.order_status = 'returned'
                            THEN order_details.qty
                            ELSE 0
                        END
                    ), 0) as return_qty
                ")
            )

            // selected status wise row filtering
            ->when(!empty($statuses), function ($q) use ($statuses, $from_date, $to_date) {
                $q->where(function ($sub) use ($statuses, $from_date, $to_date) {
                    $sub->whereExists(function ($exists) use ($statuses, $from_date, $to_date) {
                        $exists->select(DB::raw(1))
                            ->from('order_details as od2')
                            ->join('orders as o2', 'o2.id', '=', 'od2.order_id')
                            ->whereColumn('od2.product_id', 'products.id')
                            ->whereBetween('o2.created_at', [$from_date, $to_date])
                            ->whereIn('o2.order_status', $statuses);
                    });
                });
            })

            ->groupBy(
                'products.id',
                'products.thumbnail',
                'products.name',
                'products.code',
                'products.unit_price',
                'products.purchase_price',
                'products.current_stock',
                'products.choice_options',
                'products.color_variant',
                'order_details.variation'
            );

        return DataTables::of($query)
            ->addIndexColumn()

            ->editColumn('thumbnail', function ($row) {
                return '<img src="' . asset('assets/storage/product/thumbnail/' . $row->thumbnail) . '" width="45">';
            })

            ->editColumn('variation', function ($row) {
                $colorVariants = is_array($row->color_variant)
                    ? $row->color_variant
                    : json_decode($row->color_variant ?? '[]', true);

                $choiceOptions = is_array($row->choice_options)
                    ? $row->choice_options
                    : json_decode($row->choice_options ?? '[]', true);

                $sizes = [];
                $colors = [];

                if (!empty($choiceOptions)) {
                    foreach ($choiceOptions as $choice) {
                        if (
                            isset($choice['title']) &&
                            strtolower($choice['title']) === 'size' &&
                            !empty($choice['options'])
                        ) {
                            $sizes = array_map('trim', $choice['options']);
                        }
                    }
                }

                $sizeText = !empty($sizes)
                    ? 'Size: ' . implode(', ', $sizes)
                    : 'Size: Free';

                if (!empty($colorVariants)) {
                    foreach ($colorVariants as $color) {
                        if (!empty($color['color'])) {
                            $colors[] = trim($color['color']);
                        }
                    }
                }

                $colorText = !empty($colors)
                    ? '<br><small class="text-muted">Color: ' . implode(', ', $colors) . '</small>'
                    : '';

                return $sizeText . $colorText;
            })

            ->editColumn('purchase_price', function ($row) {
                return '৳ ' . number_format($row->purchase_price, 2);
            })

            ->editColumn('unit_price', function ($row) {
                return '৳ ' . number_format($row->unit_price, 2);
            })

            ->editColumn('profit', function ($row) {
                return '৳ ' . number_format($row->profit, 2);
            })

            ->rawColumns(['thumbnail', 'variation'])
            ->make(true);
    }

    public static function getProductReportSummary($request)
    {
        $statuses = $request->order_status ?? [];
        //dd($statuses);

        $today_sales = self::getConfirmedSalesAmount(
            Carbon::today()->startOfDay(),
            Carbon::today()->endOfDay(),
            $statuses
        );

        $yesterday_sales = self::getConfirmedSalesAmount(
            Carbon::yesterday()->startOfDay(),
            Carbon::yesterday()->endOfDay(),
            $statuses
        );

        $last_7_days_sales = self::getConfirmedSalesAmount(
            Carbon::now()->subDays(6)->startOfDay(),
            Carbon::today()->endOfDay(),
            $statuses
        );

        $monthly_sales = self::getConfirmedSalesAmount(
            Carbon::now()->startOfMonth(),
            Carbon::now()->endOfMonth(),
            $statuses
        );

        [$from_date, $to_date] = self::getProductReportDateRange($request);

        $topSellingProducts = DB::table('order_details')
            ->join('orders', 'orders.id', '=', 'order_details.order_id')
            ->join('products', 'products.id', '=', 'order_details.product_id')
            ->select(
                'products.name',
                'products.code',
                DB::raw('SUM(order_details.qty) as total_qty'),
                DB::raw('SUM(order_details.price * order_details.qty) as total_amount')
            )
            ->whereBetween('orders.created_at', [$from_date, $to_date])
            ->where('orders.order_status', 'confirmed')
            ->when(!empty($statuses), function ($q) use ($statuses) {
                // confirmed না থাকলে empty আসবে, এটাই expected
                $q->whereIn('orders.order_status', $statuses);
            })
            ->groupBy('products.name', 'products.code')
            ->orderByDesc('total_qty')
            ->limit(5)
            ->get()
            ->map(function ($item) {
                $item->total_amount = number_format($item->total_amount, 2);
                return $item;
            });

        return response()->json([
            'today_sales' => number_format($today_sales, 2),
            'yesterday_sales' => number_format($yesterday_sales, 2),
            'last_7_days_sales' => number_format($last_7_days_sales, 2),
            'monthly_sales' => number_format($monthly_sales, 2),
            'top_selling_products' => $topSellingProducts,
        ]);
    }

    public static function getProductReportDateRange($request)
    {
        $report_type = $request->report_type ?? 'today';

        switch ($report_type) {
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

    public static function getConfirmedSalesAmount($from, $to, $statuses = [])
    {
        return DB::table('order_details')
            ->join('orders', 'orders.id', '=', 'order_details.order_id')
            ->whereBetween('orders.created_at', [$from, $to])
            ->where('orders.order_status', 'confirmed')
            ->when(!empty($statuses), function ($q) use ($statuses) {
                $q->whereIn('orders.order_status', $statuses);
            })
            ->sum(DB::raw('order_details.price * order_details.qty'));
    }


    public static function getExportProductsReport($request)
    {
        $from_date = Carbon::parse($request->from_date)->startOfDay();
        $to_date   = Carbon::parse($request->to_date)->endOfDay();

        $productReports = Product::leftJoin('order_details', 'order_details.product_id', '=', 'products.id')
            ->leftJoin('orders', 'orders.id', '=', 'order_details.order_id')
            ->select(
                'products.name',
                'products.code',
                'products.unit_price',
                'products.purchase_price',
                'products.current_stock',
                'products.choice_options',
                'products.color_variant',

                // ✅ Sales Qty (confirmed + date)
                DB::raw('COALESCE(SUM(
                CASE
                    WHEN orders.order_status = "confirmed"
                    THEN order_details.qty
                    ELSE 0
                END
            ),0) as sales_qty'),

                // ✅ Profit (confirmed + date)
                DB::raw('COALESCE(SUM(
                CASE
                    WHEN orders.order_status = "confirmed"
                    THEN (order_details.price - products.purchase_price) * order_details.qty
                    ELSE 0
                END
            ),0) as profit'),

                // ✅ Returned Qty (date aware)
                DB::raw('COALESCE(SUM(
                CASE
                    WHEN orders.order_status = "returned"
                    THEN order_details.qty
                    ELSE 0
                END
            ),0) as return_qty')
            )->whereBetween('orders.created_at', [$from_date, $to_date])
            ->groupBy(
                'products.name',
                'products.code',
                'products.unit_price',
                'products.purchase_price',
                'products.current_stock',
                'products.choice_options',
                'products.color_variant'
            )
            ->get();

        /** ---------- Build Excel Rows ---------- */
        $data = $productReports->map(function ($item) {

            // ----- Attribute build (same logic as DataTable) -----
            $sizes = [];
            $colors = [];

            $colorVariants = is_array($item->color_variant)
                ? $item->color_variant
                : json_decode($item->color_variant ?? '[]', true);
            $choiceOptions = is_array($item->choice_options)
                ? $item->choice_options
                : json_decode($item->choice_options ?? '[]', true);

            if ($choiceOptions) {
                foreach ($choiceOptions as $choice) {
                    if (
                        isset($choice['title']) &&
                        strtolower($choice['title']) === 'size' &&
                        !empty($choice['options'])
                    ) {
                        $sizes = array_map('trim', $choice['options']);
                    }
                }
            }

            if ($colorVariants) {
                foreach ($colorVariants as $color) {
                    if (!empty($color['color'])) {
                        $colors[] = trim($color['color']);
                    }
                }
            }

            $attribute = !empty($sizes)
                ? 'Size: ' . implode(', ', $sizes)
                : 'Size: Free';

            if (!empty($colors)) {
                $attribute .= ' | Color: ' . implode(', ', $colors);
            }

            return [
                $item->name,
                $item->code,
                $attribute,
                $item->current_stock,
                $item->purchase_price,
                $item->unit_price,
                $item->sales_qty,
                $item->profit,
                $item->return_qty,
            ];
        })->toArray();

        $headings = [
            'Product Name',
            'Product Code',
            'Attributes',
            'Stock',
            'Purchase Price',
            'Selling Price',
            'Sales Qty',
            'Profit',
            'Returned Qty',
        ];

        $filename = 'Products_Report_' . $request->from_date . '_to_' . $request->to_date . '.xlsx';

        return Excel::download(
            new DataExport($headings, $data),
            $filename
        );
    }
}
