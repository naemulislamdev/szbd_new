<?php

namespace App\Http\Controllers\Front;

use App\CPU\FileManager;
use App\CPU\ImageManager;
use App\Http\Controllers\Controller;
use App\Model\Complaint;
use App\Models\Complaint as ModelsComplaint;
use Brian2694\Toastr\Facades\Toastr;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ComplainController extends Controller
{
    public function customerComplain()
    {
        if (auth('customer')->check()) {
            return view('web.complain');
        } else {
            return to_route('customer.auth.login')->with('info', 'Please at first you need to login as customer!');
        }
    }
    public function customerComplainStore(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:140',
            'phone' => ['required', function ($attribute, $value, $fail) {
                if (!preg_match('/^(?:\+88|88)?(01[3-9]\d{8})$/', $value)) {
                    $fail('The ' . $attribute . ' must be a valid Bangladesh phone number.');
                }
            }],
            'image' => 'nullable|mimes:png,jpg,jpeg|max:2048',
            'complain_details' => 'required|string|max:500'
        ]);

        $imagePath = FileManager::uploadFile('assets/complaints/', 300, $request->file('image'));
        if ($imagePath == 'def.png') {
            $imagePath = null;
        }
        $complain = ModelsComplaint::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'reasons' => $request->complain_details,
            'images' => $imagePath,
            'status' => false
        ]);
        return back()->with("success", "Your complaint has been send!");
    }
}
