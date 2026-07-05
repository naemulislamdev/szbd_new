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
        // Track type wise date
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

            default: // today
                $from_date = Carbon::today()->startOfDay();
                $to_date = Carbon::today()->endOfDay();
                break;
        }

        $query = VisitorLog::selectRaw('source, COUNT(*) as total')
            ->whereBetween('created_at', [$from_date, $to_date]);

        // ✅ Track Status Filter (multi select)
        if (!empty($request->track_status)) {
            $query->whereIn('source', $request->track_status);
        }

        $query->groupBy('source');

        return DataTables::of($query)
            ->addIndexColumn()
            ->editColumn('source', function ($row) {
                return match ($row->source) {
                    'facebook_search' => 'Facebook Search',
                    'facebook_ads' => 'Facebook Ads',
                    'google_search' => 'Google Search',
                    'google_ads' => 'Google Ads',
                    default => 'Direct',
                };
            })
            ->editColumn('total', function ($row) {
                return '<span class="badge bg-primary">' . $row->total . '</span>';
            })
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
