<?php

namespace App\Http\Controllers\Front;

use App\CPU\Helpers;
use App\Http\Controllers\Controller;
use App\Models\Wholesale;
use Carbon\Carbon;
use Rap2hpoutre\FastExcel\FastExcel;
use Illuminate\Http\Request;

class WholesaleController extends Controller
{
    public function create()
    {
        return view("web-views.wholesale.wholesale");
    }
    public function store(Request $request)
    {
        $request->validate([
            'name'              => 'required|string|max:255|min:2',
            'phone'     => 'required|string|max:11',
            'address'           => 'nullable|string',
            'occupation'        => 'nullable|string|max:255',
            'product_quantity'  => 'nullable|numeric|min:1',
        ]);
        Wholesale::create([
            'name'              => $request->name,
            'phone'             => $request->phone,
            'address'           => $request->address,
            'occupation'        => $request->occupation,
            'product_quantity'  => $request->product_quantity,
        ]);

        return redirect()->back()->with('success', 'Wholesale Info Submit successfully!');
    }
      public function status(Request $request)
    {
        $wSale = Wholesale::find($request->id);
        $wSale->wholesale_status = $request->wholesale_status;
        $wSale->wholesale_note = $request->wholesale_note;
        $wSale->save();

        return response()->json([
            'status' => true,
            'id' => $wSale->id,
            'note' => $wSale->wholesale_note
        ]);
    }
    public function wholesaleList(Request $request)  {
        $query_param = [];
        $search = $request['search'];
        if ($request->has('search')) {
            $key = explode(' ', $request['search']);
            $wholesaleList = Wholesale::where(function ($q) use ($key) {
                foreach ($key as $value) {
                    $q->orWhere('name', 'like', "%{$value}%")
                        ->orWhere('phone', 'like', "%{$value}%")
                        ->orWhere('address', 'like', "%{$value}%");
                }
            });
            $query_param = ['search' => $request['search']];
        } else {
            $wholesaleList = new Wholesale();
        }
        $wholesaleList = $wholesaleList->latest()->paginate(Helpers::pagination_limit())->appends($query_param);
        return view('admin-views.wholesale.list', compact('wholesaleList', 'search'));
    }
      public function wholesaleView(Request $request)
    {

        $item = Wholesale::findOrFail($request->id);
        // status update
        if ($item->status !== 1) {
            $item->status = 1;
            $item->save();
        }

        return response()->json([
            'status' => $item->status,
        ]);
}
    public function wholesaleDestroy(Request $request) {
        $wlist = Wholesale::find($request->id);
        $wlist->delete();

        return response()->json();
    }
    public function bulk_export_data()
    {
        $leads = Wholesale::latest()->get();
        //export from leads
        $data = [];
        foreach ($leads as $item) {
            $data[] = [
                'Date' => Carbon::parse($item->created_at)->format('d M Y'),
                'name' => $item->name,
                'phone' => $item->phone,
                'address' => $item->address,
                'occupation' => $item->occupation,
                'product_quantity' => $item->product_quantity,
                'status' => $item->status == 1 ? 'Unseen' : 'Seen',

            ];
        }
        return (new FastExcel($data))->download('wholesale_info.xlsx');
    }
}
