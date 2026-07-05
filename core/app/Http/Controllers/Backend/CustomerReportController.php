<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use App\Models\User;
use App\Services\LoyaltyService;
use App\Exports\CustomerLoyaltyExport;
use Maatwebsite\Excel\Facades\Excel;

class CustomerReportController extends Controller
{
    public function __construct(protected LoyaltyService $loyaltyService) {}
    public function list()
    {
        return view('admin.customers.report.list');
    }
    public function datatables(Request $request)
    {
        $orderStartDate = $request->input('order_start_date');
        $orderEndDate   = $request->input('order_end_date');

        $query = User::query()
            ->select([
                'id',
                'name',
                'f_name',
                'l_name',
                'email',
                'phone',
                'is_active',
                'loyalty_tier',   // ✅ নতুন
                DB::raw('(SELECT COUNT(*) FROM orders WHERE orders.customer_id = users.id) as total_order'),
                DB::raw('(SELECT COUNT(*) FROM orders WHERE orders.customer_id = users.id AND orders.order_status = "canceled") as total_cancelled'),
                DB::raw('(SELECT COUNT(*) FROM orders WHERE orders.customer_id = users.id AND orders.order_status = "confirmed") as total_confirmed'),
            ])
            ->latest('id');
        // ✅ Tier filter
        if ($request->filled('loyalty_tier')) {
            $query->where('loyalty_tier', $request->loyalty_tier);
        }
        if ($orderStartDate && $orderEndDate) {
            $query->whereExists(function ($q) use ($orderStartDate, $orderEndDate) {
                $q->select(DB::raw(1))
                    ->from('orders')
                    ->whereColumn('orders.customer_id', 'users.id')
                    ->whereBetween('orders.created_at', [$orderStartDate . ' 00:00:00', $orderEndDate . ' 23:59:59']);
            });
        }

        return DataTables::eloquent($query)
            ->addIndexColumn()
            ->editColumn('loyalty_tier', function ($row) {
                $colors = [
                    'VIP'    => 'bg-purple text-white',
                    'Gold'   => 'bg-warning text-dark',
                    'Silver' => 'bg-secondary text-white',
                    'Bronze' => 'bg-danger text-white',
                    'New'    => 'bg-info text-white',
                ];
                $tier = $row->loyalty_tier ?? 'New';
                $class = $colors[$tier] ?? 'bg-light text-dark';

                return '<span class="badge ' . $class . '">' . $tier . '</span>';
            })

            ->addColumn('name', function ($row) {

                // loyality tie
                $url = route('admin.customer.view', $row->id);

                if (!empty($row->name)) {
                    $displayName = $row->name;
                } elseif (!empty($row->f_name) || !empty($row->l_name)) {
                    $displayName = trim($row->f_name . ' ' . $row->l_name);
                } else {
                    $displayName = 'N/A';
                }

                return '<a href="' . $url . '">' . $displayName . '</a>';
            })

            ->addColumn('action', function ($row) {
                $url = route('admin.customer.view', $row->id);
                return '
                <a href="' . $url . '" class="btn btn-primary btn-sm">
                    <i class="la la-eye"></i>
                </a>
                <button class="btn btn-danger btn-sm delete"
                        style="cursor: pointer;"
                        title="Delete"
                        data-id="' . $row->id . '">
                    <i class="la la-trash"></i>
                </button>
            ';
            })

            ->editColumn('total_order', function ($row) {
                $count = $row->total_order ?? 0;
                return '<span class="badge bg-primary">' . $count . '</span>';
            })

            ->editColumn('total_cancelled', function ($row) {
                $count = $row->total_cancelled ?? 0;
                return '<span class="badge bg-danger">' . $count . '</span>';
            })

            ->editColumn('total_confirmed', function ($row) {
                $count = $row->total_confirmed ?? 0;
                return '<span class="badge bg-success">' . $count . '</span>';
            })

            ->editColumn('is_active', function ($row) {
                $checked = $row->is_active == 1 ? 'checked' : '';
                return '
                <div class="form-check form-switch">
                    <input class="form-check-input status"
                        type="checkbox"
                        name="colors_active"
                        data-id="' . $row->id . '"
                        value="1"
                        ' . $checked . '
                        id="flexSwitch' . $row->id . '">
                    <label class="form-check-label" for="flexSwitch' . $row->id . '"></label>
                </div>
            ';
            })

            ->rawColumns([
                'name',
                'action',
                'is_active',
                'total_order',
                'total_cancelled',
                'total_confirmed',
                'loyalty_tier'
            ])
            ->toJson();
    }
    public function report(Request $request)
    {
        $startDate = $request->input('start_date', now()->subDays(30)->format('Y-m-d'));
        $endDate   = $request->input('end_date', now()->format('Y-m-d'));

        return response()->json(
            $this->loyaltyService->getSummary($startDate, $endDate) +
                ['tier' => $this->loyaltyService->getTierDistribution()]
        );
    }
    public function export(Request $request)
    {
        $request->validate([
            'order_start_date' => 'nullable|date',
            'order_end_date'   => 'nullable|date',
        ]);

        return Excel::download(
            new CustomerLoyaltyExport(
                $request->input('loyalty_tier'),
                $request->input('order_start_date'),
                $request->input('order_end_date')
            ),
            'customer-loyalty-report-' . now()->format('Y-m-d') . '.xlsx'
        );
    }
}
