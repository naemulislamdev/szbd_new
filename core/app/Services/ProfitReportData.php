<?php

namespace App\Services;

use App\Exports\DataExport;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class ProfitReportData
{
    public static function getProfitData($request)
    {
        $from_date = $request->from_date
            ? Carbon::parse($request->from_date)->startOfDay()
            : Carbon::today()->startOfDay();

        $to_date = $request->to_date
            ? Carbon::parse($request->to_date)->endOfDay()
            : Carbon::today()->endOfDay();

        $query = Product::leftJoin('order_details', 'order_details.product_id', '=', 'products.id')
            ->leftJoin('orders', 'orders.id', '=', 'order_details.order_id')
            ->select(
                'products.thumbnail',
                'products.name',
                'products.code',
                'products.unit_price',
                'products.purchase_price',
                'products.choice_options',
                'products.color_variant',

                //Sales Qty (confirmed only)
                DB::raw('COALESCE(SUM(
            CASE
                WHEN orders.order_status = "confirmed" THEN order_details.qty
                ELSE 0
            END
        ),0) as sales_qty'),

                //Profit (confirmed only)
                DB::raw('COALESCE(SUM(
            CASE
                WHEN orders.order_status = "confirmed"
                THEN (order_details.price - products.purchase_price) * order_details.qty
                ELSE 0
            END
        ),0) as profit')
            )->whereBetween('orders.created_at', [$from_date, $to_date])
            ->groupBy(
                'products.thumbnail',
                'products.name',
                'products.code',
                'products.unit_price',
                'products.purchase_price',
                'products.choice_options',
                'products.color_variant'
            );

        return DataTables::of($query)
            ->addIndexColumn()

            ->editColumn('thumbnail', function ($row) {
                return '<img src="' . asset('assets/storage/product/thumbnail/' . $row->thumbnail) . '" width="45">';
            })

            // ✅ FIXED VARIATION
            ->editColumn('variation', function ($row) {

                $colorVariants = is_array($row->color_variant)
                    ? $row->color_variant
                    : json_decode($row->color_variant ?? '[]', true);
                $choiceOptions = is_array($row->choice_options)
                    ? $row->choice_options
                    : json_decode($row->choice_options ?? '[]', true);

                $sizes = [];
                $colors = [];

                // ---- Size ----
                if (!empty($row->choice_options)) {

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
                }

                $sizeText = !empty($sizes)
                    ? 'Size: ' . implode(', ', $sizes)
                    : 'Size: Free';

                // ---- Color ----
                if (!empty($row->color_variant)) {

                    if ($colorVariants) {
                        foreach ($colorVariants as $color) {
                            if (!empty($color['color'])) {
                                $colors[] = trim($color['color']);
                            }
                        }
                    }
                }

                $colorText = !empty($colors)
                    ? '<br><small class="text-muted">Color: ' . implode(', ', $colors) . '</small>'
                    : '';

                return $sizeText . $colorText;
            })

            ->editColumn('profit', function ($row) {
                return '৳ '. number_format($row->profit, 2);
            })

            ->rawColumns(['thumbnail'])
            ->make(true);
    }
    public static function getExportProfitReport($request)
    {
        $from_date = Carbon::parse($request->from_date)->startOfDay();
        $to_date   = Carbon::parse($request->to_date)->endOfDay();

        $profitReports = Product::leftJoin('order_details', 'order_details.product_id', '=', 'products.id')
            ->leftJoin('orders', 'orders.id', '=', 'order_details.order_id')
            ->select(
                'products.name',
                'products.code',
                'products.unit_price',
                'products.purchase_price',
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
            ),0) as profit')
            )->whereBetween('orders.created_at', [$from_date, $to_date])
            ->groupBy(
                'products.name',
                'products.code',
                'products.unit_price',
                'products.purchase_price',
                'products.choice_options',
                'products.color_variant'
            )
            ->get();

        /** ---------- Build Excel Rows ---------- */
        $data = $profitReports->map(function ($item) {

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
                $item->purchase_price,
                $item->unit_price,
                $item->sales_qty,
                $item->profit,
            ];
        })->toArray();

        $headings = [
            'Product Name',
            'Product Code',
            'Attributes',
            'Purchase Price',
            'Selling Price',
            'Sales Qty',
            'Profit',
        ];

        $filename = 'Profit_Report_' . $request->from_date . '_to_' . $request->to_date . '.xlsx';

        return Excel::download(
            new DataExport($headings, $data),
            $filename
        );
    }
}
