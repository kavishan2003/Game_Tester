<?php

namespace App\Http\Controllers;

use App\Models\Transactions;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index()
    {

        $users = Transactions::all(); // <-- THIS is the model fetching from the DB
        return response()->json($users); // returns JSON to frontend
    }
}
