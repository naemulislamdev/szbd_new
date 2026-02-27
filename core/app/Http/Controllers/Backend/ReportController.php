<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Order;
use App\Services\DailySalesData;
use App\Services\ModeratarReportData;
use App\Services\ProductReportData;
use App\Services\TopSellingData;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    //daily sales peport, product report, moderator report, profit report, sealse filter, customer report,
    public function dailySales()
    {
        return view('admin.reports.daily_sales');
    }
    public function dailySalesData(Request $request)
    {
        return DailySalesData::getDailySalesData($request);
    }
    public function dailySalesExport(Request $request)
    {
        $request->validate([
            'from_date' => 'required|date',
            'to_date'   => 'required|date|after_or_equal:from_date',
        ]);

        return DailySalesData::getExportDailySales($request);
    }
    // Products Reports
    public function productReport()
    {
        return view('admin.reports.products_report');
    }
    public function productReportData(Request $request)
    {
        return ProductReportData::getProductsReportData($request);
    }
    public function productReportExport(Request $request)
    {
        $request->validate([
            'from_date' => 'required|date',
            'to_date'   => 'required|date|after_or_equal:from_date',
        ]);

        return ProductReportData::getExportProductsReport($request);
    }
    // Moderatar Reports
    public function moderatarReport()
    {
        return view('admin.reports.products_report');
    }
    public function moderatarReportData(Request $request)
    {
        return ModeratarReportData::getModeratarReportData($request);
    }
    public function moderatarReportExport(Request $request)
    {
        $request->validate([
            'from_date' => 'required|date',
            'to_date'   => 'required|date|after_or_equal:from_date',
        ]);

        return ModeratarReportData::getExportModeratarReport($request);
    }
    // Top Selling Product Reorts
    public function topSellingReport(Request $request)
    {
        $data = TopSellingData::topSellingProductsData($request);

        return view('admin.reports.top_selling_products', $data);
    }

    public function moderationIdUpdate()
    {
        Order::whereNotNull('multiple_note')
            ->chunk(200, function ($orders) {

                foreach ($orders as $order) {

                    $multiNote = json_decode($order->multiple_note, true);

                    if (!is_array($multiNote) || empty($multiNote)) {
                        continue;
                    }

                    // Get last note safely
                    $lastNote = collect($multiNote)->last();

                    if (!isset($lastNote['user'])) {
                        continue;
                    }

                    $employee = Admin::where('name', $lastNote['user'])->first();

                    if ($employee) {
                        $order->update([
                            'moderatar_id' => $employee->id, // confirm column name
                        ]);
                    }
                }
            });
    }
}
