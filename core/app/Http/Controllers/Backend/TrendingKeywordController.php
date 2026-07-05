<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\TrendingKeyword;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class TrendingKeywordController extends Controller
{
    public function list()
    {
        return view('admin.trending_keyword.index');
    }

    public function datatables()
    {
        $query = TrendingKeyword::query()->orderBy('sort_order');

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                return '
                <button class="btn btn-primary btn-sm edit"
                    data-id="' . $row->id . '"
                    data-keyword="' . e($row->keyword) . '"
                    data-order="' . $row->sort_order . '"
                    data-bs-toggle="modal"
                    data-bs-target="#editModal">
                    <i class="la la-edit"></i>
                </button>
                <button class="btn btn-danger btn-sm delete" title="Delete"
                    data-id="' . $row->id . '">
                    <i class="la la-trash"></i>
                </button>';
            })
            ->editColumn('is_active', function ($row) {
                $checked = $row->is_active == 1 ? 'checked' : '';
                return '
                    <div class="form-check form-switch">
                        <input class="form-check-input status" type="checkbox"
                            data-id="' . $row->id . '" value="1" ' . $checked . '
                            id="flexSwitch' . $row->id . '">
                        <label class="form-check-label" for="flexSwitch' . $row->id . '"></label>
                    </div>';
            })
            ->rawColumns(['action', 'is_active'])
            ->toJson();
    }

    public function store(Request $request)
    {
        $request->validate(['keyword' => 'required|string|max:255'], [
            'keyword.required' => 'Keyword is required!',
        ]);

        $kw = new TrendingKeyword;
        $kw->keyword    = $request->keyword;
        $kw->sort_order = (int) $request->sort_order;
        $kw->is_active  = 1;

        if ($kw->save()) {
            return response()->json(['status' => true, 'message' => 'Keyword created successfully']);
        }
        return response()->json(['status' => false, 'message' => 'Failed to create'], 500);
    }

    public function update(Request $request)
    {
        $request->validate(['keyword' => 'required|string|max:255'], [
            'keyword.required' => 'Keyword is required!',
        ]);

        $kw = TrendingKeyword::find($request->id);
        $kw->keyword    = $request->keyword;
        $kw->sort_order = (int) $request->sort_order;

        if ($kw->save()) {
            return response()->json(['status' => true, 'message' => 'Keyword updated successfully']);
        }
        return response()->json(['status' => false, 'message' => 'Failed to update'], 500);
    }

    public function status(Request $request)
    {
        $kw = TrendingKeyword::find($request->id);
        $kw->is_active = $request->is_active;
        if ($kw->save()) {
            return response()->json(['success' => true], 200);
        }
        return response()->json(['error' => 0], 500);
    }

    public function delete(Request $request)
    {
        $kw = TrendingKeyword::findOrFail($request->id);
        if ($kw->delete()) {
            return response()->json(['success' => 1], 200);
        }
        return response()->json(['error' => 0], 500);
    }
}
