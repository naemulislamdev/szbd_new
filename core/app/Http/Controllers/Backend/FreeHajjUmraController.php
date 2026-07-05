<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUmrahHajApplicationRequest;
use App\Models\FreeHajjUmra;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;

class FreeHajjUmraController extends Controller
{
     // -------------------------------------------------------
    // Store — আবেদন জমা দিন
    // -------------------------------------------------------

    /**
     * POST /umrah-haj/apply
     * ফর্ম সাবমিশন হ্যান্ডেল করে এবং ডেটা সংরক্ষণ করে।
     */
    public function store(StoreUmrahHajApplicationRequest $request): JsonResponse|RedirectResponse
    {
        try {
            $application = DB::transaction(function () use ($request) {
                return FreeHajjUmra::create([
                    // ১. ব্যক্তিগত তথ্য
                    'full_name'                     => $request->full_name,
                    'occupation'                    => $request->occupation,
                    'mobile_number'                 => $request->mobile_number,
                    'whatsapp_number'               => $request->whatsapp_number,
                    'email'                         => $request->email,
                    'address'                       => $request->address,

                    // ২. আবেদনকারীর তথ্য
                    'age'                           => $request->age,
                    'mahram'                           => $request->mahram,
                    'gender'                        => $request->gender,
                    'marital_status'                => $request->marital_status,
                    'has_done_umrah_or_haj_before'  => $request->boolean('has_done_umrah_or_haj_before'),

                    // ৩. পাসপোর্ট তথ্য
                    'has_valid_passport'            => $request->boolean('has_valid_passport'),
                    'passport_validity_6_months'    => $request->boolean('has_valid_passport')
                        ? $request->boolean('passport_validity_6_months')
                        : null,
                    'passport_number'               => $request->passport_number,
                    'passport_expiry_date'          => $request->passport_expiry_date,

                    // ৪. নির্বাচন সংক্রান্ত তথ্য

                    'preferred_package'             => $request->preferred_package,
                    'can_self_finance'              => $request->boolean('can_self_finance'),
                    'application_source'              => $request->application_source,
                    'selected_reason'              => $request->selected_reason,

                    // ৫. সম্মতি
                    'terms_accepted'                => true,
                    'selection_decision_accepted'   => true,

                    // status default: 'pending' (migration-এ সেট করা)
                ]);
            });

            Log::info('New Umrah/Haj application submitted.', [
                'reference' => $application->application_reference,
                'mobile'    => $application->mobile_number,

            ]);

            // API request হলে JSON রিটার্ন করো
            if ($request->expectsJson()) {
                return response()->json([
                    'success'   => true,
                    'message'   => 'আপনার আবেদন সফলভাবে জমা হয়েছে।',
                    'reference' => $application->application_reference,
                    'data'      => $application,
                ], 201);
            }

            // Web request হলে redirect করো
            return redirect()
                ->route('umrah-haj.success',)
                ->with('reference', $application->application_reference)
                ->with('success', 'আপনার আবেদন সফলভাবে জমা হয়েছে। রেফারেন্স: ' . $application->application_reference);
        } catch (\Throwable $e) {
            dd($e);
            Log::error('Umrah/Haj application store failed.', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'আবেদন জমা দিতে সমস্যা হয়েছে। অনুগ্রহ করে আবার চেষ্টা করুন।',
                ], 500);
            }

            return back()
                ->withInput()
                ->withErrors(['error' => 'আবেদন জমা দিতে সমস্যা হয়েছে। অনুগ্রহ করে আবার চেষ্টা করুন।']);
        }
    }

    // -------------------------------------------------------
    // Show — রেফারেন্স দিয়ে আবেদন দেখুন
    // -------------------------------------------------------

    /**
     * GET /umrah-haj/check/{reference}
     */
    public function show(string $reference): JsonResponse
    {
        $application = FreeHajjUmra::where('application_reference', strtoupper($reference))
            ->firstOrFail();

        return response()->json([
            'success' => true,
            'data'    => [
                'reference'      => $application->application_reference,
                'full_name'      => $application->full_name,
                'status'         => $application->status,
                'submitted_at'   => $application->created_at->format('d M Y, h:i A'),
            ],
        ]);
    }

    // -------------------------------------------------------
    // Index — সব আবেদন লিস্ট (Admin)
    // -------------------------------------------------------



    // -------------------------------------------------------
    // Update Status — অ্যাডমিন স্ট্যাটাস পরিবর্তন
    // -------------------------------------------------------

    /**
     *
     */
    public function status(Request $request): JsonResponse
    {
        $request->validate([
            'status' => ['required', 'in:pending,reviewing,approved,rejected,completed'],
            'notes'  => ['nullable', 'string', 'max:1000'],
        ]);

        $application = FreeHajjUmra::findOrFail($request->id);

        $application->update([
            'status'      => $request->status,
            'notes'       => $request->note ?? $application->notes,
            'reviewed_at' => now(),
            'reviewed_by' => auth('admin')->user()->name,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'আবেদনের অবস্থা আপডেট হয়েছে।',
            'data'    => $application->refresh(),
        ]);
    }

    // -------------------------------------------------------
    // Destroy — আবেদন মুছুন (Soft Delete)
    // -------------------------------------------------------

    /**
     * DELETE /admin/umrah-haj/applications/{id}
     */
    public function destroy(Request $request)
    {
        $application = FreeHajjUmra::findOrFail($request->id);
        $application->delete();

        return response()->json([
            'success' => true,
            'message' => 'আবেদনটি মুছে ফেলা হয়েছে।',
        ]);
    }
    public function list()
    {
        return view('admin.hajj_umra.list');
    }
    public function datatables()
    {
        $query = FreeHajjUmra::query();
        $query->latest('id');

        return DataTables::of($query)

            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                $yesNo = fn($val) => $val ? 'হ্যাঁ' : 'না';
                $nullableYesNo = fn($val) => is_null($val) ? 'প্রযোজ্য নয়' : ($val ? 'হ্যাঁ' : 'না');

                return '
        <button class="btn btn-primary btn-sm edit"
            data-id="' . $row->id . '"
            data-full_name="' . e($row->full_name) . '"
            data-occupation="' . e($row->occupation) . '"
            data-mobile_number="' . e($row->mobile_number) . '"
            data-whatsapp_number="' . e($row->whatsapp_number) . '"
            data-email="' . e($row->email) . '"
            data-address="' . e($row->address) . '"
            data-age="' . e($row->age) . '"
            data-gender="' . e($row->gender) . '"
            data-marital_status="' . e($row->marital_status) . '"
            data-has_done_umrah_or_haj_before="' . $yesNo($row->has_done_umrah_or_haj_before) . '"
            data-has_valid_passport="' . $yesNo($row->has_valid_passport) . '"
            data-passport_validity_6_months="' . $nullableYesNo($row->passport_validity_6_months) . '"
            data-passport_number="' . e($row->passport_number) . '"
            data-passport_expiry_date="' . e($row->passport_expiry_date) . '"
            data-preferred_package="' . e($row->preferred_package) . '"
            data-can_self_finance="' . $yesNo($row->can_self_finance) . '"
            data-status="' . e($row->status) . '"
            data-notes="' . e($row->notes) . '"
            data-selected_reason="' . e($row->selected_reason) . '"
            data-mahram="' . e($row->mahram) . '"
            data-selected_reason="' . e($row->selected_reason) . '"
            data-application_source="' . e($row->application_source) . '"
            data-bs-toggle="modal"
            data-bs-target="#editModal">
            <i class="la la-eye"></i>
        </button>
        <button class="btn btn-sm btn-danger delete" data-id="' . $row->id . '">
            <i class="la la-trash"></i>
        </button>
    ';
            })
            // Edit Column
            ->editColumn('status', function ($row) {

                $statuses = [
                    'pending'   => 'Pending',
                    'reviewing' => 'Reviewing',
                    'approved'  => 'Approved',
                    'rejected'  => 'Rejected',
                ];

                $html = '<select
        class="form-select form-select-sm "  onchange="order_status(this.value, ' . $row->id . ')"
        data-id="' . $row->id . '"
        data-current="' . $row->status . '">';

                foreach ($statuses as $key => $label) {
                    $selected = $row->status === $key ? 'selected' : '';
                    $html .= "<option value='{$key}' {$selected}>{$label}</option>";
                }

                return $html . '</select>';
            })

            ->editColumn("has_done_umrah_or_haj_before", function ($row) {
                return $row->has_done_umrah_or_haj_before == 1 ? 'Yes' : 'No';
            })
            ->rawColumns([
                'action',
                'status',
                'has_done_umrah_or_haj_before',
            ])
            ->toJson();
    }
}
