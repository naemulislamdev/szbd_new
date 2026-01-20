<?php

namespace App\Http\Controllers\Backend;

use App\CPU\Helpers;
use App\Http\Controllers\Controller;
use App\Models\SubCategory;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SubCategoryController extends Controller
{
    public function index(Request $request)
    {
        $query_param = [];
        $search = $request['search'];
        if ($request->has('search')) {
            $key = explode(' ', $request['search']);
            $subcategories = SubCategory::orderBy('order_number','asc')->where(function ($q) use ($key) {
                foreach ($key as $value) {
                    $q->orWhere('name', 'like', "%{$value}%");
                }
            });
            $query_param = ['search' => $request['search']];
        } else {
            $subcategories = SubCategory::orderBy('order_number','asc');
        }
        $subcategories = $subcategories->latest()->paginate(Helpers::pagination_limit())->appends($query_param);
        return view('admin-views.category.sub_category', compact('subcategories', 'search'));
    }

    public function store(Request $request)
    {
         $request->validate([
            'category_id' => 'required',
            'name' => 'required|string|max:255',
            'order_number' => 'required'
        ]);
        $sub = new SubCategory();
        $sub->category_id = $request->category_id;
        $sub->name = $request->name;
        $sub->slug = Str::slug($request->name);
        $sub->order_number = $request->order_number;
        $sub->save();

        Toastr::success('Category updated successfully!');
        return back();
    }

    public function edit(Request $request, $id)
    {
        $data = SubCategory::where('id', $id)->first();

        return response()->json($data);
    }

    public function update(Request $request, $id)
    {
        $sub = SubCategory::find($id);
        $sub->category_id = $request->category_id;
        $sub->name = $request->name;
        $sub->slug = Str::slug($request->name);
        $sub->order_number = $request->order_number;
        $sub->save();
        Toastr::success('Sub Category updated successfully!');
        return back();
    }

    public function delete(Request $request)
    {
        $sub = SubCategory::find($request->id);
        $sub->delete();
        return response()->json();
    }

    public function fetch(Request $request)
    {
        if ($request->ajax()) {
            $data = SubCategory::where('status', 1)->orderBy('id', 'desc')->get();
            return response()->json($data);
        }
    }
}
