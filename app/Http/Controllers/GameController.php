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
        //get user email from session
        $storedEmail = session('paypal_email');
        $hashedId =  '';

        if ($storedEmail) {

            $hashedId = hash('sha256', $storedEmail);
        }

        $ip = file_get_contents('https://api64.ipify.org');


        $hashedId = hash('sha256', $ip);

        $userUa  = $request->userAgent();

        $response = Http::withHeaders([
            'User-Agent' => $ip,
            'X-User-Id' => $hashedId,
            'X-Api-Token' => 'cacd309f-4f98-47bb-bec0-a631b9c139f8',
        ])->get('https://api.bitlabs.ai/v2/client/offers', [
            'client_ip'         => $ip,
            'client_user_agent' => $userUa,
            'devices'           => ['android'],
            'is_game'           => 'true',
        ]);
        if (!$response->successful()) {
            return response()->json(['error' => 'API request failed'], 500);
        }

        $offers = $response->json()['data'] ?? [];
        // dd($offers);

        $topOffers = array_slice($offers, 0, 30);


        $formatted = [];

        foreach ($topOffers['offers'] as $offer) {

            $eventPoints = array_sum(
                array_map('floatval', array_column($offer['events'] ?? [], 'points'))
            );


            $points = isset($offer['total_points'])
                ? (float) $offer['total_points']
                : $eventPoints;


            $price = '$' . number_format($points, 2);

            $events = [];
            foreach ($offer['events'] as $event) {

                $points = (float) ($event['points']);

                $Npoints = '$' . number_format($points, 2);

                $events[] = [
                    'name' => $event['name'],
                    'points' => $Npoints ?? '',


                ];
            }
            usort($events, fn($a, $b) => $a['points'] <=> $b['points']);
            $formatted[] = [
                'id'          => Str::uuid()->toString(),
                'title'       => $offer['anchor']      ?? '',
                'description' => $offer['description'] ?? '',
                'thumbnail'   => $offer['icon_url']    ?? '',
                'price'       => $price,
                'play_url'    => $offer['click_url']   ?? '#',
                'disclaimer'  => $offer['disclaimer'],
                'events'      => $events,
            ];
        }

        return view('Game_Tester', ['games' => $formatted]);
    }
}
