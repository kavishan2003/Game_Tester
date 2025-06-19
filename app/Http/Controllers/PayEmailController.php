<?php

namespace App\Http\Controllers;

use App\Models\emails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;


class PayEmailController extends Controller
{
    public function save(Request $request)
    {

        $validated = $request->validate([
            'email' => 'required|email',
        ]);

        $email = $request->input('email');


        emails::create([
            'email' => $email,
             
        ]);

       
        Session::put([
            'email' => $email,
        ]);
        

        return back()->with('success', 'Email saved ');
    }
}
