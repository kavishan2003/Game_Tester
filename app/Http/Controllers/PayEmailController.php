<?php

namespace App\Http\Controllers;

use App\Models\emails;
use Illuminate\Http\Request;

class PayEmailController extends Controller
{
    public function save(Request $request)
    {

        $validated = $request->validate([
            'email' => 'required|email',
        ]);

        $email = $request->input('email');

        if (emails::where('email', $email)->exists()) {
            return back()->with('error', 'This email is already registered ❌');
        }


        emails::create([
            'email' => $email,

        ]);

        //add email to session
        $request->session()->put('paypal_email', $email);
        

        return back()->with('success', 'Email saved ✨');
    }
}
