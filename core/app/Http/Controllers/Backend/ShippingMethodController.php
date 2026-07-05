<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\ShippingMethod;
use App\Models\ShippingConfig;
use App\Models\BusinessSetting;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ShippingMethodController extends Controller
{
    public function list()
    {
        $config = ShippingConfig::getConfig();
        return view('admin.shipping_methods.index', compact('config'));
    }

    public function datatables()
    {
        $query = ShippingMethod::query()->latest('id');

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                return '
                <button class="btn btn-primary btn-sm edit"
                    data-id="'          . $row->id            . '"
                    data-title="'       . $row->title         . '"
                    data-cost="'        . $row->cost          . '"
                    data-duration="'    . $row->duration      . '"
                    data-minamount="'   . $row->min_order_amount . '"
                    data-discount="'    . $row->discount_amount  . '"
                    data-discounttype="' . $row->discount_type    . '"
                    data-bs-toggle="modal"
                    data-bs-target="#editModal">
                    <i class="la la-edit"></i>
                </button>
                <button class="btn btn-danger btn-sm delete"
                    data-id="' . $row->id . '">
                    <i class="la la-trash"></i>
                </button>';
            })
            ->editColumn('cost', function ($row) {
                $html = '৳' . number_format($row->cost);
                if ($row->discount_amount > 0) {
                    $discLabel = $row->discount_type === 'percent'
                        ? $row->discount_amount . '%'
                        : '৳' . $row->discount_amount;
                    $html .= ' <span class="badge bg-success ms-1">-' . $discLabel . ' off</span>';
                }
                return $html;
            })
            ->editColumn('status', function ($row) {
                $checked = $row->status == 1 ? 'checked' : '';
                return '
                <div class="form-check form-switch">
                    <input class="form-check-input status" type="checkbox"
                        data-id="' . $row->id . '" value="1" ' . $checked . '
                        id="flexSwitch' . $row->id . '">
                    <label class="form-check-label" for="flexSwitch' . $row->id . '"></label>
                </div>';
            })
            ->rawColumns(['action', 'status', 'cost'])
            ->toJson();
    }

    // ── Shipping Config Save ──────────────────────────────────────
    public function saveConfig(Request $request)
    {
        $config = ShippingConfig::getConfig();
        $config->shipping_type      = $request->shipping_type;
        $config->free_shipping_type = $request->free_shipping_type ?? null;
        $config->free_shipping_min_amount  = $request->free_shipping_min_amount ?? null;
        $config->save();

        // Shipping discount config (web_config / business_settings এ)
        $this->saveWebConfig('free_shipping_min_amount', $request->min_amount_for_discount ?? null);
        $this->saveWebConfig('free_shipping_discount',   $request->shipping_discount_amount ?? null);

        return response()->json(['message' => 'Configuration saved successfully']);
    }

    private function saveWebConfig($name, $value)
    {
        BusinessSetting::updateOrCreate(
            ['type' => $name],
            ['value' => $value]
        );
    }

    // ── CRUD ──────────────────────────────────────────────────────
    public function store(Request $request)
    {
        $shipping = ShippingMethod::create([
            'title'            => $request->title,
            'cost'             => $request->cost,
            'duration'         => $request->duration,
            'min_order_amount' => $request->min_order_amount ?? null,
            'discount_amount'  => $request->discount_amount  ?? 0,
            'discount_type'    => $request->discount_type    ?? 'flat',
            'creator_type'     => auth('admin')->user()->name,
            'creator_id'       => auth('admin')->user()->id,
        ]);

        return response()->json([
            'status'  => true,
            'message' => 'Shipping method created successfully'
        ]);
    }

    public function update(Request $request)
    {
        $shipping = ShippingMethod::findOrFail($request->id);
        $shipping->update([
            'title'            => $request->title,
            'cost'             => $request->cost,
            'duration'         => $request->duration,
            'min_order_amount' => $request->min_order_amount ?? null,
            'discount_amount'  => $request->discount_amount  ?? 0,
            'discount_type'    => $request->discount_type    ?? 'flat',
            'creator_type'     => auth('admin')->user()->name,
            'creator_id'       => auth('admin')->user()->id,
        ]);

        return response()->json(['success' => 1]);
    }

    public function delete(Request $request)
    {
        ShippingMethod::findOrFail($request->id)->delete();
        return response()->json();
    }

    public function status(Request $request)
    {
        ShippingMethod::findOrFail($request->id)->update(['status' => $request->status]);
        return response()->json(['success' => 1]);
    }
}
