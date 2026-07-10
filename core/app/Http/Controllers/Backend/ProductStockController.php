<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class ProductStockController extends Controller
{
    public function index()
    {
        return view('admin.reports.product_stock');
    }

    public function datatables(Request $request)
    {
        // Shob product niye asho (variation thakuk ba na thakuk)
        $products = Product::select('id', 'name', 'code', 'status', 'unit_price', 'thumbnail', 'variation', 'current_stock', 'updated_at')
            ->get();

        $rows = collect();

        foreach ($products as $product) {

            $variations = is_string($product->variation)
                ? json_decode($product->variation, true)
                : $product->variation;

            if (is_array($variations) && count($variations) > 0) {
                // ---- Variation product ----
                foreach ($variations as $v) {
                    $qty = (int)($v['qty'] ?? 0);

                    $rows->push([
                        'product_id'   => $product->id,
                        'name'         => $product->name,
                        'photo'        => $product->thumbnail,
                        'code'         => $product->code,

                        'type'         => $v['type'] ?? '',
                        'sku'          => $v['sku'] ?? $product->code,
                        'unit_price'   => $product->unit_price ?? 0,   // <-- fix: variation theke na, product theke
                        'status'   => $product->status,   // <-- fix: variation theke na, product theke
                        'qty'          => $qty,
                        'status_label' => $this->stockStatus($qty),
                        'updated_at'   => $product->updated_at,
                    ]);
                }
            } else {
                // ---- Non-variation product (e.g. Cream) ----
                $qty = (int) ($product->current_stock ?? 0);

                $rows->push([
                    'product_id'   => $product->id,
                    'name'         => $product->name,
                    'photo'        => $product->thumbnail,
                    'code'         => $product->code,
                    'type'         => '',
                    'sku'          => $product->code,
                    'unit_price'   => $product->unit_price ?? 0,
                    'qty'          => $qty,
                    'status'          => $product->status,
                    'status_label' => $this->stockStatus($qty),
                    'updated_at'   => $product->updated_at,
                ]);
            }
        }

        // ---------- Filter ----------
        // ---------- Filter ----------
        $filter = $request->get('filter'); // out_of_stock | low_stock | available
        if ($filter === 'out_of_stock') {
            $rows = $rows->where('qty', '<=', 0);   // <-- fix: 0 na, <=0
        } elseif ($filter === 'low_stock') {
            $rows = $rows->whereBetween('qty', [1, 10]);
        } elseif ($filter === 'available') {
            $rows = $rows->where('qty', '>', 10);
        }

        // ---------- Filter: Product Status ----------
        $statusFilter = $request->get('status_filter'); // '' = All, '1' = Active, '0' = Inactive
        if ($statusFilter !== null && $statusFilter !== '') {
            $rows = $rows->where('status', (int) $statusFilter);
        }

        // ---------- Search ----------
        $search = $request->input('search.value');
        if (!empty($search)) {
            $rows = $rows->filter(function ($row) use ($search) {
                $search = strtolower($search);
                return str_contains(strtolower($row['name']), $search)
                    || str_contains(strtolower($row['sku']), $search)
                    || str_contains(strtolower($row['type']), $search)
                    || str_contains(strtolower($row['code']), $search)
                    || str_contains(strtolower($row['status']), $search);
            });
        }

        $recordsTotal = $rows->count();

        // ---------- Sort: qty 0 -> high ----------
        $rows = $rows->sortBy('qty')->values();

        $recordsFiltered = $rows->count();

        // ---------- Paginate ----------
        $start  = (int) $request->input('start', 0);
        $length = (int) $request->input('length', 10);

        $paged = $length == -1
            ? $rows->slice($start)
            : $rows->slice($start, $length);

        $data = $paged->values()->map(function ($row, $i) use ($start) {
            $photo = $row['photo']
                ? asset('assets/storage/product/thumbnail/' . $row['photo'])
                : asset('assets/noimage.png');

            $statusBadge = $row['qty'] <= 0
                ? '<span class="badge bg-danger-subtle text-danger">Out of Stock</span>'
                : ($row['qty'] <= 10
                    ? '<span class="badge bg-warning-subtle text-warning">Low Stock</span>'
                    : '<span class="badge bg-success-subtle text-success">Available</span>');

            return [
                'DT_RowIndex' => $start + $i + 1,
                'photo'       => '<img src="' . $photo . '" width="50">',
                'name'        => $row['name'] . ($row['type'] ? ' <br><span class="h5"> <small>Size: </small>' . $row['type'] . '</span>' : ''),
                'sku'         => $row['sku'],
                'code'        => $row['code'],
                'unit_price'        => $row['unit_price'],
                'qty'         => $row['qty'],
                'stock_status'      => $statusBadge,
                'status'      => $row['status'] == 1 ?  '<span class="badge bg-success-subtle text-success">Active</span>' :  '<span class="badge bg-danger-subtle text-danger">Inactive</span>',
                'updated_at'  => $row['updated_at'] ? Carbon::parse($row['updated_at'])->format('d M Y, h:i A') : '-',
            ];
        });

        return response()->json([
            'draw'            => intval($request->input('draw')),
            'recordsTotal'    => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data'            => $data,
        ]);
    }

    private function stockStatus(int $qty): string
    {
        if ($qty <= 0) return 'out_of_stock';
        if ($qty <= 10) return 'low_stock';
        return 'available';
    }
}
