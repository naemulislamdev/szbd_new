<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ProductController extends Controller
{
    public function create()
    {
        return view('admin.product.create');
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
            <a href="' . route('admin.product.edit', $data->id) . '" class="btn btn-sm btn-primary">Edit</a>
            <a id="delete" href="' . route('admin.product.delete', $data->id) . '" class="btn btn-sm btn-danger">Delete</a>';
            })

            ->rawColumns(['checkbox', 'photo', 'name', 'featured', 'arrival', 'status', 'action'])
            ->make(true);
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
