<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Investor;
use Illuminate\Http\Request;

class InvestorController extends Controller
{
    public function create()
    {
        return view("web-views.investor_form");
    }
    public function store(Request $request)
    {
        $request->validate([
            'name'              => 'required|string|max:255|min:2',
            'mobile_number'     => 'required|string|max:11',
            'address'           => 'nullable|string',
            'occupation'        => 'nullable|max:255',
            'investment_amount' => 'nullable|numeric|min:1',
        ]);
        Investor::create([
            'name'              => $request->name,
            'mobile_number'     => $request->mobile_number,
            'address'           => $request->address,
            'occupation'        => $request->occupation,
            'investment_amount' => $request->investment_amount,
        ]);

        return redirect()->back()->with('success', 'Investor Submit successfully!');
    }
}
