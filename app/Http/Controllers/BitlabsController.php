<?php

namespace App\Http\Controllers;

use App\Models\Gamers;
use Illuminate\Http\Request;
use App\Models\Bitlabs_callback;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Bavix\Wallet\Models\Transaction;



class BitlabsController extends Controller
{
    public function handleCallback(Request $request)
    {

        //check the ip
        $allowedIps = [
            '20.76.54.40',
            '20.76.54.41',
            '20.76.54.42',
            '20.76.54.43',
            '20.76.54.44',
            '20.76.54.45',
            '20.76.54.46',
            '20.76.54.47',
            '18.199.243.90',
            '18.157.62.114',
            '18.193.24.206',
        ];

        // if (!in_array($request->ip(), $allowedIps)) {
        //     return response('Unauthorized IP', 403);
        // }
        

        $appSecret = env('BITLABS_SECRET'); // .env file එකෙන් ගන්නවා
        $receivedHash = $request->query('hash');

        $fullUrl = $request->fullUrl(); // hash එකත් ඇතුළුව URL එක
        $baseUrlWithoutHash = explode('&hash=', $fullUrl)[0]; // hash එක remove කරනවා

        $expectedHash = hash_hmac('sha1', $baseUrlWithoutHash, $appSecret); // අපේම hash එක ගණනය කරනවා

        // if ($expectedHash !== $receivedHash) {
        //     return response('Hash mismatch', 403);
        // }

        //if transaction ID already exists

         $tx = $request->query('tx');
        if (Bitlabs_callback::where('transaction_id', $tx)->exists()) {
            return response('Already processed', 200);
        }


        // echo "hi";

        $postback = $request->all();

        // logger($postback);

        $data = $request->all();

        $parsed = [
            'userID'     => $data['uid'] ?? null,
            'transactionid' => $data['tx'] ?? null,
            'rate'       => $data['raw'] ?? null,
            'type'       => $data['type'] ?? null,
            'ref'        => $data['ref'] ?? null,
            'currency'   => $data['val'] ?? null,
            'offer_name' => $data['offer_name'] ?? null,
            'ip'         => $data['ip'] ?? $request->ip(),
            'offer_delay' => $data['offer:delay'] ?? null,
            'offer_id'   => $data['offer_id'] ?? null,
            'delay'      => $data['delay'] ?? null,
            'offer_state' => $data['offer_state'] ?? null,
            'offer_vc_title' => $data['offer:vc_title'] ?? null,
            'task_name'  => $data['task_name'] ?? null,
            'task_id'    => $data['task_id'] ?? null,
            'fscore'     => $data['fscore'] ?? null,
            'category'   => $data['category'] ?? null,
            'network'    => 'bitlabs',
            'secret'     => 'f92nf9nasfn23f8n9sn', // You probably don't need to log this
            'offertasktype' => $data['offer:task:type'] ?? null,
            'banreason'  => $data['n_reason'] ?? null,
            'banstate'   => $data['n_state'] ?? null,
            'surveyloi'  => $data['loi'] ?? null,
            'surveycategory' => $data['category'] ?? null,
            'conversion_country' => $data['country'] ?? null,
            'unique_surveyid' => $data['v2sid'] ?? null,
            'survey_fraudlevel' => $data['fscore'] ?? null,
            'offer_purchase_usd' => $data['offer:task:iap:usd'] ?? null,
            'inapp_purchase_event_hidden' => $data['offer:task:hidden'] ?? null,
        ];



        Bitlabs_Callback::create([
            'user_id' => $parsed['userID'],
            'transaction_id' => $parsed['transactionid'],
            'offer_name' => $parsed['offer_name'],
            'ip_address' => $parsed['ip'],
            'offer_value' => $parsed['offer_purchase_usd'],
            'status' => $parsed['offer_state'],
        ]);


        $user = Gamers::where('hash_id', $parsed['userID'])->first();

        //  dd($user);

        $user->depositFloat($parsed['offer_purchase_usd']);

        $UserBalance = number_format($user->balanceFloat, 2, '.', '');

        echo 'done';
    }
}
