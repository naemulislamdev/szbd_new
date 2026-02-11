<?php

namespace App\Http\Controllers\Backend;

use App\CPU\FileManager;
use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Branch;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;


class EmplyeeController extends Controller
{
    function list()
    {
        $branches = Branch::where('status', 1)->get();
        $roles = Role::where('name', '!=', 'super-admin')
            ->where('guard_name', 'admin')
            ->get();

        return view('admin.role_permission.users', compact('branches', 'roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required',
            'role_id'  => 'required|exists:roles,id',
            'image'    => 'required',
            'email'    => 'required|email|unique:admins',
            'password' => 'required|min:6',
            'phone'    => 'required',
        ]);

        if ($request->role_id == 2) {
            return back()->with('warning', 'Access Denied!');
        }


        // 1️⃣ Create Admin
        $admin = Admin::create([
            'name'          => $request->name,
            'phone'         => $request->phone,
            'email'         => $request->email,
            'admin_role_id' => $request->role_id, // UI purpose only
            'branch_id'     => $request->branch_id,
            'password'      => bcrypt($request->password),
            'status'        => 1,
            'image'         => FileManager::uploadFile('admin/', 300, $request->file('image')),
        ]);

        // 2️⃣ Get Role name from roles table
        $role = Role::find($request->role_id);

        app(PermissionRegistrar::class)
            ->setPermissionsTeamId($admin->branch_id);

        // dd($role);
        $admin->assignRole($role);
        $permissions = $role->permissions;
        $admin->givePermissionTo($permissions);

        return redirect()->route('admin.employee.list')->with('success', 'Employee added successfully!');
    }
    public function datatables()
    {
        //VERY IMPORTANT
        app(PermissionRegistrar::class)
            ->setPermissionsTeamId(auth('admin')->user()->branch_id);

        $query = Admin::query()->with('branch');
        $query->latest('id');
        return DataTables::of($query)
            ->addIndexColumn()

            ->addColumn('action', function ($row) {
                return '
        <button
        data-id="' . ($row->id ?? '') . '"
        data-name="' . $row->name . '"
        data-mobile="' . $row->phone . '"
        data-email="' . $row->email . '"
        data-branch="' . ($row->branch ? $row->branch->id : '') . '"
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
                if ($row->getRoleNames()->isEmpty()) {
                    return 'N/A';
                }

                return $row->getRoleNames()
                    ->map(fn($role) => '<span class="badge bg-primary me-1">' . $role . '</span>')
                    ->implode('');
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
                'role',
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
            'id'      => 'required|exists:admins,id',
            'name'    => 'required',
            'role_id' => 'required|exists:roles,id',
            'email'   => 'required|email|unique:admins,email,' . $request->id,
        ]);

        if ($request->role_id == 1) {
            return response()->json([
                'error' => 1,
                'message' => 'Access Denied!'
            ], 403);
        }

        DB::beginTransaction();

        try {
            $admin = Admin::findOrFail($request->id);

            // 🔐 Password handling
            if ($request->filled('password')) {
                if (strlen($request->password) < 8) {
                    return response()->json([
                        'error' => 1,
                        'message' => 'Password length must be at least 8 characters.'
                    ], 422);
                }
                $admin->password = bcrypt($request->password);
            }

            if ($request->hasFile('image')) {
                $admin->image = FileManager::updateFile(
                    'admin/',
                    $admin->image,
                    $request->file('image')
                );
            }

            $admin->update([
                'name'          => $request->name,
                'phone'         => $request->phone,
                'email'         => $request->email,
                'admin_role_id' => $request->role_id,
                'branch_id'     => $request->branch_id,
            ]);

            $role = Role::where('id', $request->role_id)
                ->where('guard_name', 'admin')
                ->first();

            $admin->assignRole($role);
            $permissions = $role->permissions;
            $admin->givePermissionTo($permissions);

            DB::commit();

            return response()->json([
                'success' => 1,
                'message' => 'Employee updated successfully'
            ], 200);
        } catch (\Exception $e) {

            DB::rollBack();

            return response()->json([
                'error' => 1,
                'message' => 'Something went wrong'
            ], 500);
        }
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
