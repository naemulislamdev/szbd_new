<?php

namespace App\Http\Controllers\Backend\Auth;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function login()
    {
        if (auth('admin')->check()) {
            return redirect()->route('admin.dashboard.index');
        }
        return view('auth.admin.login');
    }
    public function submit(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6'
        ]);
        $admin = Admin::where('email', $request->email)->first();
        if (isset($admin) && $admin->status == 0) {
            return back()->with('error', 'You are blocked!, contact with admin.');
        } else {
            if (auth('admin')->attempt(['email' => $request->email, 'password' => $request->password], $request->remember)) {
                return to_route('admin.dashboard.index')->with('success', 'Login successful');
            }
        }

        return redirect()->back()->withInput($request->only('email', 'remember'))
            ->withErrors(['Credentials does not match.']);
    }

    public function logout(Request $request)
    {
        auth()->guard('admin')->logout();
        $request->session()->invalidate();
        return redirect()->route('admin.auth.login');
    }
}
