<?php

namespace App\Http\Controllers\Backend;

use App\CPU\FileManager;
use App\Http\Controllers\Controller;
use App\Models\Career;
use App\Models\JobDepartment;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class CareerController extends Controller
{

    public function index()
    {
        return view('admin.career.index');
    }

    public function datatables()
    {
        $query = Career::query();
        $query->latest('id');
        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                $editUrl = route('admin.career.edit', $row->id);
                return '
                <a href="' . $editUrl . '" class="btn btn-primary btn-sm ">
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

            // Edit Column
            ->editColumn('status', function ($row) {
                $checked = $row->status == 1 ? 'checked' : '';
                return '
                        <div class="form-check form-switch">
                            <input class="form-check-input status"
                                type="checkbox"
                                name="status"
                                data-id="' . $row->id . '"
                                value="1"
                                ' . $checked . '
                                id="flexSwitch' . $row->id . '">
                            <label class="form-check-label" for="flexSwitch' . $row->id . '"></label>
                        </div>
    ';
            })
            ->editColumn('opening_date', function ($row) {
                return $row->created_at->format('d F Y');
            })
            ->editColumn('deadline', function ($row) {
                return $row->created_at->format('d F Y');
            })
            ->editColumn('image', function ($row) {
                $url = asset('assets/storage/career/' . $row->image);
                return '<img src="' . $url . '" width="60" alt="job post image">';
            })
            ->rawColumns([
                'action',
                'status',
                'opening_date',
                'deadline',
                'image'
            ])
            ->toJson();
    }
    public function create()
    {
        $departments = JobDepartment::where("status", 1)->get();
        return view('admin.career.create', compact('departments'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'position'     => 'required|string|max:255',
            'department'   => 'required|string|max:255',
            'description'  => 'nullable',
            'location'     => 'nullable|string',
            'job_type'     => 'nullable|string',
            'opening_date' => 'nullable|date',
            'deadline'     => 'nullable|date',
            'image'        => 'nullable|image|max:5120',
        ]);

        $imageName = null;

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName =  FileManager::uploadFile('career/', 300, $image);
        }

        Career::create([
            'position'     => $request->position,
            'slug'     => Str::slug($request->position),
            'department'   => $request->department,
            'description'  => $request->description,
            'location'     => $request->location,
            'type'     => $request->type,
            'opening_date' => $request->opening_date,
            'deadline'     => $request->deadline,
            'image'        => $imageName,
        ]);

        return redirect()->route('admin.career.view')->with('success', 'Job Post Created successfully!');
    }
    public function edit($id)
    {
        $departments = JobDepartment::where('status', 1)->get();
        $career = Career::where('id', $id)->first();

        return view('admin.career.edit', compact('departments', 'career'));
    }
    public function status(Request $request)
    {
        $category = Career::find($request->id);
        $category->status = $request->status;
        $category->save();
        return response()->json([
            'success' => 1,
        ], 200);
    }
    public function delete(Request $request)
    {
        $cr = Career::find($request->id);
        if ($cr->image) {
            FileManager::delete('career/' . $cr->image);
        }
        $cr->delete();
        return response()->json();
    }

    public function update(Request $request, $id)
    {
        // Career fetch by id
        $career = Career::where('id', $id)->first();

        if (!$career) {
            return redirect()->back()->with('error', 'Job Post not found!');
        }

        // Validation
        $request->validate([
            'position'     => 'required|string|max:255',
            'department'   => 'required|string|max:255',
            'description'  => 'nullable|string',
            'location'     => 'nullable|string|max:255',
            'job_type'     => 'nullable|string|max:50',
            'opening_date' => 'nullable|date',
            'deadline'     => 'nullable|date',
            'image'        => 'nullable|image|max:5120',
        ]);

        // Image upload (optional)
        if ($request->hasFile('image')) {
            $career->image = FileManager::updateFile('career/', $career->image, $request->file('image'));
        }

        // Slug generation with duplicate check
        $slug = Str::slug($request->position);


        // Update other fields
        $career->position     = $request->position;
        $career->slug         = $slug;
        $career->department   = $request->department;
        $career->description  = $request->description;
        $career->location     = $request->location;
        $career->type     = $request->type;
        $career->opening_date = $request->opening_date;
        $career->deadline     = $request->deadline;

        $career->save();

        return redirect()->route('admin.career.view')
            ->with('success', 'Job Post updated successfully!');
    }



    // Department functions
    public function department()
    {
        return view('admin.career.department');
    }
    public function departmentDatatables()
    {
        $query = JobDepartment::query();
        $query->latest('id');
        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {

                return '
                <button class="btn btn-primary btn-sm edit"
                    data-id="' . $row->id . '"
                    data-name="' . $row->name . '"
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

            // Edit Column
            ->editColumn('status', function ($row) {
                $checked = $row->status == 1 ? 'checked' : '';
                return '
                        <div class="form-check form-switch">
                            <input class="form-check-input status"
                                type="checkbox"
                                name="status"
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

                'status',
            ])
            ->toJson();
    }
    public function departmentStore(Request $request)
    {
        $validinfo =  $request->validate([
            'name' => 'required',
        ]);

        $category = new JobDepartment();
        $category->name = $request->name;
        if ($category->save()) {
            return response()->json([
                'status' => true,
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => $validinfo
            ], 500);
        }
    }
    public function departmentUpdate(Request $request)
    {
        $validinfo = $request->validate([
            'name' => 'required',
        ]);

        $category = JobDepartment::find($request->id);
        $category->name = $request->name;
        if ($category->save()) {
            return response()->json([
                'success' => 1,
            ], 200);
        } else {
            return response()->json([
                'error' => 0,
                'message' => $validinfo
            ], 500);
        }
    }

    public function departmentDelete(Request $request)
    {
        $category = JobDepartment::find($request->id);
        $category->delete();
        return response()->json();
    }

    public function departmentStatus(Request $request)
    {
        $category = JobDepartment::find($request->id);
        $category->status = $request->status;
        $category->save();
        return response()->json([
            'success' => 1,
        ], 200);
    }
}
