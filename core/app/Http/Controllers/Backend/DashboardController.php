<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class DashboardController extends Controller
{
    public function dashboard()
    {
        $user= auth('admin')->user();
        // $role = Role::where('id', $user->admin_role_id)->first();
        // dd($user->getRoleNames());
        // dd($role->guard_name);
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
