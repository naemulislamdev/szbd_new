<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ContactController extends Controller
{
    public function list()
    {
        return view('admin.contact.list');
    }
    public function datatables()
    {
        $query = Contact::query();
        $query->latest('id');

        return DataTables::of($query)

            ->addIndexColumn()

            ->addColumn('action', function ($row) {

                $viewClass = $row->seen == 0 ? 'viewMessage' : '';

                return '
        <button class="btn btn-primary btn-sm ' . $viewClass . ' edit"
            data-id="' . $row->id . '"
            data-seen="' . $row->seen . '"
            data-name="' . $row->name . '"
            data-email="' . $row->email . '"
            data-mobile="' . $row->mobile_number . '"
            data-subject="' . $row->subject . '"
            data-message="' . $row->message . '"
            data-bs-toggle="modal"
            data-bs-target="#editModal">
            <i class="la la-eye"></i>
        </button>

        <button class="btn btn-danger btn-sm delete"
            style="cursor: pointer;"
            title="Delete"
            data-id="' . $row->id . '">
            <i class="la la-trash"></i>
        </button>
    ';
            })


            ->rawColumns([
                'action',
            ])
            ->toJson();
    }

    public function destroy(Request $request)
    {
        $contact = Contact::find($request->id);
        $contact->delete();

        return response()->json();
    }
    public function view(Request $request)
    {
        Contact::where('id', $request->id)->update([
            'seen' => 1
        ]);

        return response()->json([
            'status' => true
        ]);
    }
}
