<?php

namespace App\Http\Controllers\Backend;

use App\CPU\Helpers;
use App\Http\Controllers\Controller;
use App\Models\Order;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use App\Model\Subscription;
use App\Model\BusinessSetting;
use App\Models\Customer;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class CustomerController extends Controller
{
    public function customer_list()
    {
        return view('admin.customers.index');
    }
    public function datatables()
    {
        $query = User::query();
        $query->latest('id');
        return DataTables::of($query)
            ->addIndexColumn()

            ->addColumn('name', function ($row) {
                $url = route('admin.customer.view', $row->id);
                return '<a href="' . $url . '">' . $row->f_name . ' ' . $row->l_name . '</a>';
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
            ->addColumn('total_order', function ($row) {
                return $row->order->count();
            })
            // Edit Column
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
            ])
            ->toJson();
    }

    public function status(Request $request)
    {
        $customer = User::find($request->id);
        $customer->is_active = $request->status;
        $saveinfo = $customer->save();
        DB::table('oauth_access_tokens')
            ->where('user_id', $request->id)
            ->delete();
        if ($saveinfo) {
            return response()->json([
                'success' => 1,
            ], 200);
        } else {
            return response()->json([
                'error' => 0,
            ], 500);
        }
    }

    public function view($id)
    {
        $customer = User::find($id);
        $totalOrder = Order::where("customer_id", $id)->get()->Count();
        if (isset($customer)) {
            return view('admin.customers.view', compact('customer', 'totalOrder'));
        }
    }
    public function customerDatatables($customer_id)
    {
        $query = Order::query();
        $query->latest('id')->where("customer_id", $customer_id);

        return DataTables::of($query)
            ->addIndexColumn()
            ->editColumn('id', function ($row) {
                $url = route('admin.order.details', $row->id);

                return '<a href="' . $url . '">' . $row->id . '</a>';
            })
            ->editColumn('created_at', function ($row) {
                return $row->created_at->format('d M Y h:i A');
            })
            ->editColumn('order_status', function ($row) {
                return ucfirst($row->order_status);
            })

            ->addColumn('action', function ($row) {
                $url1 = route('admin.order.generate-invoice', $row->id);
                $url2 = route('admin.order.details', $row->id);
                return '
                    <a href="' . $url2 . '" class="btn btn-info btn-sm">
                        <i class="la la-eye"></i>
                    </a>
                    <a href="' . $url1 . '" class="btn btn-primary btn-sm">
                        <i class="las la-arrow-circle-down"></i>
                    </a>
                ';
            })

            ->rawColumns([
                'id',
                'action',
                'created_at',
                'order_status'
            ])
            ->toJson();
    }
    public function delete(Request $request)
    {
        $customer = User::findOrFail($request->id);
        if ($customer->delete()) {
            return response()->json([
                'success' => 1,
            ], 200);
        }
        return response()->json([
            'error' => 0,
        ], 500);
    }

    public function subscriber_list(Request $request)
    {
        $query_param = [];
        $search = $request['search'];
        if ($request->has('search')) {
            $subscription_list = Subscription::where('email', 'like', "%{$search}%");

            $query_param = ['search' => $request['search']];
        } else {
            $subscription_list = new Subscription;
        }
        $subscription_list = $subscription_list->latest()->paginate(Helpers::pagination_limit())->appends($query_param);
        return view('admin-views.customer.subscriber-list', compact('subscription_list', 'search'));
    }
    public function customer_settings()
    {
        $data = BusinessSetting::where('type', 'like', 'wallet_%')->orWhere('type', 'like', 'loyalty_point_%')->get();
        $data = array_column($data->toArray(), 'value', 'type');

        return view('admin-views.customer.customer-settings', compact('data'));
    }

    public function customer_update_settings(Request $request)
    {
        if (env('APP_MODE') == 'demo') {
            Toastr::info(\App\CPU\translate('update_option_is_disable_for_demo'));
            return back();
        }

        $request->validate([
            'add_fund_bonus' => 'nullable|numeric|max:100|min:0',
            'loyalty_point_exchange_rate' => 'nullable|numeric',
        ]);
        BusinessSetting::updateOrInsert(['type' => 'wallet_status'], [
            'value' => $request['customer_wallet'] ?? 0
        ]);
        BusinessSetting::updateOrInsert(['type' => 'loyalty_point_status'], [
            'value' => $request['customer_loyalty_point'] ?? 0
        ]);
        BusinessSetting::updateOrInsert(['type' => 'wallet_add_refund'], [
            'value' => $request['refund_to_wallet'] ?? 0
        ]);
        BusinessSetting::updateOrInsert(['type' => 'loyalty_point_exchange_rate'], [
            'value' => $request['loyalty_point_exchange_rate'] ?? 0
        ]);
        BusinessSetting::updateOrInsert(['type' => 'loyalty_point_item_purchase_point'], [
            'value' => $request['item_purchase_point'] ?? 0
        ]);
        BusinessSetting::updateOrInsert(['type' => 'loyalty_point_minimum_point'], [
            'value' => $request['minimun_transfer_point'] ?? 0
        ]);

        Toastr::success(\App\CPU\translate('customer_settings_updated_successfully'));
        return back();
    }

    public function get_customers(Request $request)
    {
        $key = explode(' ', $request['q']);
        $data = User::where('id', '!=', 0)->where(function ($q) use ($key) {
            foreach ($key as $value) {
                $q->orWhere('f_name', 'like', "%{$value}%")
                    ->orWhere('l_name', 'like', "%{$value}%")
                    ->orWhere('phone', 'like', "%{$value}%");
            }
        })
            ->limit(8)
            ->get([DB::raw('id, CONCAT(f_name, " ", l_name, " (", phone ,")") as text')]);
        if ($request->all) $data[] = (object)['id' => false, 'text' => trans('messages.all')];


        return response()->json($data);
    }
}
