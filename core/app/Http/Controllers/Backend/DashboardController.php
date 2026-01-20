<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function dashboard()
    {
        return view('admin.dashboard');
    }

    public function order_stats(Request $request)
    {
        // Logic for order stats
    }

    public function business_overview(Request $request)
    {
        // Logic for business overview
    }

    public function OrderReportFilter(Request $request)
    {
        // Logic for order report filter
    }
}
