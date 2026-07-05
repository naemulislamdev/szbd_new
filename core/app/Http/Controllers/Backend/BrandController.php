<?php

namespace App\Http\Controllers\Backend;

use App\CPU\FileManager;
use App\Http\Controllers\Controller;
use App\Models\Brand;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class BrandController extends Controller
{
    public function list()
    {
        return view('admin.brand.index');
    }

    public function datatables()
    {
        $query = Brand::query();
        $query->latest('id');

        return DataTables::of($query)

            ->addIndexColumn()
            ->editColumn('image', function ($row) {

                return '<img src="' . asset("assets/storage/brand/" . $row->image) . '"
                 alt="icon"
                 width="40"
                 height="40">';
            })
            ->editColumn('total_product', function ($row) {
                return $row->brandAllProducts->count();
            })
            ->editColumn('total_order', function ($row) {
                return $row->brandAllProducts->sum('order_details_count');
            })
            ->addColumn('action', function ($row) {
                return '
                <button class="btn btn-primary btn-sm edit"
                    data-id="' . $row->id . '"
                    data-name="' . $row->name . '"
                    data-image="' . asset('assets/storage/brand/' . $row->image) . '"
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
                'image',
                'status',
                'total_product',
                'total_order'
            ])
            ->toJson();
    }
    public function store(Request $request)
    {
        $validinfo =  $request->validate([
            'name' => 'required',
            'image' => 'required',
        ], [
            'name.required' => 'Brand name is required!',
            'icon.required' => 'Brand image is required!',

        ]);

        $br = new Brand;
        $br->name = $request->name;
        $br->image = FileManager::uploadFile('brand/', 300, $request->file('image'));

        if ($br->save()) {
            return response()->json([
                'status' => true,
                'message' => 'Brand created successfully'
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
            'id' => 'required|numeric',
            'name' => 'required',
        ], [
            'id.numeric' => 'Invalid category!',
            'name.required' => 'Brand name is required!',
        ]);

        $br = Brand::find($request->id);
        $br->name = $request->name;
        if ($request->image) {
            $br->image = FileManager::updateFile('brand/', $br->image, $request->file('image'));
        }
        if ($br->save()) {
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
    public function status(Request $request)
    {
        $br = Brand::find($request->id);
        $br->status = $request->status;
        $br->save();
        return response()->json([
            'success' => 1,
        ], 200);
    }
    public function delete(Request $request)
    {
        $br = Brand::find($request->id);
        if ($br->image) {
            FileManager::delete('brand/' . $br->image);
        }
        $br->delete();
        return response()->json(['success' => true]);
    }
}
