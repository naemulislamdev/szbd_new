<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ProductController extends Controller
{
    public function create()
    {
        $categories = Category::all();
        // $branches = Branch::all();
        // $brands = Brand::all();
        return view('admin.product.create', compact('categories'));
    }
    public function store(Request $request)
    {

        $request->validate([
            'name' => 'required',
            'description' => 'required'
        ]);
    }
    public function index()
    {
        return view('admin.product.index');
    }
    public function datatables(Request $request)
    {
        $query = Product::latest('id');
        if ($request->type == 'deactive') {
            $query->where('status', 0);
        }

        return DataTables::eloquent($query)

            ->addColumn('checkbox', function ($data) {
                return '<input type="checkbox" class="row-checkbox" value="' . $data->id . '">';
            })

            ->editColumn('photo', function ($data) {
                $photo = $data->thumbnail
                    ? asset('assets/storage/product/thumbnail/' . $data->thumbnail)
                    : asset('assets/noimage.png');

                return '<img src="' . $photo . '" width="60">';
            })

            ->editColumn('name', function ($data) {
                return '<a href="' . route('admin.product.show', $data->id) . '">' . $data->name . '</a>';
            })

            ->editColumn('purchase_price', fn($data) => $data->purchase_price . ' ৳')
            ->editColumn('unit_price', fn($data) => $data->unit_price . ' ৳')

            ->addColumn('featured', function ($data) {
                return '
    <div class="form-check form-switch">
        <input class="form-check-input change-featured" type="checkbox" data-id="' . $data->id . '" ' . ($data->featured ? 'checked' : '') . '>
    </div>';
            })
            ->addColumn('arrival', function ($data) {
                return '
    <div class="form-check form-switch">
        <input class="form-check-input change-arrival" type="checkbox" data-id="' . $data->id . '" ' . ($data->arrival ? 'checked' : '') . '>
    </div>';
            })
            ->addColumn('status', function ($data) {
                return '
    <div class="form-check form-switch">
        <input class="form-check-input change-status" type="checkbox" data-id="' . $data->id . '" ' . ($data->status ? 'checked' : '') . '>
    </div>';
            })

            ->addColumn('action', function ($data) {
                return '
            <a href="' . route('admin.product.edit', $data->id) . '" class="btn btn-sm btn-primary mb-2"><i class="la la-edit"></i></a>
            <a id="delete" href="' . route('admin.product.delete', $data->id) . '" class="btn btn-sm btn-danger"><i class="la la-trash-alt"></i></a>';
            })

            ->rawColumns(['checkbox', 'photo', 'name', 'featured', 'arrival', 'status', 'action'])
            ->make(true);
    }
    public function sku_combination(Request $request)
    {
        $options = [];
        if ($request->has('colors_active') && $request->has('colors') && count($request->colors) > 0) {
            $colors_active = 1;
            array_push($options, $request->colors);
        } else {
            $colors_active = 0;
        }

        $unit_price = $request->unit_price;
        $product_name = $request->name;

        if ($request->has('choice_no')) {
            foreach ($request->choice_no as $key => $no) {
                $name = 'choice_options_' . $no;
                $my_str = implode('', $request[$name]);
                array_push($options, explode(',', $my_str));
            }
        }

        // $combinations = Helpers::combinations($options);
        return response()->json([
            'view' => view('admin-views.product.partials._sku_combinations', compact('combinations', 'unit_price', 'colors_active', 'product_name'))->render(),
        ]);
    }
    public function delete(Product $product)
    {
        $file = $product->thumbnail;
        if ($file) {
            $file_path = public_path('assets/storage/product/thumbnail/' . $file);
            if (file_exists($file_path)) {
                unlink($file_path);
            }
        }
        $product->delete();
        return to_route('admin.product.index')->with('success', 'Product deleted successfully');
    }
    public function updateFeatured(Request $request)
    {
        $product = Product::findOrFail($request->id);

        $product->featured = $request->featured;
        $product->save();

        return response()->json([
            'success' => true,
            'message' => 'Featured Status updated successfully'
        ]);
    }
    public function updateArrival(Request $request)
    {
        $product = Product::findOrFail($request->id);

        $product->arrival = $request->arrival;
        $product->save();

        return response()->json([
            'success' => true,
            'message' => 'Arrival Status updated successfully'
        ]);
    }
    public function statusUpdate(Request $request)
    {
        $product = Product::findOrFail($request->id);

        $product->status = $request->status;
        $product->save();

        return response()->json([
            'success' => true,
            'message' => 'Status updated successfully'
        ]);
    }
}
