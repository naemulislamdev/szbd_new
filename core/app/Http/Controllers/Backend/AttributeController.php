<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Attribute;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class AttributeController extends Controller
{
    public function index()
    {
        return view('admin.attribute.index');
    }

    public function datatables()
    {
        $query = Attribute::query();
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


            ->rawColumns([
                'action',
            ])
            ->toJson();
    }

    public function store(Request $request)
    {
        $attribute = new Attribute;
        $attribute->name = $request->name;
        $attribute->save();

        if ($attribute->save()) {
            return response()->json([
                'success' => 1,
            ], 200);
        } else {
            return response()->json([
                'error' => 0,
            ], 500);
        }
    }



    public function update(Request $request)
    {
        $attribute = Attribute::find($request->id);
        $attribute->name = $request->name;

        if ($attribute->save()) {
            return response()->json([
                'success' => 1,
            ], 200);
        } else {
            return response()->json([
                'error' => 0,
            ], 500);
        }
    }

    public function delete(Request $request)
    {
        Attribute::destroy($request->id);
        return response()->json();
    }
}
