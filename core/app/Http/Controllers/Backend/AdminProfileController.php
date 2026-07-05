<?php

namespace App\Http\Controllers\Backend;

use App\CPU\FileManager;
use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminProfileController extends Controller
{
    public function profile()
    {
        return view('admin.profile.profile');
    }

    public function updateProfile(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'phone' => 'nullable|numeric',
            'email' => 'required|email|unique:admins,email,' . auth('admin')->id(),
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $authUser = auth('admin')->user();
        $admin = Admin::find($authUser->id);
        $admin->name = $request->name;
        $admin->email = $request->email;
        $admin->phone = $request->phone;

        if ($request->hasFile('profile_image')) {
            $imageName = FileManager::updateFile('profile/', $admin->image, $request->file('profile_image'));
            $admin->image = $imageName;
        }

        $admin->save();

        return back()->with('success', 'Profile updated successfully.');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'password' => 'required|min:6|confirmed',
        ]);

        $authUser = auth('admin')->user();
        $admin = Admin::find($authUser->id);
        $admin->password = Hash::make($request->password);
        $admin->save();

        return back()->with('success', 'Password updated successfully.');
    }
}
