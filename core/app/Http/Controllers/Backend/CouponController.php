<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\CPU\BackEndHelper;
use App\CPU\Convert;
use App\CPU\Helpers;
use App\Models\Coupon;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Yajra\DataTables\Facades\DataTables;


class CouponController extends Controller
{
    public function list()
    {
        return view('admin.coupon.list');
    }
    public function datatables()
    {
        $query = Coupon::query();
        $query->latest('id');

        return DataTables::of($query)

            ->addIndexColumn()



            ->addColumn('action', function ($row) {

                return '
                <button class="btn btn-primary btn-sm edit"
                    data-id="' . $row->id . '"
                    data-coupon_type="' . $row->coupon_type . '"
                    data-code="' . $row->code . '"
                    data-title="' . $row->title . '"
                    data-discount_type="' . $row->discount_type . '"
                    data-start_date="' . \Carbon\Carbon::parse($row->start_date)->format('Y-m-d') . '"
                    data-expire_date="' . \Carbon\Carbon::parse($row->expire_date)->format('Y-m-d') . '"
                    data-discount="' . $row->discount . '"
                    data-limit="' . $row->limit . '"
                    data-min_purchase="' . $row->min_purchase . '"
                    data-max_discount="' . $row->max_discount . '"
                    data-bs-toggle="modal"
                    data-bs-target="#editModal">
                    <i class="la la-edit"></i>
                </button>

                <button class="btn btn-danger btn-sm delete"
                        style="cursor: pointer;"
                        title="Delete"
                        data-id="' . $row->id . '">
                    <i class="la la-trash"></i>
                </button>
            ';
            })

            // Edit Column
            ->editColumn('status', function ($row) {

                $checked = $row->status == 1 ? 'checked' : '';

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
            ->editColumn('start_date', function ($row) {
                return $row->start_date->format('d M Y ');
            })
            ->editColumn('expire_date', function ($row) {
                return $row->expire_date->format('d M Y ');
            })
            ->editColumn('coupon_type', function ($row) {
                return ucwords(str_replace('_', ' ', $row->coupon_type));
            })
            ->editColumn('discount_type', function ($row) {
                return ucwords(str_replace('_', ' ', $row->discount_type));
            })
            ->editColumn('allready_use', function ($row) {
                return $row->order->count();
            })
            ->rawColumns([
                'action',
                'status',
                'start_date',
                'expire_date',
                'coupon_type',
                'discount_type',
                'allready_use'
            ])
            ->toJson();
    }
    public function add_new(Request $request)
    {
        $query_param = [];
        $search = $request['search'];
        if ($request->has('search')) {
            $key = explode(' ', $request['search']);
            $cou = Coupon::where(function ($q) use ($key) {
                foreach ($key as $value) {
                    $q->orWhere('title', 'like', "%{$value}%")
                        ->orWhere('code', 'like', "%{$value}%")
                        ->orWhere('discount_type', 'like', "%{$value}%");
                }
            });
            $query_param = ['search' => $request['search']];
        } else {
            $cou = new Coupon();
        }

        $cou = $cou->withCount('order')->latest()->paginate(Helpers::pagination_limit())->appends($query_param);

        return view('admin-views.coupon.add-new', compact('cou', 'search'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|unique:coupons',
            'title' => 'required',
            'start_date' => 'required',
            'expire_date' => 'required',
            'discount' => 'required',
            'min_purchase' => 'required',
            'limit' => 'required',
        ]);
        $coupon = new Coupon();
        $coupon->coupon_type = $request->coupon_type;
        $coupon->title = $request->title;
        $coupon->code = $request->code;
        $coupon->start_date = $request->start_date;
        $coupon->expire_date = $request->expire_date;
        $coupon->min_purchase = $request->min_purchase;
        $coupon->max_discount = $request->max_discount != null ? $request->max_discount : $request->discount;
        $coupon->discount = $request->discount_type == 'amount' ? $request->discount : $request['discount'];
        $coupon->discount_type = $request->discount_type;
        $coupon->limit = $request->limit;
        $coupon->status = 1;
        $coupon->save();

        return response()->json(
            [
                'success' => true,
            ]
        );
    }
    public function update(Request $request)
    {
        $request->validate([
            'code' => 'required|unique:coupons,code,' . $request->id,
            'title' => 'required',
            'start_date' => 'required',
            'expire_date' => 'required',
            'discount' => 'required',
            'min_purchase' => 'required',
            'limit' => 'required',
        ]);

        DB::table('coupons')->where(['id' => $request->id])->update([
            'coupon_type' => $request->coupon_type,
            'title' => $request->title,
            'code' => $request->code,
            'start_date' => $request->start_date,
            'expire_date' => $request->expire_date,
            'min_purchase' => $request->min_purchase,
            'max_discount' => $request->max_discount != null ? $request->max_discount : $request->discount,
            'discount' => $request->discount_type == 'amount' ?  $request->discount : $request['discount'],
            'discount_type' => $request->discount_type,
            'updated_at' => now(),
            'limit' => $request->limit,
        ]);

        return response()->json(['success' => true]);
    }

    public function status(Request $request)
    {

        $coupon = Coupon::find($request->id);
        $coupon->status = $request->status;
        $coupon->save();
        $data = $request->status;

        return response()->json(['success' => true]);
    }

    public function delete(Request $request)
    {
        $coupon = Coupon::find($request->id);
        $coupon->delete();
        return response()->json(['success' => true]);
    }
}
