<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Wholesale;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class WholeSaleController extends Controller
{
    public function create()
    {
        return view("web-views.wholesale.wholesale");
    }
    public function list()
    {
        return view('admin.wholesale.index');
    }
    public function datatables()
    {
        $query = Wholesale::query();
        $query->latest('id');
        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('change_status', function ($row) {

                $status = $row->wholesale_status;
                $id = $row->id;

                return '
                    <div class="dropdown">
                        <select name="wholesale_status"
                            onchange="lead_status(this.value, ' . $id . ')"
                            class="form-control changeStatus status_select_' . $id . '"
                            data-id="' . $id . '">

                            <option value="pending" ' . ($status == 'pending' ? 'selected' : '') . '>
                                Pending
                            </option>

                            <option value="confirmed" ' . ($status == 'confirmed' ? 'selected' : '') . '>
                                Confirmed
                            </option>

                            <option value="canceled" ' . ($status == 'canceled' ? 'selected' : '') . '>
                                Canceled
                            </option>

                        </select>
                    </div>
                ';
            })


            ->addColumn('action', function ($row) {
                return '
        <button
        data-id="' . $row->id . '"
        data-name="' . $row->name . '"
        data-mobile="' . $row->phone . '"
        data-address="' . $row->address . '"
        data-occupation="' . $row->occupation . '"
        data-productQuantity="' . round($row->product_quantity) . '"
        data-remark="' . $row->remark . '"
        data-date="' . $row->created_at->format('d M Y h:i A') . '"
        data-status="' . ($row->wholesale_status) . '"
        data-statusNote="' . ($row->wholesale_note ? $row->wholesale_note : 'N/A') . '"
            data-bs-toggle="modal"
               data-bs-target="#viewInvestorModal"
         class="btn btn-primary btn-sm viewBtn" title="View" style="cursor: pointer;">
            <i class="la la-eye"></i>
        </button>

        <button class="btn btn-danger btn-sm delete"
                style="cursor: pointer;"
                title="Delete"
                data-id="' . $row->id . '">
            <i class="la la-trash"></i>
        </button>
    ';
            })
            ->editColumn('created_at', function ($row) {
                return $row->created_at->format('d M Y h:i A');
            })
            ->editColumn('status_note', function ($row) {
                return $row->status_note ? $row->status_note : 'N/A';
            })
            ->editColumn('remark', function ($row) {

                if ($row->remark) {
                    return $row->remark;
                }

                return '
                <a href="#"
                class="btn btn-sm text-center btn-primary my-2 addRemarkBtn"
                data-bs-toggle="modal"
                data-id="' . $row->id . '"
                data-bs-target="#remarkAddModal">
                Add
                </a>
            ';
            })
            // Edit Column
            ->editColumn('status', function ($row) {
                $status = $row->status == 1 ? 'Seen' : 'Unseen';
                return '
                        <div class="">
                            <button  class="btn btn-sm btn-primary ">' . $status . '</button>
                        </div>';
            })
            ->rawColumns([
                'status',
                'change_status',
                'created_at',
                'action',
            ])
            ->toJson();
    }
    public function destroy(Request $request)
    {
        $wlist = Wholesale::find($request->id);
        $wlist->delete();

        return response()->json([
            'status' => true,
            'message' => 'Wholesale deleted successfully.'
        ]);
    }
    public function status(Request $request)
    {
        $wSale = Wholesale::find($request->id);
        $wSale->wholesale_status = $request->wholesale_status;
        $wSale->wholesale_note = $request->wholesale_note;
        $wSale->save();

        return response()->json([
            'status' => true,
            'id' => $wSale->id,
            'note' => $wSale->wholesale_note
        ]);
    }
}
