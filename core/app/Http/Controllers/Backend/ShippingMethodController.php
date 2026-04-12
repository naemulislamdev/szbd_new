<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\ShippingMethod;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Str;

class ShippingMethodController extends Controller
{
    public function list()
    {
        return view('admin.shipping_methods.index');
    }
    public function datatables()
    {
        $query = ShippingMethod::query();
        $query->latest('id');

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                return '
                <button class="btn btn-primary btn-sm edit"
                    data-id="' . $row->id . '"
                    data-title="' . $row->title . '"
                    data-cost="' . $row->cost . '"
                    data-duration ="' . $row->duration  . '"
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
            ->rawColumns([
                'action',

                'status'
            ])
            ->toJson();
    }

    public function store(Request $request)
    {
        $shipping = new ShippingMethod();
        $shipping->title = $request->title;
        $shipping->cost = $request->cost;
        $shipping->duration = $request->duration;
        $shipping->creator_type = auth('admin')->user()->name;
        $shipping->creator_id = auth('admin')->user()->id;
        if ($shipping->save()) {
            return response()->json([
                'status' => true,
                'message' => 'Shipping  created successfully'
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => "Something Went Wrong!"
            ], 500);
        }
    }

    public function update(Request $request)
    {
        $shipping = ShippingMethod::find($request->id);
        $shipping->title = $request->title;
        $shipping->cost = $request->cost;
        $shipping->duration = $request->duration;
        $shipping->creator_type = auth('admin')->user()->name;
        $shipping->creator_id = auth('admin')->user()->id;
        if ($shipping->save()) {
            return response()->json([
                'success' => 1,
            ], 200);
        } else {
            return response()->json([
                'error' => 0,
                'message' => "Something Went Wrong!"
            ], 500);
        }
    }

    public function delete(Request $request)
    {
        $shipping = ShippingMethod::find($request->id);
        $shipping->delete();
        return response()->json();
    }

    public function status(Request $request)
    {
        $shipping = ShippingMethod::find($request->id);
        $shipping->status = $request->status;
        $shipping->save();
        return response()->json([
            'success' => 1,
        ], 200);
    }
}
