<?php

namespace App\Http\Controllers\Backend;

use App\CPU\FileManager;
use App\Http\Controllers\Controller;
use App\Models\AdminRole;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class RolePermissionController extends Controller
{
    function list()
    {
        return view('admin.role_permission.roles.list');
    }
    public function datatables()
    {
        $query = AdminRole::query();
        $query->latest('id');
        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                return '
        <button
            data-bs-toggle="modal"
               data-bs-target="#viewInvestorModal"
         class="btn btn-primary btn-sm viewBtn" title="View" style="cursor: pointer;">
            <i class="las la-edit"></i>
        </button>

        <button class="btn btn-danger btn-sm delete"
                style="cursor: pointer;"
                title="Delete"
                data-id="' . $row->id . '">
            <i class="la la-trash"></i>
        </button>
    ';
            })
            ->editColumn('module_access', function ($row) {
                $access = $row->module_access;
                $access = json_decode($access);
                $html = '';
                if (is_array($access)) {
                    foreach ($access as $item) {
                        $html .= '<span class="badge bg-primary me-1 mb-1">' . $item . '</span>';
                    }
                }
                return $html;
            })



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

            ->editColumn('created_at', function ($row) {
                return $row->created_at ? $row->created_at->format('d M Y h:i A') : "";
            })

            ->rawColumns([
                'status',
                'module_access',
                'action',
            ])
            ->toJson();
    }
    public function destroy(Request $request)
    {
        $user = AdminRole::find($request->id);
        $user->delete();

        return response()->json([
            'success' => 1,
        ], 200);
    }

    public function status(Request $request)
    {
        $category = AdminRole::find($request->id);
        $category->status = $request->status;
        $category->save();
        return response()->json([
            'success' => 1,
        ], 200);
    }
    public function create()
    {

        return view('admin.role_permission.roles.create');
    }
}
