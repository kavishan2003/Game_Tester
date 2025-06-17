<?php

namespace App\Http\Controllers;

use App\Models\emails;
use Illuminate\Http\Request;

class PayEmailController extends Controller
{
    public function save(Request $request){

        $email = $request->input('email');

        // dd($email);

        $validated = $request->validate([
            'email' => 'required|email|unique:emails,email',   // tweak table/column as needed
        ]);

        emails::create([
            'email' => $email,
        ]);

        return back()->with('success', 'Email saved ✨');
    }
}
