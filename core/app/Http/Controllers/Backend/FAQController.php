<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\HelpTopic;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;


class FAQController extends Controller
{

    function list()
    {
        return view('admin.faq.list');
    }
    public function datatables()
    {
        $query = HelpTopic::query();
        $query->latest('id');
        return DataTables::of($query)
            ->addIndexColumn()

            ->addColumn('action', function ($row) {
                return '
                  <button class="btn btn-primary btn-sm edit"
                    data-id="' . $row->id . '"
                    data-question="' . $row->question . '"
                    data-answer="' . $row->answer . '"
                    data-ranking="' . $row->ranking . '"
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

    public function store(Request $request)
    {
        $request->validate([
            'question' => 'required',
            'answer'   => 'required',
        ], [
            'question.required' => 'Question name is required!',
            'answer.required'   => 'Question answer is required!',

        ]);
        $helps = new HelpTopic();
        $helps->question = $request->question;
        $helps->answer = $request->answer;
        $helps->ranking = $request->ranking ? $request->ranking : 1;
        $helps->save();
        return response()->json([
            'status' => true,
        ]);
    }
    public function status(Request $request)
    {

        $helps = HelpTopic::findOrFail($request->id);
        if ($helps->status == 1) {
            $helps->update(["status" => 0]);
        } else {
            $helps->update(["status" => 1]);
        }
        return response()->json(['success' => true]);
    }
    public function update(Request $request)
    {
        $request->validate([
            'question' => 'required',
            'answer'   => 'required',
        ], [
            'question.required' => 'Question name is required!',
            'answer.required'   => 'Question answer is required!',

        ]);
        $helps = HelpTopic::find($request->id);
        $helps->question = $request->question;
        $helps->answer = $request->answer;
        $helps->ranking = $request->ranking;
        $helps->update();
        return response()->json([
            'status' => true,
        ]);
    }



    public function destroy(Request $request)
    {
        $helps = HelpTopic::find($request->id);
        $helps->delete();
        return response()->json([
            'status' => true,
        ]);
    }
}
