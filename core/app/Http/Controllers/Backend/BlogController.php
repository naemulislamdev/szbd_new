<?php

namespace App\Http\Controllers\Backend;

use App\CPU\FileManager;
use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\BlogCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;
use Carbon\Carbon;

class BlogController extends Controller
{
    // Category Management
    public function categoryList()
    {
        return view('admin.blog.category.index');
    }
    public function categoryDatatables()
    {
        $query = BlogCategory::query();
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

            // Edit Column
            ->editColumn('status', function ($row) {

                $checked = $row->status == 1 ? 'checked' : '';

                return '
                        <div class="form-check form-switch">
                            <input class="form-check-input status"
                                type="checkbox"
                                name="status"
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
                'status',
            ])
            ->toJson();
    }
    public function categoryDelete(Request $request)
    {
        $blog_category = BlogCategory::find($request->id);
        $blog_category->delete();
        return response()->json();
    }

    public function categoryStatus(Request $request)
    {
        $category = BlogCategory::find($request->id);
        $category->status = $request->status;
        $category->save();
        return response()->json([
            'success' => 1,
        ], 200);
    }
    public function categoryStore(Request $request)
    {

        $validinfo =  $request->validate([
            'name' => 'required',
        ]);

        $category = new BlogCategory;
        $category->name = $request->name;
        $category->slug = Str::slug($request->name);

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
    public function categoryUpdate(Request $request)
    {
        $validinfo = $request->validate([
            'id' => 'required|numeric',
        ]);

        $category = BlogCategory::find($request->id);
        $category->name = $request->name;
        $category->slug = Str::slug($request->name);
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

    public function list()
    {
        return view('admin.blog.index');
    }
    public function datatables()
    {
        $query = Blog::query();
        $query->latest('id');
        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {

                $editUrl = route('admin.blog.edit', $row->id);

                return '
        <a href="' . $editUrl . '" class="btn btn-primary btn-sm edit">
            <i class="la la-edit"></i>
        </a>

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
                                name="status"
                                data-id="' . $row->id . '"
                                value="1"
                                ' . $checked . '
                                id="flexSwitch' . $row->id . '">
                            <label class="form-check-label" for="flexSwitch' . $row->id . '"></label>
                        </div>
    ';
            })

            ->editColumn('created_at', function ($row) {
                return Carbon::parse($row->created_at)->format('d F Y');
            })
            ->editColumn('image', function ($row) {
                return '<img src="' . asset('assets/storage/blogs/' . $row->image) . '" alt="Blog Image" style="width:80px;height:auto;" />';
            })
            ->editColumn('category_id', function ($row) {
                return $row->blogCategory ? $row->blogCategory->name : "";
            })

            ->rawColumns([
                'action',
                'status',
                'created_at',
                'image',
                'category_id'
            ])
            ->toJson();
    }
    public function status(Request $request)
    {
        $category = Blog::find($request->id);
        $category->status = $request->status;
        $category->save();
        return response()->json([
            'success' => 1,
        ], 200);
    }
    public function create()
    {
        $categories = BlogCategory::where('status', '1')->get();
        return view('admin.blog.create', compact('categories'));
    }
    public function store(Request $request)
    {
        $request->validate([
            "title" => 'required|string|max:255',
            "category_id" => 'required',
            "description" => 'required|string',
            "image" => 'required|max:2048'
        ], [
            'category_id.required' => "Please select category",
        ]);

        $blog = new Blog();
        $blog->title = $request->title;
        $blog->slug = Str::slug($request->title);
        $blog->category_id = $request->category_id;
        $blog->description = $request->description;

        $blog->meta_title = $request->meta_title;
        $blog->meta_keywords = $request->meta_keywords;
        $blog->meta_description = $request->meta_description;

        // ✅ Image Upload
        if ($request->hasFile('image')) {
            $blog->image = FileManager::uploadFile('blogs/', 300, $request->file('image'));
        }

        $blog->save();

        return redirect()->back()->with('success', 'Blog created successfully');
    }
    public function edit($id)
    {
        $categories = BlogCategory::where("status", 1)->get();
        $blog = Blog::find($id)->first();

        return view('admin.blog.edit', compact('categories', 'blog'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            "title" => 'required|string|max:255',
            "category_id" => 'required',
            "description" => 'required|string',
        ], [
            'category_id.required' => "Please select category",
        ]);
        $blog = Blog::find($id)->first();
        $blog->title = $request->title;
        $blog->slug = Str::slug($request->title);
        $blog->category_id = $request->category_id;
        $blog->description = $request->description;

        $blog->meta_title = $request->meta_title;
        $blog->meta_keywords = $request->meta_keywords;
        $blog->meta_description = $request->meta_description;

        // ✅ Image Upload
        if ($request->hasFile('image')) {
            $blog->image = FileManager::updateFile('blogs/', $blog->image, $request->file('image'));
        }
        $blog->save();

        return redirect()->Route("admin.blog.list")->with('success', 'Blog Updated successfully');
    }
}
