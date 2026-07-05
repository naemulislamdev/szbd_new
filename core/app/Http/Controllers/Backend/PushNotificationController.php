<?php

namespace App\Http\Controllers\Backend;

use App\CPU\FcmHelper;
use App\CPU\FileManager;
use App\Http\Controllers\Controller;
use App\Models\PushNotification;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class PushNotificationController extends Controller
{
    public function list()
    {
        return view('admin.push_notification.index');
    }

    public function datatables()
    {
        $query = PushNotification::query()->latest('id');

        return DataTables::of($query)
            ->addIndexColumn()
            ->editColumn('image', function ($row) {
                if (!$row->image) {
                    return '<span class="text-muted">—</span>';
                }
                return "<img src='{$row->image}' width='50' height='50' style='object-fit:cover;border-radius:6px;'/>";
            })
            ->editColumn('status', function ($row) {
                $cls = $row->status === 'sent' ? 'success' : 'danger';
                return '<span class="badge bg-' . $cls . '">' . $row->status . '</span>';
            })
            ->editColumn('created_at', function ($row) {
                return $row->created_at ? $row->created_at->format('Y-m-d H:i') : '';
            })
            ->rawColumns(['image', 'status'])
            ->toJson();
    }

    public function send(Request $request)
    {
        $request->validate([
            'title'  => 'required|string|max:255',
            'body'   => 'required|string',
            'target' => 'required|in:all,android,ios',
        ], [
            'title.required'  => 'Title is required!',
            'body.required'   => 'Body is required!',
            'target.required' => 'Target is required!',
        ]);

        $image_url = null;
        if ($request->file('image')) {
            $file = FileManager::uploadFile('notification/', 300, $request->file('image'));
            $image_url = asset('assets/storage/notification/' . $file);
        }

        $link = $request->link ?: null;

        $result = FcmHelper::sendToTopic($request->target, $request->title, $request->body, $image_url, $link);

        $pn = new PushNotification;
        $pn->title          = $request->title;
        $pn->body           = $request->body;
        $pn->image          = $image_url;
        $pn->link           = $link;
        $pn->target         = $request->target;
        $pn->status         = $result['ok'] ? 'sent' : 'failed';
        $pn->fcm_message_id = $result['response']['name'] ?? null;
        $pn->save();

        if ($result['ok']) {
            return response()->json(['status' => true, 'message' => 'Notification sent to "' . $request->target . '"']);
        }

        return response()->json([
            'status'  => false,
            'message' => 'FCM send failed (HTTP ' . ($result['http'] ?? '0') . ')',
        ]);
    }
}
