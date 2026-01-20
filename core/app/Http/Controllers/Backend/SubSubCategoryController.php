<?php

namespace App\Http\Controllers\Backend;

use App\CPU\Helpers;
use App\Http\Controllers\Controller;
use App\Model\Category;
use App\Models\ChildCategory;
use App\Models\SubCategory;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SubSubCategoryController extends Controller
{
    public function index(Request $request)
    {
        $query_param = [];
        $search = $request['search'];
        if($request->has('search'))
        {
            $key = explode(' ', $request['search']);
            $childCategories = ChildCategory::orderBy('order_number','asc')->where(function ($q) use ($key) {
                foreach ($key as $value) {
                    $q->orWhere('name', 'like', "%{$value}%");
                }
            });
            $query_param = ['search' => $request['search']];
        }else{
            $childCategories=ChildCategory::orderBy('order_number','asc');
        }
        $childCategories = $childCategories->latest()->paginate(Helpers::pagination_limit())->appends($query_param);
        return view('admin-views.category.child_category',compact('childCategories','search'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required',
            'sub_category_id' => 'required',
            'name' => 'required|string|max:255',
            'order_number' => 'required'
        ]);

        $category = new ChildCategory;
        $category->category_id = $request->category_id;
        $category->sub_category_id = $request->sub_category_id;
        $category->name = $request->name;
        $category->slug = Str::slug($request->name);
        $category->order_number = $request->order_number;
        $category->save();

        Toastr::success('Child Category stored successfully!');
        return back();
    }

    public function edit(Request $request)
    {
        $data = Category::where('id',$request->id)->first();
        return response()->json($data);
    }
    public function update(Request $request, $id)
    {
        $request->validate([
            'category_id' => 'required',
            'sub_category_id' => 'required',
            'name' => 'required|string|max:255',
            'order_number' => 'required'
        ]);

        $childcat = ChildCategory::find($id);
        $childcat->category_id = $request->category_id;
        $childcat->sub_category_id = $request->sub_category_id;
        $childcat->name = $request->name;
        $childcat->slug = Str::slug($request->name);
        $childcat->order_number = $request->order_number;
        $childcat->save();

        Toastr::success('Child Category updated successfully!');
        return back();
    }
    public function delete(Request $request)
    {
        $childcat = ChildCategory::find($request->id);
        $childcat->delete();
        return response()->json();
    }
    public function fetch(Request $request){
        if($request->ajax())
        {
            $data = ChildCategory::orderBy('id','desc')->get();
            return response()->json($data);
        }
    }

    public function getSubCategory(Request $request)
    {
        $data = SubCategory::where("category_id",$request->id)->get();
        $output="";
        foreach($data as $row)
        {
            $output .= '<option value="'.$row->id.'">'.$row->name.'</option>';
        }
        echo $output;
    }

    public function getCategoryId(Request $request)
    {
        $data= Category::where('id',$request->id)->first();
        return response()->json($data);
    }
}
