<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;


class FranchiseController extends Controller
{
    public function list()
    {
        return view('admin.franchise.index');
    }
    public function datatables()
    {
        $query = Lead::query();
        $query->latest('id');
        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('change_status', function ($row) {

                $status = $row->lead_status;
                $id = $row->id;

                return '
                    <div class="dropdown">
                        <select name="lead_status"
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
        data-showroomSize="' . $row->showroom_size . '"
        data-upazila="' . $row->upazila . '"
        data-district="' . $row->district . '"
        data-division="' . $row->division . '"
        data-showroomLocation="' . $row->showroom_location . '"
        data-remark="' . $row->remark . '"
        data-date="' . $row->created_at->format('d M Y h:i A') . '"
        data-status="' . ($row->lead_status) . '"
        data-statusNote="' . ($row->status_note ? $row->status_note : 'N/A') . '"
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
    public function status(Request $request)
    {
        $lead = Lead::find($request->id);
        $lead->lead_status = $request->lead_status;
        $lead->status_note = $request->status_note;
        $lead->save();

        return response()->json([
            'status' => true,
            'id' => $lead->id,
            'note' => $lead->status_note
        ]);
    }

    public function view(Request $request)
    {
        $item = Lead::findOrFail($request->id);

        // status update
        if ($item->status !== 1) {
            $item->status = 1;
            $item->save();
        }

        return response()->json([
            'status' => $item->status,
        ]);
    }
    public function updateLeadRemark(Request $request)
    {
        $lead = Lead::find($request->id);
        $lead->remark = $request->remark;
        $lead->save();

        return response()->json([
            'status' => true,
            'id' => $lead->id,
            'remark' => $lead->remark
        ]);
    }
    public function destroy(Request $request)
    {
        $lead = Lead::find($request->id);
        $lead->delete();

        return response()->json();
    }
    // public function bulk_export_LeadsData()
    // {
    //     $leads = Lead::latest()->get();
    //     //export from leads
    //     $data = [];
    //     foreach ($leads as $item) {
    //         $data[] = [
    //             'Date' => Carbon::parse($item->created_at)->format('d M Y'),
    //             'name' => $item->name,
    //             'phone' => $item->phone,
    //             'address' => $item->address,
    //             'division' => $item->division,
    //             'district' => $item->district,
    //             'upazila' => $item->upazila,
    //             'Showroom Size' => $item->showroom_size,
    //             'Showroom Location' => $item->showroom_location,
    //             'status' => $item->status == 1 ? 'Unseen' : 'Seen',

    //         ];
    //     }
    //     $headings = ['Date', 'Name', 'Phone', 'Address', 'Division', 'District', 'Upazila', 'Showroom Size', 'Showroom Location', 'Status'];
    //     return Excel::download(new DynamicExport($headings, $data), 'leads_info.xlsx');
    // }
}
