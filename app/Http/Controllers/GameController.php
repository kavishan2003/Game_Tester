<?php

namespace App\Http\Controllers;

use App\Models\emails;
use App\Models\Sessions;
use Illuminate\Log\Logger;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class GameController extends Controller
{
    public $events = [];
    public $turnstileToken;


    public function getGames(Request $request)
    {
   

        return view('Game_Tester');
    }
}
