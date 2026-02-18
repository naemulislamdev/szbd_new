<?php

namespace App\Http\Controllers\Backend;

use App\Exports\DataExport;
use App\Http\Controllers\Controller;
use App\Models\Investor;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;


class InvestorController extends Controller
{
    //--- Investment Management ---//
    public function investorsList()
    {
        return view('admin.investors.index');
    }
    public function datatables()
    {
        $query = Investor::query();
        $query->latest('id');
        return DataTables::of($query)
            ->addIndexColumn()

            ->addColumn('action', function ($row) {
                return '
        <button
        data-id="' . $row->id . '"
        data-name="' . $row->name . '"
        data-mobile="' . $row->mobile_number . '"
        data-address="' . $row->address . '"
        data-occupation="' . $row->occupation . '"
        data-investment-amount="' . $row->investment_amount . '"
        data-remark="' . $row->remark . '"
        data-date="' . $row->created_at->format('d M Y h:i A') . '"
        data-status="' . ($row->status == 1 ? 'Seen' : 'Unseen') . '"
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
                'remark',
                'created_at',
                'action',
            ])
            ->toJson();
    }

    public function investorsViewStatus(Request $request)
    {
        $item = Investor::findOrFail($request->id);

        // status update
        if ($item->status !== 1) {
            $item->status = 1;
            $item->save();
        }

        return response()->json([
            'status' => $item->status,
        ]);
    }

    public function delete(Request $request)
    {
        $investor = Investor::findOrFail($request->id);
        if ($investor->delete()) {
            return response()->json([
                'success' => 1,
            ], 200);
        }
        return response()->json([
            'error' => 0,
        ], 500);
    }
    public function dateWiseExport(Request $request)
    {
        $request->validate([
            'from_date' => 'required|date',
            'to_date'   => 'required|date|after_or_equal:from_date',
        ]);

        $investors = Investor::whereBetween('created_at', [
            Carbon::parse($request->from_date)->startOfDay(),
            Carbon::parse($request->to_date)->endOfDay(),
        ])->latest()->cursor();
        //export from userInfos
        $data = [];
        foreach ($investors as $item) {
            $data[] = [
                'Date' => Carbon::parse($item->created_at)->format('d M Y'),
                'name' => $item->name,
                'phone' => $item->mobile_number,
                'address' => $item->address,
                'occupation' => $item->occupation,
                'investment_amount' => $item->investment_amount,
                'status' => $item->status == 0 ? 'Unseen' : 'Seen',
            ];
        }

        $headings = ['Date', 'Name', 'Phone', 'Address', 'Occupation', 'Investment Amount', 'Status'];

        return Excel::download(new DataExport($headings, $data), 'investors_info.xlsx');
    }
    public function remarkStore(Request $request)
    {

        $request->validate([
            'id' => 'required|exists:investors,id',
            'remark' => 'required|string'
        ]);

        $investor = Investor::find($request->id);
        $investor->remark = $request->remark;
        $investor->save();

        return response()->json([
            'status' => true,
            'id' => $investor->id,
            'remark' => $investor->remark
        ]);
    }
}
