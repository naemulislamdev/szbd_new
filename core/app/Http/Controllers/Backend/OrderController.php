<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\OrderHistory;
use App\Models\Product;
use App\Models\ShippingAddress;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Barryvdh\DomPDF\Facade\Pdf;

class OrderController extends Controller
{
    public function list(Request $request)
    {
        $views = [
            'pending'         => 'admin.order.pending',
            'confirmed'       => 'admin.order.confirmed',
            'processing'      => 'admin.order.processing',
            'out_for_delivery' => 'admin.order.out_for_delivery',
            'delivered'       => 'admin.order.delivered',
            'returned'        => 'admin.order.returned',
            'failed'          => 'admin.order.failed',
            'canceled'        => 'admin.order.canceled',
        ];

        // Use request status if exists in map, otherwise default view
        $view = $views[$request->status] ?? 'admin.order.index';

        return view($view);
    }
    public function datatables(Request $request, $status)
    {
        $query = Order::query();

        // 🔹 Filter by date range
        if ($request->filled('from_date') && $request->filled('to_date')) {
            $query->whereDate('created_at', '>=', $request->from_date)
                ->whereDate('created_at', '<=', $request->to_date);
        } elseif ($request->filled('from_date')) {
            $query->whereDate('created_at', '>=', $request->from_date);
        } elseif ($request->filled('to_date')) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }

        // 🔹 Filter by status (optional)
        if ($status != 'all') {
            $query->where('order_status', $status);
        }

        $query->latest('id');

        return DataTables::eloquent($query)

            ->addIndexColumn() // SL

            ->editColumn('order_number', function (Order $order) {
                return '<a href="' . route('admin.order.details', $order->id) . '">'
                    . $order->order_number ?? '' . '</a>';
            })

            ->addColumn('date', function (Order $order) {
                return $order->created_at->format('d M Y');
            })

            ->addColumn('time', function (Order $order) {
                return $order->created_at->format('h:i A');
            })

            ->addColumn('customer_name', function (Order $order) {
                return optional($order->customer)->name ?? 'Guest';
            })

            ->addColumn('phone', function (Order $order) {
                return optional($order->customer)->phone ?? 'N/A';
            })

            ->addColumn('amount', function (Order $order) {
                return number_format($order->order_amount, 2);
            })

            ->addColumn('delivery_charge', function (Order $order) {
                return number_format($order->shipping_cost, 2);
            })

            ->addColumn('total', function (Order $order) {
                return number_format(
                    $order->order_amount + $order->shipping_cost - $order->discount_amount,
                    2
                );
            })

            ->editColumn('order_status', function (Order $order) {
                // dd($order->order_status);

                if (!$order->order_status) {
                    return '<span class="badge badge-dark">N/A</span>';
                }

                $badges = [
                    'pending'           => 'warning',
                    'confirmed'         => 'primary',
                    'processing'        => 'info',
                    'out_for_delivery'  => 'primary',
                    'delivered'         => 'success',
                    'returned'          => 'secondary',
                    'failed'            => 'dark',
                    'canceled'          => 'danger',
                ];

                $color = $badges[$order->order_status] ?? 'secondary';

                return '<span class="badge bg-' . $color . '">'
                    . ucfirst(str_replace('_', ' ', $order->order_status))
                    . '</span>';
            })


            ->editColumn('order_type', function (Order $order) {
                if ($order->order_type == 'default_type') {
                    return 'Web';
                } else {
                    return ucfirst($order->order_type ?? 'Regular');
                }
            })

            ->addColumn('action', function (Order $order) {
                return '
                <a href="' . route('admin.order.details', $order->id) . '" class="btn btn-sm btn-info">
                    <i class="las la-eye"></i>
                </a>
                <a href="' . route('admin.order.generate-invoice', $order->id) . '"
                   class="btn btn-sm btn-secondary">
                    <i class="las la-receipt"></i>
                </a>
                <a href="' . route('admin.order.delete', $order->id) . '"
                   class="btn btn-sm btn-danger">
                    <i class="las la-trash-alt"></i>
                </a>
            ';
            })

            ->rawColumns([
                'order_number',
                'order_status',
                'action'
            ])

            ->toJson();
    }
    public function status(Request $request){
        $order = Order::with('customer')->where(['id' => $request->id])->first();

        $order->order_status = $request->order_status;
        $existingNotes = json_decode($order->multiple_note, true) ?? [];

        $newNotes = [];
        foreach ($request->multiple_note as $note) {
            $newNotes[] = [
                'note' => $note,
                'time' => now()->format('d M Y h:i A'),
                'user' => auth('admin')->user()->name ?? 'System'
            ];
        }

        $order->multiple_note = json_encode(
            array_merge($existingNotes, $newNotes)
        );
        // $order->order_note = json_encode([
        //     'note' => $request->note,
        //     'user' => auth('admin')->user()->name,
        //     'date' => now()->format('d M Y h:i A')
        // ]);

        if ($request->order_status === 'delivered') {
            $order->payment_status = 'paid';
        }

        $order->save();

        return response()->json([
            'status'       => true,
            'order_status' => $order->order_status,
            'note'    => end($newNotes)
        ]);
    }
    public function multipleNote(Request $request)
    {
        $request->validate([
            'id' => 'required',
            'multiple_note' => 'required|array'
        ]);

        $order = Order::findOrFail($request->id);

        $existingNotes = json_decode($order->multiple_note, true) ?? [];

        $newNotes = [];

        foreach ($request->multiple_note as $note) {
            $newNotes[] = [
                'note' => $note,
                'time' => now()->format('d M Y h:i A'),
                'user' => auth('admin')->user()->name
            ];
        }

        $mergedNotes = array_merge($existingNotes, $newNotes);

        $order->multiple_note = json_encode($mergedNotes);
        $order->save();

        return response()->json([
            'status' => true,
            'note' => end($newNotes)
        ]);
    }
    public function detailsProduct($id)
    {
        $detailsProduct = OrderDetail::findOrFail($id);
        dd($detailsProduct);
        return view('admin.order.order_details', compact('detailsProduct'));
    }
    public function details($id)
    {
        $order = Order::with('details', 'shipping')->where(['id' => $id])->first();

        $shipping_address = ShippingAddress::find($order->shipping_address);
        $orderHistories = OrderHistory::where('order_id', $id)->get();

        return view('admin.order.order_details', compact('order', 'orderHistories', 'shipping_address'));
    }

    // Add Multiple Product In order Details page Start
    public function productSearch(Request $request)
    {
        $products = Product::where('name', 'LIKE', "%{$request->keyword}%")
            ->orWhere('code', 'LIKE', "%{$request->keyword}%")
            ->limit(10)
            ->get();

        return response()->json($products);
    }
    public function productVariation($id)
    {
        $product = Product::findOrFail($id);

        $colors = $product->color_variant
            ? json_decode($product->color_variant)
            : [];

        $sizes = [];
        if ($product->choice_options) {
            foreach (json_decode($product->choice_options) as $choice) {
                $sizes = array_merge($sizes, $choice->options);
            }
        }

        return response()->json([
            'has_variation' => count($colors) > 0 || count($sizes) > 0,
            'colors' => collect($colors)->map(fn($c) => [
                'color' => $c->color,
                'image' => asset($c->image),
            ]),
            'sizes' => $sizes,
        ]);
    }

    public function addProduct(Request $request)
    {
        $order = Order::findOrFail($request->order_id);
        $product = Product::findOrFail($request->product_id);
        $discount = 0;
        $price = $product->unit_price;
        // product discount calculation
        if ($product->discount > 0) {
            if ($product->discount_type === 'percent') {
                $discount = ($price * $product->discount) / 100;
            } elseif ($product->discount_type === 'flat') {
                $discount = $product->discount;
            }
        }

        // safety check (discount can't exceed price)
        $discount = min($discount, $price);

        $subtotal = ($price * 1) - $discount;

        $detail = OrderDetail::create([
            'order_id'       => $request->order_id,
            'product_id'     => $product->id,
            'product_details' => json_encode($product),
            'color_image'    => $request->color_image,
            'qty'            => 1,
            'price'          => $product->unit_price,
            'tax'            => 0,
            'discount'       => $discount,
            'discount_type'  => 'discount_on_product',
            'created_by'  => auth('admin')->user()->name,
            'variant'        => 'color-size',
            'variation'      => json_encode([
                'color' => $request->color,
                'size'  => $request->size,
            ]),
            'delivery_status' => 'pending',
            'payment_status' => 'unpaid',
        ]);


        return response()->json([
            'success' => true,
            'detail'  => $detail->load('product')
        ]);
    }
    public function removeProduct(Request $request)
    {
        OrderDetail::where('id', $request->detail_id)->delete();

        return response()->json(['success' => true]);
    }
    public function recalculate(Order $order)
    {
        $subtotal = $order->details->sum('subtotal');

        return response()->json([
            'subtotal' => $subtotal,
            'shipping' => $order->shipping_cost,
            'coupon'   => $order->discount_amount,
            'advance'  => $order->advance_amount,
            'total'    => $subtotal + $order->shipping_cost - $order->discount_amount - $order->advance_amount,
        ]);
    }
    // Add multiple product in order details page End
    public function generate_invoice($id)
    {
        $order = Order::with('seller')->with('shipping')->with('details')->where('id', $id)->first();
        $data["email"] = $order->customer != null ? $order->customer["email"] : 'Email not found';
        $data["client_name"] = $order->customer != null ? $order->customer["f_name"] . ' ' . $order->customer["l_name"] : 'Customer not found';
        $data["order"] = $order;
        return view('admin.order.invoice', compact('order'));
        $pdf = PDF::loadView('admin.order.invoice', $data);
        return $pdf->download($order->id . '.pdf');
    }
}
