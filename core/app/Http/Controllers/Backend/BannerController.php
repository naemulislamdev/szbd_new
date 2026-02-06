<?php

namespace App\Http\Controllers\Backend;

use App\CPU\FileManager;
use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class BannerController extends Controller
{
    function list()
    {
        return view('admin.banner.index');
    }
    public function datatables()
    {
        $query = Banner::query();
        $query->latest('id');

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {

                return '
                <button class="btn btn-primary btn-sm edit"
                    data-id="' . $row->id . '"
                    data-url="' . $row->url . '"
                    data-image="' . asset('assets/storage/banner/' . $row->photo) . '"
                    data-type="' . $row->banner_type . '"
                    data-orderNumber="' . $row->order_number . '"
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
            ->editColumn('photo', function ($row) {
                $url = asset('assets/storage/banner/' . $row->photo);

                return "<img src='{$url}' width='60' height='60'
            style='object-fit:cover;border-radius:6px;' />";
            })
            // Edit Column
            ->editColumn('published', function ($row) {

                $checked = $row->published == 1 ? 'checked' : '';

                return '
                        <div class="form-check form-switch">
                            <input class="form-check-input status"
                                type="checkbox"
                                name="published"
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
                'published',
                'photo'

            ])
            ->toJson();
    }

    public function store(Request $request)
    {
        $validinfo = $request->validate([
            'url' => 'required',
            'image' => 'required',
        ], [
            'url.required' => 'url is required!',
            'image.required' => 'Image is required!',

        ]);

        $banner = new Banner;
        $banner->banner_type = $request->banner_type;
        $banner->order_number = $request->order_number;
        $banner->resource_type = $request->resource_type;
        $banner->resource_id = $request[$request->resource_type . '_id'];
        $banner->url = $request->url;
        $banner->photo = FileManager::uploadFile('banner/', 300, $request->file('image'));

        if ($banner->save()) {
            return response()->json([
                'status' => true,
                'message' => 'Banner created successfully'
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => $validinfo
            ], 500);
        }
    }
    public function status(Request $request)
    {
        $banner = Banner::find($request->id);
        $banner->published = $request->published;
        if ($banner->save()) {
            return response()->json([
                'success' => 1,
            ], 200);
        } else {
            return response()->json([
                'error' => 0,
            ], 500);
        }
    }
    public function update(Request $request)
    {
        $validinfo =   $request->validate([
            'url' => 'required',
        ], [
            'url.required' => 'url is required!',
        ]);

        $banner = Banner::find($request->id);
        $banner->banner_type = $request->banner_type;
        $banner->order_number = $request->order_number;
        $banner->resource_type = $request->resource_type;
        $banner->resource_id = $request[$request->resource_type . '_id'];
        $banner->url = $request->url;
        if ($request->file('image')) {
            $banner->photo = FileManager::updateFile('banner/', $banner->photo, $request->file('image'));
        }
        if ($banner->save()) {
            return response()->json([
                'status' => true,
                'message' => 'Banner Updated successfully'
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => $validinfo
            ], 500);
        }
    }

    public function delete(Request $request)
    {

        $banner = Banner::findOrFail($request->id);
        $path = 'banner/' . $banner->photo;

        FileManager::delete($path);

        if ($banner->delete()) {
            return response()->json([
                'success' => 1,
            ], 200);
        }

        return response()->json([
            'error' => 0,
        ], 500);
    }
}
