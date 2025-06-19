<?php

namespace App\Http\Controllers;

use App\Models\Sessions;
use Illuminate\Log\Logger;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use Coderflex\LaravelTurnstile\Rules\TurnstileCheck;
use Coderflex\LaravelTurnstile\Facades\LaravelTurnstile;

class CaptureController extends Controller
{
    public function send(Request $request)
    {

        dd($request);
        $response = LaravelTurnstile::validate(
            $request->get('cf-turnstile-response') // this will be created from the cloudflare widget.
        );

        if (!($response['success'] ?? false)) {

            return back()
                ->withErrors(['turnstile' => 'CAPTCHA failed, please try again.'])
                ->withInput();
        }

        return response()->json(['result' => $response]);
    }
}
