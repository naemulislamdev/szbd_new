<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\JobDepartment;
use Illuminate\Http\Request;

class JobDepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $departments = JobDepartment::paginate(20);
        return view("admin-views.career.department", compact("departments"));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function status(Request $request)
    {
        if ($request->ajax()) {
            $career = JobDepartment::find($request->id);
            $career->status = $request->status;
            $career->save();
            $data = $request->status;
            return response()->json($data);
        }

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);
        // Validation passed → data save
        JobDepartment::create($request->all());

        return redirect()->back()->with('success', 'Department Creadted Successfully!');
    }



    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $jobDepartment = JobDepartment::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $jobDepartment->update([
            'name' => $request->name
        ]);

        return redirect()->back()->with('success', 'Department updated!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, $id)
    {
        $dp = JobDepartment::findOrFail($id);
        $dp->delete();
        return response()->json();

        return redirect()->back()
            ->with('success', 'Department deleted successfully!');

    }
}
