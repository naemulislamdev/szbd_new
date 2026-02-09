<?php

namespace App\Http\Controllers\Backend;

use App\CPU\FileManager;
use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class CategoryController extends Controller
{
    public function index()
    {
        return view('admin.category.index');
    }

    public function datatables()
    {
        $query = Category::query();
        $query->latest('id');

        return DataTables::of($query)

            ->addIndexColumn()
            ->editColumn('icon', function ($row) {

                return '<img src="' . asset("assets/storage/category/" . $row->icon) . '"
                 alt="icon"
                 width="40"
                 height="40">';
            })


            ->addColumn('action', function ($row) {

                return '
                <button class="btn btn-primary btn-sm edit"
                    data-id="' . $row->id . '"
                    data-name="' . $row->name . '"
                    data-order="' . $row->order_number . '"
                    data-icon="' . asset($row->icon) . '"
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
            ->editColumn('home_status', function ($row) {

                $checked = $row->home_status == 1 ? 'checked' : '';

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
                'home_status',
            ])
            ->toJson();
    }

    public function store(Request $request)
    {
        $validinfo =  $request->validate([
            'name' => 'required',
            'icon' => 'required',
            'order_number' => 'required'
        ], [
            'name.required' => 'Category name is required!',
            'icon.required' => 'Category icon is required!',
            'order_number.required' => 'Category order number is required!',
        ]);

        $category = new Category;
        $category->name = $request->name;
        $category->slug = Str::slug($request->name);
        $category->icon = FileManager::uploadFile('category/', 300, $request->file('icon'));
        $category->order_number = $request->order_number;

        if ($category->save()) {
            return response()->json([
                'status' => true,
                'message' => 'Category created successfully'
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
            'order_number' => 'required'
        ], [
            'id.required' => 'Invalid category!',
            'id.numeric' => 'Invalid category!',
            'name.required' => 'Category name is required!',
            'order_number.required' => 'Category order number is required!',
        ]);

        $category = Category::find($request->id);
        $category->name = $request->name;
        $category->slug = Str::slug($request->name);
        if ($request->image) {
            $category->icon = FileManager::updateFile('category/', $category->icon, 300, $request->file('image'));
        }
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

    public function delete(Request $request)
    {
        $category = Category::find($request->id);
        if (file_exists(public_path('assets/storage/category/' . $category->icon))) {
            FileManager::delete('category/' . $category->icon);
        }
        $category->delete();

        return response()->json();
    }

    public function status(Request $request)
    {
        $category = Category::find($request->id);
        $category->home_status = $request->home_status;
        $category->save();
        return response()->json([
            'success' => 1,
        ], 200);
    }
}
