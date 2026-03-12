<?php

namespace App\Http\Controllers\Backend;

use App\CPU\FileManager;
use App\CPU\Helpers;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Models\Brand;
use App\Models\Category;
use App\Models\ChildCategory;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use App\Models\Review;
use App\Models\SubCategory;
use App\Services\ProductService;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ProductController extends Controller
{
    public function create()
    {
        $categories = Category::all();
        $brands = Brand::all();
        return view('admin.product.create', compact('categories', 'brands'));
    }
    // Store New Product
    public function store(ProductRequest $request)
    {

        $product = ProductService::store($request);
        return response()->json([
            'success' => true,
            'message' => 'Product added successfully'
        ]);
    }
    // Edit Product
    public function edit(Product $product)
    {
        $categories = Category::where('home_status', 1)->get();
        $subCategories = SubCategory::where('status', 1)->get();
        $childCategories = ChildCategory::where('status', 1)->get();
        $brands = Brand::all();
        return view('admin.product.edit', compact('product', 'categories', 'subCategories', 'childCategories', 'brands'));
    }
    // Update Product
    public function update(ProductRequest $request, $id)
    {
        //dd($id);
        $product = ProductService::update($request, $id);
        return response()->json([
            'success' => true,
            'message' => 'Product updated successfully'
        ]);
    }
    public function show(Product $product)
    {
        $reviews = Review::where(['product_id' => $product->id])->paginate(Helpers::pagination_limit());
        return view('admin.product.show', compact('product', 'reviews'));
    }
    public function index()
    {
        return view('admin.product.index');
    }
    // Product DataTable
    public function datatables(Request $request)
    {
        $query = Product::latest('id');
        if ($request->type == 'deactive') {
            $query->where('status', 0);
        }

        return DataTables::eloquent($query)
            ->addIndexColumn() // SL
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
            <a href="' . route('admin.product.edit', $data->id) . '" class="btn btn-sm btn-primary"><i class="la la-edit fs-18"></i></a>
            <a href="' . route('admin.product.show', $data->id) . '" class="btn btn-sm btn-danger"><i class="las la-eye"></i></a>
            <a id="delete" href="' . route('admin.product.delete', $data->id) . '" class="btn btn-sm btn-danger"><i class="la la-trash-alt text-secondary fs-18"></i></a>
            ';
            })

            ->rawColumns(['checkbox', 'photo', 'name', 'featured', 'arrival', 'status', 'action'])
            ->make(true);
    }
    public function remove_image(Request $request)
    {
        FileManager::delete('/asset/storage/product/' . $request['name']);
        $product = Product::find($request['id']);
        $array = [];
        if (count(json_decode($product['images'])) <= 1) {
            return back()->with('warning', 'You cannot delete all images!');
        }
        foreach (json_decode($product['images']) as $image) {
            if ($image != $request['name']) {
                array_push($array, $image);
            }
        }
        Product::where('id', $request['id'])->update([
            'images' => json_encode($array),
        ]);
        return back()->with('success', 'Product image removed successfully!');
    }
    public function color_combination(Request $request)
    {
        $options = [];
        if ($request->has('colors_active') && $request->has('colors') && count($request->colors) > 0) {
            $colors_active = 1;
            array_push($options, $request->colors);
        } else {
            $colors_active = 0;
        }

        $product_name = $request->name;

        $combinations = Helpers::combinations($options);
        return response()->json([
            'view' => view('admin.product.partials._color_combinations', compact('combinations', 'colors_active', 'product_name'))->render(),
        ]);
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

        $combinations = Helpers::combinations($options);
        return response()->json([
            'view' => view('admin.product.partials._sku_combinations', compact('combinations', 'unit_price', 'colors_active', 'product_name'))->render(),
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
    public function getSubCategories($category_id)
    {
        $subCategories = SubCategory::where('category_id', $category_id)
            ->where('status', 1)
            ->select('id', 'name')
            ->get();

        return response()->json($subCategories);
    }

    // Get Child Categories
    public function getChildCategories($subcategory_id)
    {
        $childCategories = ChildCategory::where('sub_category_id', $subcategory_id)
            ->where('status', 1)
            ->select('id', 'name')
            ->get();

        return response()->json($childCategories);
    }


    /* =====================================================
    Product Sales Report methods
========================================================
*/

    public function ProductSalesReport()
    {

        return view('admin.product.report.view');
    }

    // public function reportDatatables(Request $request)
    // {


    //     $query = Order::with('details') // eager load details
    //         ->leftJoin('shipping_addresses', 'orders.shipping_address', '=', 'shipping_addresses.id')
    //         ->select('orders.*', 'shipping_addresses.contact_person_name as shipping_name', 'shipping_addresses.phone as shipping_phone');


    //     // 🔹 Filter by status
    //     if ($request->status != 'all') {
    //         $query->where('order_status', $request->status);
    //     }
    //     // 🔹 Filter by date range
    //     if ($request->filled('from_date') && $request->filled('to_date')) {
    //         $query->whereDate('created_at', '>=', $request->from_date)
    //             ->whereDate('created_at', '<=', $request->to_date);
    //     } elseif ($request->filled('from_date')) {
    //         $query->whereDate('created_at', '>=', $request->from_date);
    //     } elseif ($request->filled('to_date')) {
    //         $query->whereDate('created_at', '<=', $request->to_date);
    //     }

    //     $orders = $query->latest('id')->get();

    //     // 🔹 Aggregate product data
    //     $productReport = [];

    //     foreach ($orders as $order) {
    //         foreach ($order->details as $detail) {
    //             $product = json_decode($detail->product_details, true);

    //             if (!$product) continue;

    //             $productId = $product['id'] ?? null;
    //             if (!$productId) continue;

    //             $qty = $detail->qty ?? 0;
    //             $price = $product['price'] ?? 0;
    //             $discount = $detail->discount ?? 0;
    //             $total = ($price * $qty) - $discount;

    //             if (!isset($productReport[$productId])) {
    //                 $productReport[$productId] = [
    //                     'purchase_price' => $product['purchase_price'] ?? '',
    //                     'unit_price' => $product['unit_price'] ?? '',
    //                     'choice_options' => $product['choice_options'] ?? '',
    //                     'code' => $product['code'] ?? '',
    //                     'thumbnail' => $product['thumbnail'] ?? null,
    //                     'total_qty' => 0,
    //                     'total_amount' => 0,
    //                     'total_discount' => 0,
    //                 ];
    //             }

    //             $productReport[$productId]['total_qty'] += $qty;
    //             $productReport[$productId]['total_amount'] += $total;
    //             $productReport[$productId]['total_discount'] += $discount;
    //         }
    //     }

    //     return DataTables::of(collect($productReport))
    //         ->addIndexColumn()
    //         ->addColumn('code', fn($row) => $row['code'])
    //         ->addColumn('image', function ($row) {
    //             $src = $row['thumbnail']
    //                 ? asset('assets/storage/product/thumbnail/' . $row['thumbnail'])
    //                 : asset('assets/noimage.png');
    //             return "<img src='" . $src . "' width='50'>";
    //         })
    //         ->addColumn('purchase_price', fn($row) => $row['purchase_price'])
    //         ->addColumn('sold_price', fn($row) => $row['unit_price'])
    //         ->addColumn('quantity', fn($row) => $row['total_qty'])
    //         ->addColumn('attribute', function ($row) {
    //             // 1️⃣ Check if choice_options exist
    //             if (!empty($row['choice_options'])) {
    //                 $choiceOptions = json_decode($row['choice_options'], true);

    //                 if ($choiceOptions) {
    //                     // Assume only first choice option is relevant (like Size)
    //                     $option = $choiceOptions[0];
    //                     $title = $option['title'] ?? $option['name'];
    //                     $values = implode(', ', $option['options']);
    //                     return $title . ': ' . $values;
    //                 }
    //             }

    //             // 2️⃣ If no choice_options, fallback to Pc
    //             return 'Pc';
    //         })
    //         ->rawColumns(['image', 'attribute'])
    //         ->toJson();
    // }
    public function reportDatatables(Request $request)
    {
        $query = Order::with('details') // eager load details
            ->leftJoin('shipping_addresses', 'orders.shipping_address', '=', 'shipping_addresses.id')
            ->select('orders.*', 'shipping_addresses.contact_person_name as shipping_name', 'shipping_addresses.phone as shipping_phone');

        // 🔹 Filter by status
        if ($request->status != 'all') {
            $query->where('order_status', $request->status);
        }

        // 🔹 Filter by date range
        if ($request->filled('from_date') && $request->filled('to_date')) {
            $query->whereDate('created_at', '>=', $request->from_date)
                ->whereDate('created_at', '<=', $request->to_date);
        } elseif ($request->filled('from_date')) {
            $query->whereDate('created_at', '>=', $request->from_date);
        } elseif ($request->filled('to_date')) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }

        $orders = $query->latest('id')->get();

        // 🔹 Aggregate product data & totals
        $productReport = [];
        $totals = [
            'total_qty' => 0,
            'total_price' => 0,
            'total_discount' => 0,
        ];

        foreach ($orders as $order) {
            foreach ($order->details as $detail) {
                $product = json_decode($detail->product_details, true);
                if (!$product) continue;

                $productId = $product['id'] ?? null;
                if (!$productId) continue;

                $qty = $detail->qty ?? 0;
                $price = $product['unit_price'] ?? 0; // <-- unit_price fix
                $discount = $detail->discount ?? 0;
                $total = ($price * $qty) - $discount;

                // 🔹 Update page-wide totals
                $totals['total_qty'] += $qty;
                $totals['total_price'] += $total;
                $totals['total_discount'] += $discount;

                // 🔹 Aggregate per product
                if (!isset($productReport[$productId])) {
                    $productReport[$productId] = [
                        'purchase_price' => $product['purchase_price'] ?? '',
                        'unit_price' => $product['unit_price'] ?? '',
                        'choice_options' => $product['choice_options'] ?? '',
                        'code' => $product['code'] ?? '',
                        'thumbnail' => $product['thumbnail'] ?? null,
                        'total_qty' => 0,
                        'total_amount' => 0,
                        'total_discount' => 0,
                    ];
                }

                $productReport[$productId]['total_qty'] += $qty;
                $productReport[$productId]['total_amount'] += $total;
                $productReport[$productId]['total_discount'] += $discount;
            }
        }

        return DataTables::of(collect($productReport))
            ->addIndexColumn()
            ->addColumn('code', fn($row) => $row['code'])
            ->addColumn('image', function ($row) {
                $src = $row['thumbnail']
                    ? asset('assets/storage/product/thumbnail/' . $row['thumbnail'])
                    : asset('assets/noimage.png');
                return "<img src='" . $src . "' width='50'>";
            })
            ->addColumn('purchase_price', fn($row) => $row['purchase_price'])
            ->addColumn('sold_price', fn($row) => $row['unit_price'])
            ->addColumn('quantity', fn($row) => $row['total_qty'])
            ->addColumn('attribute', function ($row) {
                if (!empty($row['choice_options'])) {
                    $choiceOptions = json_decode($row['choice_options'], true);
                    if ($choiceOptions) {
                        $option = $choiceOptions[0];
                        $title = $option['title'] ?? $option['name'];
                        $values = implode(', ', $option['options']);
                        return $title . ': ' . $values;
                    }
                }
                return 'Pc';
            })
            ->rawColumns(['image', 'attribute'])
            ->with('totals', $totals) // <-- totals pass
            ->toJson();
    }
}
