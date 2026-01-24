<?php

namespace App\Http\Controllers\Backend;

use App\CPU\Helpers;
use App\Http\Controllers\Controller;
use App\Models\Category;

use App\Models\ChildCategory;
use App\Models\SubCategory;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class SubSubCategoryController extends Controller
{
    public function index()
    {
        $categories = Category::where('home_status', 1)->latest()->get();
        $subCategories = SubCategory::where('status', 1)->latest()->get();
        return view('admin.child_category.index', compact('categories', 'subCategories'));
    }

    public function datatables()
    {
        $query = ChildCategory::query();
        $query->latest('id');

        return DataTables::of($query)

            ->addIndexColumn()
            ->addColumn('category', function ($row) {
                return optional($row->category)->name ?? '—';
            })
            ->addColumn('sub-category', function ($row) {
                return optional($row->subCategory)->name ?? '—';
            })


            ->addColumn('action', function ($row) {

                return '
                <button class="btn btn-primary btn-sm edit"
                    data-id="' . $row->id . '"
                    data-category_id="' . $row->category_id . '"
                    data-sub_category_id="' . $row->sub_category_id . '"
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
        $validinfo = $request->validate(
            [
                'category_id'     => 'required|numeric',
                'sub_category_id' => 'required|numeric',
                'name'            => 'required|string|max:255',
                'order_number'    => 'required'
            ],
            [
                'category_id.required'     => 'Please select a category.',
                'category_id.numeric'      => 'Invalid category selected.',

                'sub_category_id.required' => 'Please select a sub category.',
                'sub_category_id.numeric'  => 'Invalid sub category selected.',
            ]
        );

        $category = new ChildCategory;
        $category->category_id = $request->category_id;
        $category->sub_category_id = $request->sub_category_id;
        $category->name = $request->name;
        $category->slug = Str::slug($request->name);
        $category->order_number = $request->order_number;

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

    public function update(Request $request)
    {
        $validinfo = $request->validate(
            [
                'category_id'     => 'required|numeric',
                'sub_category_id' => 'required|numeric',
                'name'            => 'required|string|max:255',
                'order_number'    => 'required'
            ],
            [
                'category_id.required'     => 'Please select a category.',
                'category_id.numeric'      => 'Invalid category selected.',

                'sub_category_id.required' => 'Please select a sub category.',
                'sub_category_id.numeric'  => 'Invalid sub category selected.',
            ]
        );

        $childcat = ChildCategory::find($request->id);
        $childcat->category_id = $request->category_id;
        $childcat->sub_category_id = $request->sub_category_id;
        $childcat->name = $request->name;
        $childcat->slug = Str::slug($request->name);
        $childcat->order_number = $request->order_number;

        if ($childcat->save()) {
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
        $childcat = ChildCategory::find($request->id);

        if ($childcat->delete()) {
            return response()->json([
                'success' => 1,
            ], 200);
        } else {
            return response()->json([
                'error' => 0,
                'message' => 'Failed to delete child category'
            ], 500);
        }
    }


    public function status(Request $request)
    {
        $subcategory = ChildCategory::find($request->id);
        $subcategory->status = $request->status;
        $subcategory->save();
        return response()->json([
            'success' => 1,
        ], 200);
    }
}
