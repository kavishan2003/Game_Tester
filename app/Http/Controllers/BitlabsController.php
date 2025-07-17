<?php

namespace App\Http\Controllers;

use App\Models\Gamers;
use App\Models\Transactions;
use Illuminate\Http\Request;
use App\Models\Bitlabs_callback;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Bavix\Wallet\Models\Transaction;



class BitlabsController extends Controller
{
    public function handleCallback(Request $request)
    {

        // Log the incoming request
        logger('Bitlabs Callback Received');



        logger($request->all());

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

        if (!in_array($request->ip(), $allowedIps)) {
            logger('Unauthorized IP: ');
            logger($request->ip());
            // Log the unauthorized IP
            return response('Unauthorized IP', 403);
        }

        logger('IP check passed: ');

        $appSecret = env('BITLABS_SECRET'); // .env file 
        $receivedHash = $request->query('hash');

        $fullUrl = $request->fullUrl();
        $baseUrlWithoutHash = explode('&hash=', $fullUrl)[0];

        $expectedHash = hash_hmac('sha1', $baseUrlWithoutHash, $appSecret);
        if ($expectedHash !== $receivedHash) {
            logger('Hash mismatch: ');
            return response('Hash mismatch', 403);
        }

        logger('Hash check passed: ');
        // if transaction ID already exists

        $tx = $request->query('tx');
        if (Bitlabs_callback::where('transaction_id', $tx)->exists()) {
            logger('Already processed: ');
            return response('Already processed', 200);
        }


        // echo "hi";

        $postback = $request->all();

        logger("\n" . $postback . "\n");

        $data = $request->all();



        // $parsed = [
        //     'userID'     => $data['uid'] ?? null,
        //     'transactionid' => $data['tx'] ?? null,
        //     'rate'       => $data['raw'] ?? null,
        //     'type'       => $data['type'] ?? null,
        //     'ref'        => $data['ref'] ?? null,
        //     'currency'   => $data['val'] ?? null,
        //     'offer_name' => $data['offer_name'] ?? null,
        //     'ip'         => $data['ip'] ?? $request->ip(),
        //     'offer_delay' => $data['offer:delay'] ?? null,
        //     'offer_id'   => $data['offer_id'] ?? null,
        //     'delay'      => $data['delay'] ?? null,
        //     'offer_state' => $data['offer_state'] ?? null,
        //     'offer_vc_title' => $data['offer:vc_title'] ?? null,
        //     'task_name'  => $data['task_name'] ?? null,
        //     'task_id'    => $data['task_id'] ?? null,
        //     'fscore'     => $data['fscore'] ?? null,
        //     'category'   => $data['category'] ?? null,
        //     'network'    => 'bitlabs',
        //     'offertasktype' => $data['offer:task:type'] ?? null,
        //     'banreason'  => $data['n_reason'] ?? null,
        //     'banstate'   => $data['n_state'] ?? null,
        //     'surveyloi'  => $data['loi'] ?? null,
        //     'surveycategory' => $data['category'] ?? null,
        //     'conversion_country' => $data['country'] ?? null,
        //     'unique_surveyid' => $data['v2sid'] ?? null,
        //     'survey_fraudlevel' => $data['fscore'] ?? null,
        //     'offer_purchase_usd' => $data['offer:task:iap:usd'] ?? null,
        //     'inapp_purchase_event_hidden' => $data['offer:task:hidden'] ?? null,
        // ];

        $parsed = [
            'userID'     => $data['userID'] ?? null,
            'transactionid' => $data['transactionid'] ?? null,
            'rate'       => $data['rate'] ?? null,
            'type'       => $data['type'] ?? null,
            'ref'        => $data['ref'] ?? null,
            'currency'   => $data['currency'] ?? null,
            'offer_name' => $data['offer_name'] ?? null,
            'ip'         => $data['ip'] ?? null,
            'offer_delay' => $data['offer_delay'] ?? null,
            'offer_id'   => $data['offer_id'] ?? null,
            'delay'      => $data['delay'] ?? null,
            'offer_state' => $data['offer_state'] ?? null,
            'offer_vc_title' => $data['offer_vc_title'] ?? null,
            'task_name'  => $data['task_name'] ?? null,
            'task_id'    => $data['task_id'] ?? null,
            'fscore'     => $data['fscore'] ?? null,
            'category'   => $data['category'] ?? null,
            'network'    => 'bitlabs',
            'offertasktype' => $data['offertasktype'] ?? null,
            'banreason'  => $data['banreason'] ?? null,
            'banstate'   => $data['banstate'] ?? null,
            'surveyloi'  => $data['surveyloi'] ?? null,
            'surveycategory' => $data['surveycategory'] ?? null,
            'conversion_country' => $data['conversion_country'] ?? null,
            'unique_surveyid' => $data['unique_surveyid'] ?? null,
            'survey_fraudlevel' => $data['survey_fraudlevel'] ?? null,
            'offer_purchase_usd' => $data['offer_purchase_usd'] ?? null,
            'inapp_purchase_event_hidden' => $data['inapp_purchase_event_hidden'] ?? null,
        ];
        $user = Gamers::where('hash_id', $parsed['userID'])->first();

        $depositID = $user->depositFloat($parsed["offer_purchase_usd"]);

        $transaction_uuid =  $depositID->uuid;

        //    dd($transaction_uuid);
        Bitlabs_Callback::create([
            'uuid' => $transaction_uuid,
            'user_id' => $parsed['userID'],
            'transaction_id' => $parsed['transactionid'],
            'offer_name' => $parsed['offer_name'],
            'ip_address' => $parsed['ip'],
            'offer_value' => $parsed['offer_purchase_usd'],
            'offertasktype' => $parsed['offertasktype'],
            'status' => $parsed['offer_state'],
        ]);

        Transaction::where('id', Transaction::max('id'))->update(['status' => $parsed['offer_state']]);
        Transaction::where('id', Transaction::max('id'))->update(['game_name' => $parsed['offer_name']]);
        Transaction::where('id', Transaction::max('id'))->update(['event_name' => $parsed['task_name']]);

        // $latest = Transactions::max('id');

        // $UserBalance = number_format($user->balanceFloat, 2, '.', '');

        echo 'done';
    }
}
