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
                    return '
            <span class="badge bg-success status_' . $row->id . '">Seen</span>
            <div><small>Seen by: <br/>' . $row->seen_by . '</small></div>
        ';
                } else {
                    return '<span class="badge bg-primary status_' . $row->id . '">Unseen</span>';
                }
            })
            ->editColumn('product_details', function ($row) {

                $productDetails = json_decode($row->product_details, true);

                if (empty($productDetails)) {
                    return 'N/A';
                }

                $html = '';

                // 🔹 SINGLE PRODUCT
                if (isset($productDetails['product_id'])) {

                    $product = Product::find($productDetails['product_id']);

                    $html .= '<strong>Product Code:</strong> ' . ($product->code ?? 'N/A') . '<br>';

                    if (!empty($productDetails['color'])) {
                        $colorName = Color::where('code', $productDetails['color'])->value('name');
                        $html .= '<strong>Color:</strong> ' . ($colorName ?? 'N/A') . '<br>';
                    }
                }

                // 🔹 MULTIPLE PRODUCTS
                elseif (isset($productDetails[0]) && is_array($productDetails[0])) {

                    foreach ($productDetails as $item) {

                        $product = Product::find($item['id'] ?? null);

                        $html .= '<div class="mb-1">';
                        $html .= '<strong>Product Code:</strong> ' . ($product->code ?? 'N/A') . '<br>';

                        if (!empty($item['color'])) {
                            $colorName = Color::where('code', $item['color'])->value('name');
                            $html .= '<strong>Color:</strong> ' . ($colorName ?? 'N/A') . '<br>';
                        }

                        $html .= '</div>';
                    }
                }

                return $html;
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

        $order = UserInfo::findOrFail($request->id);

        $order->order_status = $request->order_status;
        $order->order_note   = $request->note;
        $order->save();

        return response()->json([
            'message' => 'Order status updated successfully',
            'note' => $request->note
        ]);
    }

    public function show(Request $request)
    {
        $item = UserInfo::findOrFail($request->id);

        // status update
        if ($item->status === 0) {
            $item->update([
                'status'  => 1,
                'seen_by' => auth('admin')->user()->name,
            ]);
        }

        // return view content for modal
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
        foreach ($userinfos as $item) {
            // $customer = User::find($item->customer_id);
            $data[] = [
                $item->created_at->format('d M Y'),
                $item->name ?? 'Guest',
                $item->phone ?? 'N/A',
                $item->address ?? 'N/A',
                $item->order_status,
            ];
        }

        $headings = ['Date', 'Customer Name', 'Customer Phone', 'Customer Address', 'Status'];

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
