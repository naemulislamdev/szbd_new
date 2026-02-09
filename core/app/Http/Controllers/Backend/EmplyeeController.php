<?php

namespace App\Http\Controllers\Backend;

use App\CPU\FileManager;
use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\AdminRole;
use App\Models\Branch;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;


class EmplyeeController extends Controller
{
    function list()
    {
        $branches = Branch::where('status', 1)->get();
        $roles = AdminRole::where("status", 1)->get();
        return view('admin.role_permission.users', compact('branches', 'roles'));
    }

    public function store(Request $request)
    {
        $validinfo =  $request->validate([
            'name' => 'required',
            'role_id' => 'required',
            'image' => 'required',
            'email' => 'required|email|unique:admins',
            'password' => 'required',
            'phone' => 'required'

        ], [
            'name.required' => 'Role name is required!',
            'role_name.required' => 'Role id is Required',
            'email.required' => 'Email id is Required',
            'image.required' => 'Image is Required',

        ]);

        if ($request->role_id == 1) {
            return response()->json([
                'error' => 0,
                'message' => 'Access Denied!'
            ], 500);
        }

        $storeInfo = DB::table('admins')->insert([
            'name' => $request->name,
            'phone' => $request->phone,
            'email' => $request->email,
            'admin_role_id' => $request->role_id,
            'branch_id' => $request->branch_id,
            'password' => bcrypt($request->password),
            'status' => 1,
            'image' => FileManager::uploadFile('admin/', 300, $request->file('image')),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        if ($storeInfo) {
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
    public function datatables()
    {
        $query = Admin::query();
        $query->latest('id');
        return DataTables::of($query)
            ->addIndexColumn()

            ->addColumn('action', function ($row) {
                return '
        <button
        data-id="' . $row->id . '"
        data-name="' . $row->name . '"
        data-mobile="' . $row->phone . '"
        data-email="' . $row->email . '"
        data-branch="' . $row->branch->id . '"
        data-role="' . $row->role->id . '"
        data-date="' . $row->created_at->format('d M Y h:i A') . '"
        data-status="' . ($row->status == 1 ? 'Seen' : 'Unseen') . '"
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
            ->editColumn('created_at', function ($row) {
                return $row->created_at->format('d M Y h:i A');
            })

            // Edit Column

            ->editColumn('branch', function ($row) {
                $status = $row->branch->name ?? 'N/A';
                return $status;
            })
            ->editColumn('role', function ($row) {
                $status = $row->role->name ?? 'N/A';
                return $status;
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

            ->rawColumns([
                'status',
                'remark',
                'created_at',
                'action',
            ])
            ->toJson();
    }
    // public function bulk_export_employee()
    // {
    //     $em = Admin::latest()->get();
    //     //export from userInfos
    //     $data = [];
    //     foreach ($em as $item) {
    //         //  ->orWhere('admin', 'like', "%{$value}%")
    //         //             ->orWhere('email', 'like', "%{$value}%")
    //         //             ->orWhere('phone', 'like', "%{$value}%")
    //         //             ->orWhere('branch', 'like', "%{$value}%")
    //         //             ->orWhere('role', 'like', "%{$value}%");
    //         $data[] = [
    //             'Date' => Carbon::parse($item->created_at)->format('d M Y'),
    //             'Name' => $item->name,
    //             'Phone' => $item->phone,
    //             'Email' => $item->email,
    //             'Branch' => $item->branch,
    //             'Role' => $item->role['name'],
    //         ];
    //     }
    //     return (new FastExcel($data))->download('employee_info.xlsx');
    // }



    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'role_id' => 'required',
            'email' => 'required|email|unique:admins,email,' . $request->id,
        ], [
            'name.required' => 'Role name is required!',
        ]);

        if ($request->role_id == 1) {
            return response()->json([
                'error' => 0,
                'message' => 'Access Denied!'
            ], 500);
        }

        $e = Admin::find($request->id);
        if ($request['password'] == null) {
            $pass = $e['password'];
        } else {
            if (strlen($request['password']) < 8) {
                return response()->json([
                    'error' => 0,
                    'message' => 'Password length must be 8 character.'
                ], 500);
            }
            $pass = bcrypt($request['password']);
        }

        if ($request->has('image')) {
            $e['image'] = FileManager::updateFile('admin/', $e['image'], 300, $request->file('image'));
        }

        DB::table('admins')->where(['id' => $request->id])->update([
            'name' => $request->name,
            'phone' => $request->phone,
            'email' => $request->email,
            'admin_role_id' => $request->role_id,
            'branch_id' => $request->branch_id,
            'password' => $pass,
            'image' => $e['image'],
            'updated_at' => now(),
        ]);

        return response()->json([
            'success' => 1,
            'message' => 'Employee updated successfully'
        ], 200);
    }

    public function status(Request $request)
    {
        $category = Admin::find($request->id);
        $category->status = $request->status;
        $category->save();
        return response()->json([
            'success' => 1,
        ], 200);
    }
    public function destroy(Request $request)
    {
        $user = Admin::find($request->id);
        if (file_exists(public_path('assets/storage/admin/' . $user->image))) {
            FileManager::delete('admin/' . $user->image);
        }
        $user->delete();

        return response()->json([
            'success' => 1,
        ], 200);
    }
}
