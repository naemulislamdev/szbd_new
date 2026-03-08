<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\RoleDepartment;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class RoleDepartmentController extends Controller
{
    public function index()
    {
        $roleDept = RoleDepartment::all();
        return view('admin.role_permission.role_department.index');
    }
    public function datatables()
    {
        $query = RoleDepartment::query();
        $query->latest('id');
        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {

                return '
                <button class="btn btn-primary btn-sm edit"
                    data-id="' . $row->id . '"
                    data-name="' . $row->name . '"
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
            ])
            ->toJson();
    }
    public function store(Request $request)
    {
        $validinfo =  $request->validate([
            'name' => 'required',
        ]);

        $category = new RoleDepartment();
        $category->name = $request->name;
        if ($category->save()) {
            return response()->json([
                'status' => true,
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => $validinfo
            ], 500);
        }
    }
    public function update(Request $request)
    {
        $validinfo = $request->validate([
            'name' => 'required',
        ]);

        $category = RoleDepartment::find($request->id);
        $category->name = $request->name;
        if ($category->save()) {
            return response()->json([
                'success' => 1,
            ], 200);
        } else {
            return response()->json([
                'error' => 0,
                'message' => $validinfo
            ], 500);
        }
    }

    public function delete(Request $request)
    {
        $category = RoleDepartment::find($request->id);
        $category->delete();
        return response()->json();
    }

    public function status(Request $request)
    {
        $category = RoleDepartment::find($request->id);
        $category->status = $request->status;
        $category->save();
        return response()->json([
            'success' => 1,
        ], 200);
    }
}
