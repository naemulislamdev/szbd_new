<?php

namespace App\Http\Controllers\Customer\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function register()
    {
        session()->put('keep_return_url', url()->previous());
        return view('customer-view.auth.register');
    }

    public function submit(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'phone' => ['required', 'regex:/^(01[3-9]\d{8})$/', 'unique:users'],
            'password' => 'required|min:8|same:con_password'
        ], [
            'name.required' => 'First name is required',
        ]);

        $user = User::create([
            'name' => $request['name'],
            'email' => $request['email'],
            'phone' => $request['phone'],
            'is_active' => 1,
            'password' => Hash::make($request['password'])
        ]);

        // $phone_verification = Helpers::get_business_settings('phone_verification');
        // $email_verification = Helpers::get_business_settings('email_verification');
        // if ($phone_verification && !$user->is_phone_verified) {
        //     return redirect(route('customer.auth.check', [$user->id]));
        // }
        // if ($email_verification && !$user->is_email_verified) {
        //     return redirect(route('customer.auth.check', [$user->id]));
        // }

        Auth::guard('customer')->login($user);
        return to_route('user-account')->with('success', 'Registration Successfully!');
    }
}
