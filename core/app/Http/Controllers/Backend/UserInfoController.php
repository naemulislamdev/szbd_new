<?php

namespace App\Http\Controllers\Backend;

use App\Exports\DataExport;
use App\Http\Controllers\Controller;
use App\Models\Color;
use App\Models\Product;
use App\Models\UserInfo;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class UserInfoController extends Controller
{
    public function all(Request $request)
    {

        $views = [
            'pending'         => 'admin.user_infos.pending',
            'confirmed'       => 'admin.user_infos.confirmed',
            'canceled'        => 'admin.user_infos.canceled',

        ];
        $view = $views[$request->status] ?? 'admin.user_infos.index';
        return view($view);
    }
    public function datatables(Request $request, $status)
    {
        $query = UserInfo::query();

        // 🔹 Date filter
        if ($request->filled('from_date')) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }

        // 🔹 Status filter
        if ($status !== 'all') {
            $query->where('order_status', $status);
        }

        $query->latest('id');

        return DataTables::of($query)

            ->addIndexColumn()

            ->addColumn('action', fn($row) => '
            <button class="btn btn-sm btn-info viewBtn" data-id="' . $row->id . '">
                <i class="las la-eye"></i>
            </button>
            <a id="delete" href="' . route('admin.userinfo.delete', $row->id) . '"
                   class="btn btn-sm btn-danger">
                    <i class="las la-trash-alt"></i>
                </a>
        ')

            ->editColumn(
                'created_at',
                fn($row) =>
                Carbon::parse($row->created_at)->format('d F Y g:i A')
            )

            // Edit Column
            ->editColumn('status', function ($row) {
                if ($row->status == 1) {

                    $seenBy = json_decode($row->seen_by, true);

                    if (is_array($seenBy)) {
                        $seenText = ($seenBy['name'] ?? '') . '<br><small>' . ($seenBy['seen_at'] ?? '') . '</small>';
                    } else {
                        $seenText = $row->seen_by;
                    }
                    return '
                        <div class="status_wrapper_' . $row->id . '">
                            <span class="badge bg-success status_' . $row->id . '">Seen</span>
                            <div class="seen_by_' . $row->id . '">
                                <small>Seen by: <br/>' . $seenText . '</small>
                            </div>
                        </div>
                    ';
                }
                return '<span class="badge bg-primary status_' . $row->id . '">Unseen</span>';
            })
            ->editColumn('product_details', function ($row) {

                $productDetails = json_decode($row->product_details, true);

                if (empty($productDetails)) {
                    return 'N/A';
                }

                $items = []; // 🔥 এখানে সব collect করবো

                // 🔹 SINGLE PRODUCT
                if (isset($productDetails['product_id'])) {

                    $product = Product::find($productDetails['product_id']);
                    $productPrice = $product->unit_price ?? 'N/A';

                    $text = ($product->code ?? 'N/A') . ' - ' . $productPrice;

                    if (!empty($productDetails['color'])) {
                        $colorName = Color::where('code', $productDetails['color'])->value('name');
                        $text .= ' (' . ($colorName ?? 'N/A') . ')';
                    }

                    $items[] = $text;
                }

                // 🔹 MULTIPLE PRODUCTS
                elseif (isset($productDetails[0]) && is_array($productDetails[0])) {

                    foreach ($productDetails as $item) {

                        $product = Product::find($item['id'] ?? null);
                        $productPrice = $product->unit_price ?? 'N/A';

                        $text = ($product->code ?? 'N/A') . ' - ' . $productPrice;

                        if (!empty($item['color'])) {
                            $colorName = Color::where('code', $item['color'])->value('name');
                            $text .= ' (' . ($colorName ?? 'N/A') . ')';
                        }

                        $items[] = $text; // 🔥 push
                    }
                }

                // 🔥 final output (comma separated)
                return implode(', ', $items);
            })

            ->editColumn('order_process', function ($row) {
                return match ($row->order_process) {
                    'pending'   => '<span class="badge bg-danger">Pending</span>',
                    'completed' => '<span class="badge bg-success">Confirmed</span>',
                    default     => '<span class="badge bg-secondary">' . ucfirst($row->order_process) . '</span>',
                };
            })

            ->editColumn('order_status', function ($row) {

                $statuses = [
                    'pending'   => 'Pending',
                    'confirmed' => 'Confirmed',
                    'canceled'  => 'Canceled',
                ];

                $html = '<select
        class="form-select form-select-sm "  onchange="order_status(this.value, ' . $row->id . ')"
        data-id="' . $row->id . '"
        data-current="' . $row->order_status . '">';

                foreach ($statuses as $key => $label) {
                    $selected = $row->order_status === $key ? 'selected' : '';
                    $html .= "<option value='{$key}' {$selected}>{$label}</option>";
                }

                return $html . '</select>';
            })


            ->editColumn(
                'order_note',
                fn($row) =>
                "<span class=note_" . $row->id . ">" . $row->order_note . "</span>" ?? 'N/A'
            )

            ->rawColumns([
                'product_details',
                'status',
                'order_process',
                'order_status',
                'action',
                'order_note'
            ])
            ->toJson();
    }
    public function updateStatus(Request $request)
    {

        $userinfo = UserInfo::findOrFail($request->id);

        $userinfo->order_status = $request->order_status;
        $userinfo->order_note   = $request->note;
        if ($request->order_status === 'confirmed') {

            $userinfo->confirmed_by = json_encode([
                'user' => auth('admin')->user()->name,
                'time' => now()->format('d M Y h:i A')
            ]);
        } elseif ($request->order_status === 'canceled') {

            $userinfo->canceled_by = json_encode([
                'user' => auth('admin')->user()->name,
                'time' => now()->format('d M Y h:i A')
            ]);
        }

        $userinfo->save();

        return response()->json([
            'message' => 'Order status updated successfully',
            'note' => $request->note
        ]);
    }
    public function show(Request $request)
    {
        $item = UserInfo::findOrFail($request->id);

        $item->update([
            'status'  => 1,
            'seen_by' => json_encode([
                'name' => auth('admin')->user()->name,
                'seen_at' => now()->format('d-M-Y h:i:s A'),
            ]),
        ]);

        $html = view('admin.user_infos.show', compact('item'))->render();

        return response()->json([
            'status' => $item->status,
            'html' => $html,
            'seen_by' => $item->seen_by,
        ]);
    }

    public function dateWiseExport(Request $request)
    {
        $request->validate([
            'from_date' => 'required|date',
            'to_date'   => 'required|date|after_or_equal:from_date',
        ]);

        $query = UserInfo::with('customer')
            ->whereBetween('created_at', [
                Carbon::parse($request->from_date)->startOfDay(),
                Carbon::parse($request->to_date)->endOfDay(),
            ]);

        if ($request->status && $request->status != 'all') {
            $query->where('order_status', $request->status);
        }

        $userinfos = $query->latest()->cursor();

        $data = [];
        $totalPrice = 0;

        foreach ($userinfos as $item) {
            $productCode = '';
            $productPrice = 0;
            $product_details = json_decode($item->product_details, true);

            if (is_array($product_details)) {

                if (isset($product_details[0]) && is_array($product_details[0])) {

                    $codes = [];

                    foreach ($product_details as $pd) {
                        $product = Product::find($pd['id'] ?? null);

                        if ($product) {
                            $codes[] = $product->code ?? '';
                            $productPrice += $product->unit_price ?? 0; // ⭐ sum per row
                        }
                    }

                    $productCode = implode(', ', $codes);
                } else {

                    $product = Product::find($product_details['product_id'] ?? null);

                    if ($product) {
                        $productCode = $product->code ?? '';
                        $productPrice = $product->unit_price ?? 0;
                    }
                }
            }

            $totalPrice += $productPrice;

            $data[] = [
                'Date'          => Carbon::parse($item->created_at)->format('d F Y'),
                'Time'          => Carbon::parse($item->created_at)->format('h:i A'),
                'Name'          => $item->name ?? '',
                'Email'         => $item->email ?? '',
                'Phone'         => $item->phone ?? '',
                'Address'       => $item->address ?? '',
                'Product Code'  => $productCode,
                'Product Price' => $productPrice,
                'Type'          => ucfirst($item->type ?? ''),
                'Status'        => $item->status == 0 ? 'Unseen' : 'Seen',
                'Order Process' => $item->order_process == 'pending'
                    ? 'Pending'
                    : ($item->order_process == 'completed' ? 'Confirmed' : ucfirst($item->order_process)),
                'Order Status'  => ucfirst($item->order_status ?? ''),
                'Order Note'    => $item->order_note ?? 'N/A',
            ];
        }

        // ⭐ Add Total Row
        $data[] = [
            'Date'          => '',
            'Time'          => '',
            'Name'          => '',
            'Email'         => '',
            'Phone'         => '',
            'Address'       => '',
            'Product Code'  => 'Total',
            'Product Price' => $totalPrice,
            'Type'          => '',
            'Status'        => '',
            'Order Process' => '',
            'Order Status'  => '',
            'Order Note'    => '',
        ];

        $headings = [
            'Date',
            'Time',
            'Name',
            'Email',
            'Phone',
            'Address',
            'Product Code',
            'Product Price',
            'Type',
            'Status',
            'Order Process',
            'Order Status',
            'Order Note'
        ];

        return Excel::download(new DataExport($headings, $data), 'userinfo' . $request->from_date . '_to_' . $request->to_date . '.xlsx');
    }
    public function multipleNote(Request $request)
    {
        $request->validate([
            'id' => 'required',
            'multiple_note' => 'required|array'
        ]);

        $userinfo = UserInfo::findOrFail($request->id);

        // existing notes
        $existingNotes = json_decode($userinfo->multiple_note, true) ?? [];

        // new notes with date & time
        $newNotes = [];

        foreach ($request->multiple_note as $note) {
            $newNotes[] = [
                'note' => $note,
                'time' => now()->format('d M Y h:i A'),
                'user' => auth('admin')->user()->name
            ];
        }

        // merge old + new
        $mergedNotes = array_merge($existingNotes, $newNotes);

        // save as json
        $userinfo->multiple_note = json_encode($mergedNotes);
        $userinfo->save();

        return response()->json([
            'status' => true,
            'note' => end($newNotes) // last added note frontend এ পাঠানো
        ]);
    }
}
