<?php

namespace App\Http\Controllers\Front;

use App\CPU\Helpers;
use App\CPU\ImageManager;
use App\Http\Controllers\Controller;
use App\Models\Career;
use App\Models\JobApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class JobApplicationController extends Controller
{

      public function index(Request $request)
    {
        $query_param = [];
        $search = $request->search;

        if (!empty($search)) {

            $key = explode(' ', $search);

            $application = JobApplication::where(function ($q) use ($key) {
                foreach ($key as $value) {
                    $q->where(function ($sub) use ($value) {
                        $sub->where('name', 'like', "%{$value}%")
                            ->orWhere('email', 'like', "%{$value}%")
                            ->orWhere('phone', 'like', "%{$value}%")
                            ->orWhere('status', 'like', "%{$value}%");
                    });
                }
            })->orderBy('id', 'desc');

            $query_param = ['search' => $search];
        } else {
            $application = JobApplication::orderBy('id', 'desc');
        }

        $applications = $application->paginate(Helpers::pagination_limit())->appends($query_param);

        return view("admin-views.job_applications.view", compact("applications", "search"));
    }

    public function status(Request $request)
    {

        if ($request->ajax()) {
            $app = JobApplication::find($request->id);
            $app->status = $request->status;
            $app->save();
            $data = $request->status;
            return response()->json($data);
        }
    }

    public function delete(Request $request)
    {
        $app = JobApplication::find($request->id);

        ImageManager::delete('files/job_resume/' . $app['resume']);
        $app->delete();
        return response()->json();
    }
}
