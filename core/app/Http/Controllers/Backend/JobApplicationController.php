<?php

namespace App\Http\Controllers\Backend;

use App\CPU\FileManager;
use App\Http\Controllers\Controller;
use App\Models\JobApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class JobApplicationController extends Controller
{
    public function index()
    {
        return view('admin.Job_application.list');
    }
    public function datatables()
    {
        $query = JobApplication::query();
        $query->latest('id');
        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {

                return '
                <button class="btn btn-primary btn-sm edit"
                    data-id="' . $row->id . '"
                    data-name="' . $row->name . '"
                    data-email="' . $row->email . '"
                    data-phone="' . $row->phone . '"
                    data-expected_salary="' . $row->expected_salary . '"
                    data-current_position="' . $row->current_position . '"
                    data-experience_level="' . $row->experience_level . '"
                    data-portfolio_link="' . $row->portfolio_link . '"
                    data-status="' . $row->status . '"
                    data-created_at="' . $row->created_at . '"
                    data-resume="' . $row->resume . '"
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
            ->addColumn('change_status', function ($row) {

                $selectedPending = $row->status == 'pending' ? 'selected' : '';
                $selectedShortlisted = $row->status == 'shortlisted' ? 'selected' : '';
                $selectedRejected = $row->status == 'rejected' ? 'selected' : '';

                return '
                        <div>
                            <select class="form-select form-select-sm status"
                                name="status" data-id="' . $row->id . '">

                                <option value="pending" ' . $selectedPending . '>
                                    Pending
                                </option>

                                <option value="shortlisted" ' . $selectedShortlisted . '>
                                    Shortlisted
                                </option>

                                <option value="rejected" ' . $selectedRejected . '>
                                    Rejected
                                </option>

                            </select>
                        </div>
                    ';
            })

            // Edit Column

            ->editColumn('career_id', function ($row) {
                return $row->career->position ? $row->career->position : 'N/A';
            })
            ->editColumn('status', function ($row) {

                if (!$row->status) {
                    return '';
                }

                $class = '';

                if ($row->status == 'shortlisted') {
                    $class = 'bg-success';
                } elseif ($row->status == 'pending') {
                    $class = 'bg-warning text-dark';
                } elseif ($row->status == 'rejected') {
                    $class = 'bg-danger';
                }

                return '<span class="badge ' . $class . '">'
                    . ucfirst($row->status) .
                    '</span>';
            })
            ->editColumn('created_at', function ($row) {
                return $row->created_at->format('d M Y h:i A');
            })
            ->editColumn('resume', function ($row) {
                $url = asset('assets/storage/files/job_resume/' . $row->resume);
                return $row->resume
                    ? '<a target="_blank" href="' . $url . '"><i style="font-size: 30px" class="las la-file-pdf"></i></a>'
                    : '';
            })


            ->rawColumns([
                'action',
                'status',
                'career_id',
                'resume',
                'created_at',
                'change_status',

            ])
            ->toJson();
    }
    public function status(Request $request)
    {

        if ($request->ajax()) {
            $app = JobApplication::find($request->id);
            $app->status = $request->status;
            $app->save();
            $data = $request->all();
            return response()->json($data);
        }
    }
    public function delete(Request $request)
    {
        $cr = JobApplication::find($request->id);
        if ($cr->resume) {
            FileManager::delete('files/job_resume/' . $cr->resume);
        }
        $cr->delete();
        return response()->json();
    }
}
