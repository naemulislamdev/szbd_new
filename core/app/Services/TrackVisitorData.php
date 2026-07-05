<?php

namespace App\Services;

use App\Exports\DataExport;
use App\Models\Product;
use App\Models\VisitorLog;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class TrackVisitorData
{
   public static function getVisitorData($request)
{
    // ✅ Date range by track type
    switch ($request->track_type) {

        case 'yesterday':
            $from_date = Carbon::yesterday()->startOfDay();
            $to_date = Carbon::yesterday()->endOfDay();
            break;

        case 'last_7_days':
            $from_date = Carbon::now()->subDays(7)->startOfDay();
            $to_date = Carbon::now()->endOfDay();
            break;

        case 'monthly':
            $from_date = Carbon::now()->startOfMonth();
            $to_date = Carbon::now()->endOfMonth();
            break;

        case 'yearly':
            $from_date = Carbon::now()->startOfYear();
            $to_date = Carbon::now()->endOfYear();
            break;

        case 'custom':
            $from_date = Carbon::parse($request->from_date)->startOfDay();
            $to_date = Carbon::parse($request->to_date)->endOfDay();
            break;

        default:
            $from_date = Carbon::today()->startOfDay();
            $to_date = Carbon::today()->endOfDay();
            break;
    }

    // ✅ All sources (fixed list)
    $sources = [
        'direct' => 'Direct',
        'facebook_ads' => 'Facebook Ads',
        'facebook_search' => 'Facebook Search',
        'google_search' => 'Google Search',
        'google_ads' => 'Google Ads',
    ];

    // ✅ Get actual data from DB
    $query = VisitorLog::selectRaw('source, COUNT(*) as total')
        ->whereBetween('created_at', [$from_date, $to_date])
        ->groupBy('source')
        ->pluck('total', 'source'); // key-value

    $data = [];
    $selectedStatuses = $request->track_status ?? [];

    foreach ($sources as $key => $label) {

        $total = $query[$key] ?? 0;

        // ✅ If filter applied
        if (!empty($selectedStatuses)) {

            if (in_array($key, $selectedStatuses)) {
                $finalTotal = $total;
            } else {
                $finalTotal = 0;
            }

        } else {
            // ✅ No filter → show real data
            $finalTotal = $total;
        }

        $data[] = [
            'source' => $label,
            'total' => '<span class="badge bg-primary">'.$finalTotal.'</span>',
        ];
    }

    return DataTables::of($data)
        ->addIndexColumn()
        ->rawColumns(['total'])
        ->make(true);
}
    public static function getExportVisitorReport($request)
    {
        $from_date = Carbon::parse($request->from_date)->startOfDay();
        $to_date   = Carbon::parse($request->to_date)->endOfDay();

        $visitorReports = VisitorLog::selectRaw('source, COUNT(*) as total')
            ->whereBetween('created_at', [$from_date, $to_date])
            ->groupBy('source')
            ->get();

        /** ---------- Build Excel Rows ---------- */
        $data = $visitorReports->map(function ($item) {

            $sourceName = match ($item->source) {
                'facebook_search' => 'Facebook Search',
                'facebook_ads'    => 'Facebook Ads',
                'google_search'   => 'Google Search',
                'google_ads'      => 'Google Ads',
                default           => 'Direct',
            };

            return [
                $sourceName,
                $item->total,
            ];
        })->toArray();

        $headings = [
            'Source',
            'Total Visitor',
        ];

        $filename = 'visitors_report_' . $request->from_date . '_to_' . $request->to_date . '.xlsx';

        return Excel::download(
            new DataExport($headings, $data),
            $filename
        );
    }
}
