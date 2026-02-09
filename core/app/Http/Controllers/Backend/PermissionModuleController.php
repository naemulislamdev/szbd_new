<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\PermissionModule;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Nette\Utils\Json;
use Yajra\DataTables\Facades\DataTables;

class PermissionModuleController extends Controller
{
    public function list()
    {
        return view('admin.role_permission.modules.index');
    }
    public function datatables()
    {
        $query = PermissionModule::query();
        $query->latest('id');
        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                return '
        <button
            data-bs-toggle="modal"
            data-id="' . $row->id . '"
        data-title="' . $row->title . '"
        data-actions=' . json_encode($row->actions) . '

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
            ->editColumn('actions', function ($row) {

                $actions = json_decode($row->actions, true);

                if (!$actions) return '';

                return collect($actions)
                    ->map(fn($a) => '<span class="badge bg-primary me-1">' . ucfirst($a) . '</span>')
                    ->implode('');
            })

            ->rawColumns([

                'actions',
                'action',
            ])
            ->toJson();
    }

    public function store(Request $request)
    {
        $validinfo = $request->validate([
            'title' => 'required|string|max:255',
            'actions' => 'required|array|min:1',
        ]);

        $storeInfo = PermissionModule::create([
            'title' => $validinfo['title'],
            'actions' => json_encode($validinfo['actions']),
        ]);
        return response()->json([
            'status' => 'success',
            'message' => 'Module created successfully',
            'data' => $storeInfo
        ]);
    }
    public function update(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'actions' => 'required|array',
        ]);
        $module = PermissionModule::find($request->id);
        $module->title = $request->title;
        $module->slug = Str::slug($request->title);
        $module->actions = json_encode($request['actions']);

        if ($module->save()) {
            return response()->json([
                'success' => 1,
                'message' => 'Module updated successfully'
            ], 200);
        } else {
            return response()->json([
                'error' => 0,
                'message' => 'Module update failed'
            ], 500);
        }
    }
    public function destroy(Request $request)
    {
        $module = PermissionModule::find($request->id);
        $module->delete();

        return response()->json([
            'success' => 1,
        ], 200);
    }
}
