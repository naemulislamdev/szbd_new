<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

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
                return '<a href="' . route('admin.order.show', $order->id) . '">'
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
                <a href="' . route('admin.order.show', $order->id) . '" class="btn btn-sm btn-info">
                    <i class="las la-eye"></i>
                </a>
                <a href="' . route('admin.order.edit', $order->id) . '"
                   class="btn btn-sm btn-primary">
                    <i class="las la-pen"></i>
                </a>
                <a href="' . route('admin.order.edit', $order->id) . '"
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
}
