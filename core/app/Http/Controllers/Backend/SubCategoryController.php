<?php

namespace App\Http\Controllers\Backend;

use App\CPU\Helpers;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class SubCategoryController extends Controller
{
    public function index()
    {
        $categories = Category::where('home_status', 1)->latest()->get();
        return view('admin.subcategory.index', compact('categories'));
    }

    public function datatables()
    {
        $query = SubCategory::query();
        $query->latest('id');

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('category', function ($row) {
                return optional($row->category)->name ?? '—';
            })
            ->addColumn('action', function ($row) {

                return '
                <button class="btn btn-primary btn-sm edit"
                    data-id="' . $row->id . '"
                    data-category_id="' . $row->category_id . '"
                    data-name="' . $row->name . '"
                    data-order="' . $row->order_number . '"
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
                'action',
                'icon',
                'status',
                'category'
            ])
            ->toJson();
    }

    public function store(Request $request)
    {
        $validinfo = $request->validate([
            'category_id' => 'required|numeric',
            'name' => 'required|string|max:255',
            'order_number' => 'required'
        ], [
            'category_id.required' => 'Please select a category.',
            'category_id.numeric' => 'Invalid category selected.',
        ]);
        $sub = new SubCategory();
        $sub->category_id = $request->category_id;
        $sub->name = $request->name;
        $sub->slug = Str::slug($request->name);
        $sub->order_number = $request->order_number;
        if ($sub->save()) {
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


    public function update(Request $request,)
    {
        $validinfo = $request->validate([
            'category_id' => 'required:numeric',
            'name' => 'required|string|max:255',
            'order_number' => 'required'
        ], [
            'category_id.required' => 'Please select a category.',
            'category_id.numeric' => 'Invalid category selected.',
        ]);
        $sub = SubCategory::find($request->id);
        $sub->category_id = $request->category_id;
        $sub->name = $request->name;
        $sub->slug = Str::slug($request->name);
        $sub->order_number = $request->order_number;
        if ($sub->save()) {
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
        $sub = SubCategory::find($request->id);
        if ($sub->delete()) {
            return response()->json([
                'success' => 1,
            ], 200);
        } else {
            return response()->json([
                'error' => 0,
            ], 500);
        }
    }

    public function status(Request $request)
    {
        $subcategory = SubCategory::find($request->id);
        $subcategory->status = $request->status;

        if ($subcategory->save()) {
            return response()->json([
                'success' => 1,
            ], 200);
        } else {
            return response()->json([
                'error' => 0,
            ], 500);
        }
    }
}

// if ($childcat->save()) {
//     return response()->json([
//         'success' => 1,
//     ], 200);
// } else {
//     return response()->json([
//         'error' => 0,
//         'message' => $validinfo
//     ], 500);
// }
