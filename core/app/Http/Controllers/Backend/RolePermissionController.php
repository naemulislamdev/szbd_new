<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\AdminModule;
use App\Models\AdminRole;
use App\Models\PermissionModule;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionController extends Controller
{
    function list()
    {
        return view('admin.role_permission.roles.list');
    }
    public function datatables()
    {
        $query = Role::query();
        $query->latest('id');
        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                return '
        <a href="' . route('admin.role_permission.edit', $row->id) . '"
         class="btn btn-primary btn-sm viewBtn" title="View" style="cursor: pointer;">
            <i class="las la-edit"></i>
        </a>

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
    public function create()
    {
        $modules = AdminModule::all();
        return view('admin.role_permission.roles.create', compact('modules'));
    }
    public function store(Request $request)
    {
        // 1️⃣ Validate request
        $request->validate([
            'name' => 'required|string|max:100|unique:roles,name',
            'module_access' => 'required|array|min:1',
        ]);

        DB::beginTransaction();

        try {
            // 2️⃣ Create role
            $role = Role::create([
                'name'       => $request->name,
                'guard_name' => 'admin',
                'module_access' => json_encode($request->module_access),
            ]);

            $permissions = [];

            // 3️⃣ Create permissions if not exists
            foreach ($request->module_access as $permissionName) {
                $permission = Permission::firstOrCreate(
                    [
                        'name'       => $permissionName,
                        'guard_name' => 'admin',
                    ]
                );

                $permissions[] = $permission->name;
            }

            // 4️⃣ Assign permissions to role
            $role->syncPermissions($permissions);

            DB::commit();

            return redirect()
                ->route('admin.role_permission.list')
                ->with('success', 'Role & permissions created successfully');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }
    public function edit($id)
    {
        $role = Role::findOrFail($id);
        $modules = AdminModule::all();
        return view('admin.role_permission.roles.edit', compact('role', 'modules'));
    }
    public function update(Request $request, $id)
    {
        // 1️⃣ Validate request
        $request->validate([
            'name' => 'required|string|max:100|unique:roles,name,' . $id,
            'module_access' => 'required|array|min:1',
        ]);

        DB::beginTransaction();

        try {
            $role = Role::findOrFail($id);
            $role->name = $request->name;
            $role->module_access = json_encode($request->module_access);
            $role->save();

            $permissions = [];

            // 2️⃣ Create permissions if not exists
            foreach ($request->module_access as $permissionName) {
                $permission = Permission::firstOrCreate(
                    [
                        'name'       => $permissionName,
                        'guard_name' => 'admin',
                    ]
                );

                $permissions[] = $permission->name;
            }

            // 3️⃣ Sync permissions to role
            $role->syncPermissions($permissions);

            DB::commit();

            return redirect()
                ->route('admin.role_permission.list')
                ->with('success', 'Role & permissions updated successfully');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Something went wrong: ' . $e->getMessage());
        }
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
        $role = Role::find($request->id);
        $role->status = $request->status;
        $role->save();
        return response()->json([
            'success' => 1,
        ], 200);
    }
}
