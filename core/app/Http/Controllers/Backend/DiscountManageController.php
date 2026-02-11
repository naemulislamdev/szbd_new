<?php

namespace App\Http\Controllers\Backend;

use App\CPU\BackEndHelper;
use App\CPU\FileManager;
use App\Http\Controllers\Controller;
use App\Models\BatchDiscount;
use App\Models\Category;
use App\Models\DiscountOffer;
use App\Models\EidOffer;
use App\Models\FlatDiscount;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class DiscountManageController extends Controller
{
    /* =============================================
            Flat Discount Starts
       =============================================

    */
    public function discountFlat()
    {
        $categories = Category::all();
        return view('admin.discount.flat.index', compact('categories'));
    }
    public function flatDatatables()
    {
        $query = FlatDiscount::query();
        $query->latest('id');
        $query->with('category');
        return DataTables::of($query)

            ->addIndexColumn()
            ->editColumn('created_at', function ($row) {
                return $row->created_at->format('d M Y');
            })

            ->addColumn('action', function ($row) {

                return '
                <button class="btn btn-primary btn-sm edit"
                    data-id="' . $row->id . '"
                    data-amount="' . $row->discount_amount . '"
                    data-category="' . $row->category_id . '"
                    data-type="' . $row->discount_type . '"
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
            ->editColumn('category_id', function ($row) {
                return $row->category ? $row->category->name : 'All Categories';
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
                'created_at',
                'status',
            ])
            ->toJson();
    }

    public function flatStatus(Request $request)
    {
        $flat = FlatDiscount::find($request->id);
        $flat->status = $request->status;
        $flat->save();
        return response()->json([
            'success' => 1,
        ], 200);
    }

    public function flatStore(Request $request)
    {
        // Validate the request data
        $request->validate([
            'category' => 'required|string',
            'discount_amount' => 'required|numeric|min:0',
            'discount_type' => 'required|in:flat,percent',
        ]);

        $discountAmount = $request->discount_amount;
        $discountType   = $request->discount_type;

        // Query products depending on category
        $products = Product::where('status', 1)->get();

        $foundAnyProduct = false;

        foreach ($products as $product) {
            $categoryIds = json_decode($product->category_ids, true);
            $ids = array_column($categoryIds, 'id');

            // Check if this product belongs to the selected category OR all-category
            if ($request->category === 'all-category' || in_array($request->category, $ids)) {
                $foundAnyProduct = true;

                if ($discountType === 'flat') {
                    $product->discount = $discountAmount;
                    $product->discount_type = 'flat';
                } elseif ($discountType === 'percent') {
                    $product->discount = $discountAmount;
                    $product->discount_type = 'percent';
                }

                $product->save();
            }
        }

        if (!$foundAnyProduct) {
            return redirect()->back()->with('error', 'No products found for the selected category.');
        }

        // Save discount record
        $discount = new FlatDiscount();
        $discount->category_id     = $request->category;
        $discount->discount_amount = $discountAmount;
        $discount->discount_type   = $discountType;
        $discount->save();

        return response()->json([
            'success' => 1,
            'message' => 'Flat discount added successfully'
        ], 200);
    }

    public function flatUpdate(Request $request)
    {

        // Validate the request data
        $request->validate([
            'category' => 'required|string',
            'discount_amount' => 'required|numeric|min:0',
            'discount_type' => 'required|in:flat,percent',
        ]);

        $discountAmount = $request->discount_amount;
        $discountType   = $request->discount_type;

        // Query products depending on category
        $products = Product::where('status', 1)->get();

        $foundAnyProduct = false;

        foreach ($products as $product) {
            $categoryIds = json_decode($product->category_ids, true);
            $ids = array_column($categoryIds, 'id');

            // Check if this product belongs to the selected category OR all-category
            if ($request->category === 'all-category' || in_array($request->category, $ids)) {
                $foundAnyProduct = true;

                if ($discountType === 'flat') {
                    $product->discount = $discountAmount;
                    $product->discount_type = 'flat';
                } elseif ($discountType === 'percent') {
                    $product->discount = $discountAmount;
                    $product->discount_type = 'percent';
                }

                $product->save();
            }
        }

        if (!$foundAnyProduct) {
            return response()->json([
                'success' => 0,
                'message' => 'No products found for the selected category.'
            ], 404);
        }

        // update discount record
        $discount = FlatDiscount::findOrFail($request->id);
        $discount->category_id     = $request->category;
        $discount->discount_amount = $discountAmount;
        $discount->discount_type   = $discountType;
        $discount->save();

        return response()->json([
            'success' => 1,
            'message' => 'Flat discount updated successfully'
        ], 200);
    }
    public function flatDelete(Request $request)
    {
        $flatDiscount = FlatDiscount::findOrFail($request->id);
        $categoryId = $flatDiscount->category_id;

        // Query products depending on category
        $products = Product::where('status', 1)->get();

        foreach ($products as $product) {
            $categoryIds = json_decode($product->category_ids, true);
            $ids = array_column($categoryIds, 'id');

            if (in_array($categoryId, $ids)) {
                // Reset discount fields
                $product->discount = 0;
                $product->discount_type = null;
                $product->save();
            }
        }

        $flatDiscount->delete();

        return redirect()->route('admin.discount.flat')->with('success', 'Flat discount deleted successfully.');
    }

    /* =============================================
            Flat Discount Ends
=============================================

    */
    /* =============================================
            Batch Discount Start
       =============================================

    */
    public function discountBatch()
    {
        return view('admin.discount.batch.index');
    }
    public function batchDatatables()
    {
        $query = BatchDiscount::query();
        $query->latest('id');

        return DataTables::of($query)

            ->addIndexColumn()
            ->editColumn('created_at', function ($row) {
                return $row->created_at->format('d M Y');
            })

            ->addColumn('action', function ($row) {

                return '
                <a href="' . route('admin.discount.batch.edit', $row->id) . '" class="btn btn-primary btn-sm edit">
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

            ->editColumn('total_products', function ($row) {

                $productIds = json_decode($row->product_ids, true);
                $total = is_array($productIds) ? count($productIds) : 0;

                return '<a href="' . route('admin.discount.batch.product', $row->id) . '" class="btn btn-sm btn-dark">
                Total Products: <span class="badge bg-success">' . $total . '</span>
            </a>';
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
                'created_at',
                'status',
                'total_products',
            ])
            ->toJson();
    }

    public function createBatch()
    {
        $products = Product::where('status', 1)->get();
        return view('admin.discount.batch.create', compact('products'));
    }

    public function batchStore(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'discount_amounts' => 'required|array',
            'discount_types' => 'required|array',
            'product_ids' => 'required|array',
            'product_ids.*' => 'exists:products,id',
        ]);
        $products = $request->product_ids;
        foreach ($products as $productId) {
            $amount = $request->discount_amounts[$productId] ?? 0;
            $type = $request->discount_types[$productId] ?? 'flat';

            $product = Product::find($productId);
            if ($product) {
                $product->discount = $type == 'flat' ? $amount : $amount;
                $product->discount_type = $type;
                $product->save();
            }
        }


        // Store the batch discount
        BatchDiscount::create([
            'title' => $request->title,
            'discount_amount' => json_encode($request->discount_amounts),
            'discount_type' => json_encode($request->discount_types),
            'product_ids' => json_encode($request->product_ids),
            'status' => true
        ]);
        return redirect()->route('admin.discount.batch')->with('success', 'Batch discount created successfully.');
    }
    public function editBatch($id)
    {
        $batchDiscount = BatchDiscount::findOrFail($id);
        $products = Product::where('status', 1)->get();
        return view('admin.discount.batch.edit', compact('batchDiscount', 'products'));
    }

    public function batchUpdate(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'discount_amounts' => 'required|array',
            'discount_types' => 'required|array',
            'product_ids' => 'required|array',
            'product_ids.*' => 'exists:products,id',
        ]);
        $products = $request->product_ids;
        foreach ($products as $productId) {
            $amount = $request->discount_amounts[$productId] ?? 0;
            $type = $request->discount_types[$productId] ?? 'flat';

            $product = Product::find($productId);
            if ($product) {
                $product->discount = $type == 'flat' ? $amount : $amount;
                $product->discount_type = $type;
                $product->save();
            }
        }


        // Store the batch discount
        $batchDiscount = BatchDiscount::findOrFail($id);
        $batchDiscount->update([
            'title' => $request->title,
            'discount_amount' => json_encode($request->discount_amounts),
            'discount_type' => json_encode($request->discount_types),
            'product_ids' => json_encode($request->product_ids),
        ]);
        return redirect()->route('admin.discount.batch')->with('success', 'Batch discount updated successfully.');
    }
    public function batchDelete(Request $request)
    {
        $batchDiscount = BatchDiscount::findOrFail($request->id);

        $productIds = json_decode($batchDiscount->product_ids, true);
        Product::whereIn('id', $productIds)->update(['discount' => 0, 'discount_type' => null]);

        $batchDiscount->delete();
        return response()->json(['success' => true], 200);
    }
    public function batchStatus(Request $request)
    {
        $category = BatchDiscount::find($request->id);
        $category->status = $request->status;
        $category->save();
        return response()->json([
            'success' => 1,
        ], 200);
    }
    public function discountBatchProduct($id)
    {
        $batchDiscount = BatchDiscount::findOrFail($id);

        // $productIds = json_decode($batchDiscount->product_ids, true);
        // $products = Product::whereIn('id', $productIds)->get();
        return view('admin.discount.batch.see_products', compact('batchDiscount'));
    }
    public function discountBatchRemoveProduct(Request $request)
    {
        $id = $request->id;
        $product = Product::findOrFail($id);
        $product->discount = 0;
        $product->discount_type = null;
        $product->save();

        // Remove product from any batch discount
        $batchDiscounts = BatchDiscount::all();

        foreach ($batchDiscounts as $batchDiscount) {
            $productIds = json_decode($batchDiscount->product_ids, true) ?? [];
            $discountAmounts = json_decode($batchDiscount->discount_amount, true) ?? [];
            $discountTypes = json_decode($batchDiscount->discount_type, true) ?? [];

            // If product exists in this batch
            if (in_array($id, $productIds)) {
                // Remove product ID
                $updatedProductIds = array_diff($productIds, [$id]);
                $batchDiscount->product_ids = json_encode(array_values($updatedProductIds));

                // Remove discount amount & type
                unset($discountAmounts[$id]);
                unset($discountTypes[$id]);

                $batchDiscount->discount_amount = json_encode($discountAmounts);
                $batchDiscount->discount_type = json_encode($discountTypes);

                $batchDiscount->save();
            }
        }

        return response()->json(['success' => true], 200);
    }
    public function batchProductsDatatables($productIds)
    {
        // JSON string → PHP array
        $productIds = json_decode($productIds, true);

        // যদি empty বা invalid হয় → empty datatable return
        if (!is_array($productIds) || empty($productIds)) {
            return DataTables::of(collect())->toJson();
        }

        // শুধু যেসব id আছে সেগুলোই আনবে
        $query = Product::whereIn('id', $productIds)
            ->latest('id');

        return DataTables::of($query)

            ->addIndexColumn()

            ->addColumn('discount', function ($row) {

                if ($row->discount_type === 'flat') {
                    return $row->discount . ' (Flat)';
                }

                return $row->discount . '%';
            })

            ->addColumn('name', fn($row) => $row->name)
            ->addColumn('code', fn($row) => $row->code)
            ->addColumn('price', fn($row) => $row->unit_price)

            ->addColumn('action', function ($row) {
                return '
                <button class="btn btn-danger btn-sm delete"
                        title="Delete"
                        data-id="' . $row->id . '">
                    <i class="la la-trash"></i>
                </button>
            ';
            })

            ->rawColumns(['action', 'discount', 'name', 'code', 'price'])

            ->toJson();
    }


    /* =============================================
            Batch Discount Ends
       =============================================

    */

    /* =============================================
            Discount Offers Function start
       =============================================

    */
    public function discountOffers()
    {
        // $discountOffers = DiscountOffer::all();
        return view('admin.discount.offers.index');
    }
    public function discountOffersDatatables()
    {
        $query = DiscountOffer::query();
        $query->latest('id');

        return DataTables::of($query)

            ->addIndexColumn()
            ->editColumn('created_at', function ($row) {
                return $row->created_at->format('d M Y');
            })

            ->addColumn('action', function ($row) {

                return '
                <a href="' . route('admin.discount.discount-offers.edit', $row->id) . '" class="btn btn-primary btn-sm edit">
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

            ->editColumn('image', function ($row) {
                return '<img src="' . asset('assets/storage/offer/', $row->image) . '" alt="' . $row->title . '" width="80">';
            })
            ->editColumn('image', function ($row) {
                $image = $row->image
                    ? asset('assets/storage/offer/' . $row->image)
                    : '';

                return '<img src="' . $image . '" alt="' . $row->title . '" width="80">';
            })


            ->editColumn('total_products', function ($row) {

                $productIds = json_decode($row->product_ids, true);
                $total = is_array($productIds) ? count($productIds) : 0;

                return '<a href="' . route('admin.discount.discount-offers.product', $row->id) . '" class="btn btn-sm btn-dark">
                Total Products: <span class="badge bg-success">' . $total . '</span>
            </a>';
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
                'created_at',
                'status',
                'total_products',
                'image',
            ])
            ->toJson();
    }
    public function discountOffersCreate()
    {
        $products = Product::where('status', 1)->get();
        return view('admin.discount.offers.create', compact('products'));
    }

    public function discountOffersStore(Request $request)
    {

        $request->validate([
            'title' => 'required|string|max:255',
            'image' => 'required|mimetypes:image/*|max:5120',
            'discount_amounts' => 'required|array',
            'discount_types' => 'required|array',
            'product_ids' => 'required|array',
            'product_ids.*' => 'exists:products,id',
        ]);
        $products = $request->product_ids;
        foreach ($products as $productId) {
            $amount = $request->discount_amounts[$productId] ?? 0;
            $type = $request->discount_types[$productId] ?? 'flat';

            $product = Product::find($productId);
            if ($product) {
                $product->discount = $type == 'flat' ? $amount : $amount;
                $product->discount_type = $type;
                $product->save();
            }
        }

        // Store the batch discount

        DiscountOffer::create([
            'title' => $request->title,
            'slug' => Str::slug($request->title),
            'image' => FileManager::uploadFile('offer/', 300, $request->file('image'), $request->title),
            'discount_amount' => json_encode($request->discount_amounts),
            'discount_type' => json_encode($request->discount_types),
            'product_ids' => json_encode($request->product_ids),
            'status' => true
        ]);
        return redirect()->route('admin.discount.offers')->with('success', 'Discount offer created successfully.');
    }
    public function discountOffersEdit($id)
    {
        $discountOffer = DiscountOffer::findOrFail($id);
        $products = Product::where('status', 1)->get();
        return view('admin.discount.offers.edit', compact('discountOffer', 'products'));
    }
    public function discountOffersUpdate(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'discount_amounts' => 'required|array',
            'discount_types' => 'required|array',
            'product_ids' => 'required|array',
            'product_ids.*' => 'exists:products,id',
        ]);
        $products = $request->product_ids;

        foreach ($products as $productId) {
            $amount = $request->discount_amounts[$productId] ?? 0;
            $type = $request->discount_types[$productId] ?? 'flat';

            $product = Product::find($productId);
            if ($product) {
                $product->discount = $type == 'flat' ? $amount : $amount;
                $product->discount_type = $type;
                $product->save();
            }
        }


        // Store the batch discount
        $discountOffer = DiscountOffer::findOrFail($id);


        $data = [
            'title' => $request->title,
            'slug' => Str::slug($request->title),
            'discount_amount' => json_encode($request->discount_amounts),
            'discount_type' => json_encode($request->discount_types),
            'product_ids' => json_encode($request->product_ids),
        ];

        if ($request->hasFile('image')) {
            $data['image'] = FileManager::updateFile('offer/', $discountOffer->image, $request->file('image'), $request->title);
        }

        $discountOffer->update($data);

        return redirect()->route('admin.discount.offers')->with('success', 'Discount offers updated successfully.');
    }
    public function offersDelete(Request $request)
    {
        $discountOffer = DiscountOffer::findOrFail($request->id);
        $productIds = json_decode($discountOffer->product_ids, true);
        Product::whereIn('id', $productIds)->update(['discount' => 0, 'discount_type' => null]);
        if ($discountOffer->image) {
            FileManager::delete('offer/' . $discountOffer->image);
        }
        if ($discountOffer->delete()) {
            return response()->json(['success' => true]);
        } else {
            return response()->json(['success' => false], 500);
        }
    }
    public function discountOffersProduct($id)
    {
        $discountOffer = DiscountOffer::findOrFail($id);

        // $products = Product::whereIn('id', $productIds)->get();
        return view('admin.discount.offers.see_product', compact('discountOffer'));
    }
    public function discountOffersRemoveProduct(Request $request)
    {
        $id = $request->id;
        $product = Product::findOrFail($id);
        $product->discount = 0;
        $product->discount_type = null;
        $product->save();

        // Remove product from any batch discount
        $discountOffers = DiscountOffer::all();

        foreach ($discountOffers as $discountOffer) {
            $productIds = json_decode($discountOffer->product_ids, true) ?? [];
            $discountAmounts = json_decode($discountOffer->discount_amount, true) ?? [];
            $discountTypes = json_decode($discountOffer->discount_type, true) ?? [];

            // If product exists in this batch
            if (in_array($id, $productIds)) {
                // Remove product ID
                $updatedProductIds = array_diff($productIds, [$id]);
                $discountOffer->product_ids = json_encode(array_values($updatedProductIds));

                // Remove discount amount & type
                unset($discountAmounts[$id]);
                unset($discountTypes[$id]);

                $discountOffer->discount_amount = json_encode($discountAmounts);
                $discountOffer->discount_type = json_encode($discountTypes);

                $discountOffer->save();
            }
        }

        return response()->json(['success' => true], 200);
    }
    public function discountOffersStatus(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:discount_offers,id',
        ]);

        $discountOffer = DiscountOffer::findOrFail($request->id);
        $discountOffer->status = !$discountOffer->status;
        $discountOffer->save();

        return response()->json(['success' => true, 'status' => $discountOffer->status]);
    }
    public function offersProductsDatatables($productIds)
    {

        // JSON string → PHP array
        $productIds = json_decode($productIds, true);

        // যদি empty বা invalid হয় → empty datatable return
        if (!is_array($productIds) || empty($productIds)) {
            return DataTables::of(collect())->toJson();
        }

        // শুধু যেসব id আছে সেগুলোই আনবে
        $query = Product::whereIn('id', $productIds)
            ->latest('id');

        return DataTables::of($query)

            ->addIndexColumn()

            ->addColumn('discount', function ($row) {

                if ($row->discount_type === 'flat') {
                    return $row->discount . ' (Flat)';
                }

                return $row->discount . '%';
            })

            ->addColumn('name', fn($row) => $row->name)
            ->addColumn('code', fn($row) => $row->code)
            ->addColumn('price', fn($row) => $row->unit_price)

            ->addColumn('action', function ($row) {
                return '
                <button class="btn btn-danger btn-sm delete"
                        title="Delete"
                        data-id="' . $row->id . '">
                    <i class="la la-trash"></i>
                </button>
            ';
            })

            ->rawColumns(['action', 'discount', 'name', 'code', 'price'])

            ->toJson();
    }


    /* =============================================
          Discount Offers Function Ends
       =============================================

    */

    /* =============================================
            Eid Offer ✨🌙
       =============================================

    */


    public function eidOffers()
    {
        $eidOffers = EidOffer::latest()->get();
        return view('admin.discount.eid_offer.index', compact('eidOffers'));
    }
    public function eidOffersDatatables()
    {
        $query = EidOffer::query();
        $query->latest('id');

        return DataTables::of($query)

            ->addIndexColumn()
            ->editColumn('created_at', function ($row) {
                return $row->created_at->format('d M Y');
            })

            ->addColumn('action', function ($row) {

                return '
                <a href="' . route('admin.discount.eid-offers.edit', $row->id) . '" class="btn btn-primary btn-sm edit">
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


            ->editColumn('image', function ($row) {
                $image = $row->image
                    ? asset('assets/storage/eidOffer/' . $row->image)
                    : '';
                return '<img src="' . $image . '" alt="' . $row->title . '" width="80">';
            })


            ->editColumn('total_products', function ($row) {

                $productIds = json_decode($row->product_ids, true);
                $total = is_array($productIds) ? count($productIds) : 0;

                return '<a href="' . route('admin.discount.eid-offers.product', $row->id) . '" class="btn btn-sm btn-dark">
                Total Products: <span class="badge bg-success">' . $total . '</span>
            </a>';
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
                'created_at',
                'status',
                'total_products',
                'image',
            ])
            ->toJson();
    }
    public function eidOffersCreate()
    {
        $products = Product::where('status', 1)->get();
        return view("admin.discount.eid_offer.create", compact('products'));
    }

    public function eidOffersStore(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'product_ids' => 'required|array',
            'product_ids.*' => 'exists:products,id',
            'image' => 'nullable|mimetypes:image/*|max:5120',

        ]);

        $data = [
            'title' => $request->title,
            'slug' => Str::slug($request->title),
            'discount_amount' => json_encode($request->discount_amounts),
            'product_ids' => json_encode($request->product_ids),
        ];
        // Store the eid offer
        if ($request->hasFile('image')) {

            $data['image'] = FileManager::uploadFile('eidOffer/', 300, $request->file('image'), $request->title);
        }
        EidOffer::create($data);

        return redirect()->route('admin.discount.eid.offers')->with('success', 'Eid Offer created successfully.');
    }
    public function eidOffersStatus(Request $request)
    {
        $eidoffer = EidOffer::findOrFail($request->id);
        $eidoffer->status = !$eidoffer->status;
        $eidoffer->save();

        return response()->json(['success' => true, 'status' => $eidoffer->status]);
    }
    public function eidOffersProduct($id)
    {
        $eidoffer = EidOffer::findOrFail($id);
        return view('admin.discount.eid_offer.see_products', compact('eidoffer'));
    }
    public function eidOffersProductsDatatables($productIds)
    {

        // JSON string → PHP array
        $productIds = json_decode($productIds, true);

        // যদি empty বা invalid হয় → empty datatable return
        if (!is_array($productIds) || empty($productIds)) {
            return DataTables::of(collect())->toJson();
        }

        // শুধু যেসব id আছে সেগুলোই আনবে
        $query = Product::whereIn('id', $productIds)
            ->latest('id');

        return DataTables::of($query)

            ->addIndexColumn()


            ->addColumn('name', fn($row) => $row->name)
            ->addColumn('code', fn($row) => $row->code)
            ->addColumn('price', fn($row) => $row->unit_price)

            ->addColumn('action', function ($row) {
                return '
                <button class="btn btn-danger btn-sm delete"
                        title="Delete"
                        data-id="' . $row->id . '">
                    <i class="la la-trash"></i>
                </button>
            ';
            })

            ->rawColumns(['action', 'name', 'code', 'price'])

            ->toJson();
    }
    public function eidOffersRemoveProduct(Request $request)
    {
        $id = $request->id;
        // Remove product from any batch discount
        $discountOffers = EidOffer::all();

        foreach ($discountOffers as $discountOffer) {
            $productIds = json_decode($discountOffer->product_ids, true) ?? [];
            // If product exists in this batch
            if (in_array($id, $productIds)) {
                // Remove product ID
                $updatedProductIds = array_diff($productIds, [$id]);
                $discountOffer->product_ids = json_encode(array_values($updatedProductIds));
                $discountOffer->save();
            }
        }

        return response()->json(['success' => true], 200);
    }

    public function eidOffersEdit($id)
    {
        $eidoffer = EidOffer::findOrFail($id);
        $products = Product::where('status', 1)->get();
        return view('admin.discount.eid_offer.edit', compact('eidoffer', 'products'));
    }

    public function eidOffersUpdate(Request $request, $id)
    {

        $request->validate([
            'title' => 'required|string|max:255',
            'product_ids' => 'required|array',
            'product_ids.*' => 'exists:products,id',
        ]);

        // Store the batch discount
        $eidoffer = EidOffer::findOrFail($id);

        $data = [
            'title' => $request->title,
            'slug' => Str::slug($request->title),
            'product_ids' => json_encode($request->product_ids),
        ];
        if ($request->hasFile('image')) {
            $data['image'] = FileManager::updateFile('eidOffer/', $eidoffer->image, $request->file('image'), $request->title);
        }

        $eidoffer->update($data);
        return redirect()->route('admin.discount.eid.offers')->with('success', 'Eid Offer updated successfully.');
    }
    public function eidOffersDelete(Request $request)
    {
        $id = $request->id;
        $eidoffer = EidOffer::findOrFail($id);
        if ($eidoffer) {
            FileManager::delete('eidOffer/' . $eidoffer->image);
        }
        $eidoffer->delete();
        return response()->json(['success' => true], 200);
    }
}
