<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class GameController extends Controller
{
    public $events = [];
    public $turnstileToken;


    public function getGames(Request $request)
    {
        // $ip = $request->ip();
        $ip = file_get_contents('https://api64.ipify.org');
        // dd($ip);
        // dd($ip);
        $userUa  = $request->header(
            'User-Agent',
            'Mozilla/5.0 (Windows NT 10.0; Win64; x64)'
        );

        $response = Http::withHeaders([
            'User-Agent' => $ip,
            'X-User-Id' => '123',
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
        // dd($response->json());

        $offers = $response->json()['data'] ?? [];

        // dd($offers);

        $topOffers = array_slice($offers, 0, 30);
        // dd($topOffers);

        $formatted = [];

        foreach ($topOffers['offers'] as $offer) {

            $eventPoints = array_sum(
                array_map('floatval', array_column($offer['events'] ?? [], 'points'))
            );


            $points = isset($offer['total_points'])
                ? (float) $offer['total_points']
                : $eventPoints;


            $price = '$' . number_format($points / 100, 2);

            $events = [];
            foreach ($offer['events'] as $event) {
                // dd($event);
                $points = (float) ($event['points'] );

                // if ($points === 0) {
                //     continue;
                // }
                $Npoints = '$' . number_format($points / 100, 4);

                $events[] = [
                    'name' => $event['name'] ,
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



      
        return view('welcome', ['games' => $formatted]);
    }
}
