<?php

namespace App\Http\Controllers\Customer\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    public $company_name;
    public function login()
    {
        return view('customer-view.auth.login');
    }
    public function submit(Request $request)
    {
        $request->validate([
            'email'    => 'required',
            'password' => 'required|min:8',
        ]);

        $remember = $request->boolean('remember');

        // Find user by email or phone
        $user = User::where('email', $request->email)
            ->first();

        if (!$user) {
            return back()
                ->withInput()
                ->with('error', 'Invalid email/phone or password.');
        }

        if ($user->is_active == 0) {
            return back()
                ->withInput()
                ->with('error', 'Your account has been suspended.');
        }

        // Attempt login
        if (auth('customer')->attempt([
            'email'     => $user->email,
            'password'  => $request->password,
            'is_active' => 1,
        ], $remember)) {

            return redirect()->intended( route('user-account'));
        }

        return back()
            ->withInput()
            ->with('error', 'Invalid email/phone or password.');
    }
    public function logout(Request $request)
    {
        auth()->guard('customer')->logout();
        session()->forget('wish_list');

        return redirect()->route('home')->with('warning', 'Come back soon, ' . '!');
    }
}
