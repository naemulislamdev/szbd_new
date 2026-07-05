<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Investor;
use Illuminate\Http\Request;

class InvestorController extends Controller
{
    public function create()
    {
        return view("web.investor.investor");
    }
    public function store(Request $request)
    {
        $request->validate([
            'name'              => 'required|string|max:255|min:2',
            'mobile_number'     => 'required|string|max:11',
            'address'           => 'required|string| max: 700',
            'occupation'        => 'nullable|max:255',
            'investment_amount' => 'nullable|string|min:1|max: 11',
            'comment'           => 'nullable|string|min:1 | max: 700',
        ]);

        // DB save
        $investor = Investor::create([
            'name'              => $request->name,
            'mobile_number'     => $request->mobile_number,
            'address'           => $request->address,
            'occupation'        => $request->occupation,
            'investment_amount' => $request->investment_amount . " Lakh",
            'comment'           => $request->comment,
        ]);

        // WhatsApp message
        $message = "Investment Submission:\n
        Name: {$request->name}
        Phone: {$request->mobile_number}
        Address: {$request->address}
        Occupation: {$request->occupation}
        Investment: {$request->investment_amount} Lakh
        Comment: {$request->comment}";

        $phone = "8801934657964";
        $url = "https://wa.me/{$phone}?text=" . urlencode($message);

        // Redirect to WhatsApp after saving
        return redirect()->away($url);
    }
}
