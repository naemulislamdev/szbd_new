<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class BranchController extends Controller
{
    public function index()
    {
        return view('admin.branch.index');
    }
    public function datatables()
    {
        $query = Branch::query();
        $query->latest('id');

        return DataTables::of($query)

            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                return '
                <button class="btn btn-primary btn-sm edit"
                    data-id="' . $row->id . '"
                    data-name="' . $row->name . '"
                    data-phone="' . $row->phone . '"
                    data-email="' . $row->email . '"
                    data-map_url="' . $row->map_url . '"
                    data-address="' . $row->address . '"
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
            ->editColumn('address', function ($row) {
                return $row->address;
            })
            ->editColumn('status', function ($row) {

                $checked = $row->status == 1 ? 'checked' : '';

                return '
                        <div class="form-check form-switch">
                            <input class="form-check-input status"
                                type="checkbox"
                                name="status"
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
                'status',
                'address'
            ])
            ->toJson();
    }
    public function status(Request $request)
    {
        $br = Branch::find($request->id);
        $br->status = $request->status;
        $br->save();
        return response()->json([
            'success' => 1,
        ], 200);
    }
    public function delete(Request $request)
    {
        $br = Branch::find($request->id);

        $br->delete();
        return response()->json(['success' => true]);
    }
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required',
            'phone' => 'required',
            'address' => 'required',
            'map_url' => 'required',
        ], [
            'name.required'   => 'Branch name is required!',
            'email.required'   => 'Branch email is required!',
            'phone.required'   => 'Branch phone is required!',
            'address.required'   => 'Branch address is required!',
            'map_url.required'   => 'Branch Map URL is required!',
        ]);

        $branch = new Branch();
        $branch->user_id = auth('admin')->id();
        $branch->name = $request->name;
        $branch->email = $request->email;
        $branch->phone = $request->phone;
        $branch->address = $request->address;
        $branch->map_url = $request->map_url;
        $branch->status = 1;

        if ($branch->save()) {
            return response()->json(['success' => true]);
        }
    }
    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required',
            'phone' => 'required',
            'address' => 'required',
            'map_url' => 'required',
        ], [
            'name.required'   => 'Branch name is required!',
            'email.required'   => 'Branch email is required!',
            'phone.required'   => 'Branch phone is required!',
            'address.required'   => 'Branch address is required!',
            'map_url.required'   => 'Branch Map URL is required!',
        ]);

        $branch = new Branch();
        $branch->user_id = auth('admin')->id();
        $branch->name = $request->name;
        $branch->email = $request->email;
        $branch->phone = $request->phone;
        $branch->address = $request->address;
        $branch->map_url = $request->map_url;
        $branch->status = 1;

        if ($branch->save()) {
            return response()->json(['success' => true]);
        }
    }
}
