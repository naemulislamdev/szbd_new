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
        $from_date = $request->from_date
            ? Carbon::parse($request->from_date)->startOfDay()
            : Carbon::today()->startOfDay();

        $to_date = $request->to_date
            ? Carbon::parse($request->to_date)->endOfDay()
            : Carbon::today()->endOfDay();

        $query = VisitorLog::selectRaw('source, COUNT(*) as total')
            ->whereBetween('created_at', [$from_date, $to_date])
            ->groupBy('source');

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
