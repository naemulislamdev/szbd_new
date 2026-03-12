<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\SocialMedia;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class SocialMediaController extends Controller
{

    // Social Media
    public function index()
    {
        return view('admin.social_media.index');
    }

    public function datatables()
    {
        $query = SocialMedia::query();
        $query->latest('id');
        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                return '
                <button class="btn btn-primary btn-sm edit"
                    data-id="' . $row->id . '"
                    data-name="' . $row->name . '"
                    data-link="' . $row->link . '"
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
            ->editColumn('icon', function ($row) {
                return '<i class="' . $row->icon . '"></i>';
            })
            // Edit Column
            ->editColumn('active_status', function ($row) {
                $checked = $row->active_status == 1 ? 'checked' : '';
                return '
                        <div class="form-check form-switch">
                            <input class="form-check-input status"
                                type="checkbox"
                                name="colors_active"
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
                'icon',
                'active_status'
            ])
            ->toJson();
    }

    public function store(Request $request)
    {
        $check = SocialMedia::where('name', $request['name'])->first();

        if ($check != null) {
            return response()->json([
                'error' => true,
                'message' => 'Social Media Already taken !'
            ], 400);
        } else {
            if ($request->name == 'facebook') {
                $icon = 'fa fa-facebook';
            }
            if ($request->name == 'twitter') {
                $icon = 'fa fa-twitter';
            }
            if ($request->name == 'pinterest') {
                $icon = 'fa fa-pinterest';
            }
            if ($request->name == 'instagram') {
                $icon = 'fa fa-instagram';
            }
            if ($request->name == 'linkedin') {
                $icon = 'fa fa-linkedin';
            }
            if ($request->name == 'tiktok') {
                $icon = 'bi bi-tiktok';
            }
            if ($request->name == 'youtube') {
                $icon = 'bi bi-youtube';
            }
            $social_media = new SocialMedia();
            $social_media->name = $request->name;
            $social_media->link = $request->link;
            $social_media->icon = $icon;
            $social_media->save();
            return response()->json([
                'success' => 1,
            ]);
        }
    }


    public function update(Request $request)
    {
        $social_media = SocialMedia::find($request->id);
        $social_media->name = $request->name;
        $social_media->link = $request->link;
        $social_media->save();
        return response()->json();
    }

    public function delete(Request $request)
    {
        $br = SocialMedia::find($request->id);
        $br->delete();
        return response()->json();
    }

    public function status(Request $request)
    {
        SocialMedia::where(['id' => $request['id']])->update([
            'active_status' => $request['active_status'],
        ]);
        return response()->json([
            'success' => 1,
        ], 200);
    }
}
