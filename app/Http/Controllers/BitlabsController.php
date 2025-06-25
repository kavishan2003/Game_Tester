<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;


class BitlabsController extends Controller
{
    public function handleCallback(Request $request)
    {

        // echo "hi";

      $postback = $request->all();

      logger($postback);
      
    }
}
