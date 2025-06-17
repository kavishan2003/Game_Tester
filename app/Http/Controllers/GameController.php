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


           // Verify Turnstile
        // $response = Http::asForm()->post('https://challenges.cloudflare.com/turnstile/v0/siteverify', [
        //     'secret' => config('services.turnstile.secret'),
        //     'response' => $this->turnstileToken,
        //     'remoteip' => request()->ip(),
        // ]);

        // if (!$response->json('success')) {
        //     session()->flash('error', 'Captcha verification failed. Please try again.');
        //     return;
        // }

       


        // Step 1: Get user IP address
        // $ip = $request->ip();
        $ip = file_get_contents('https://api64.ipify.org');
        // dd($ip);
        $userUa  = $request->header(
            'User-Agent',
            'Mozilla/5.0 (Windows NT 10.0; Win64; x64)'
        );

        // Step 2: Make GET request to BitLabs API
        $response = Http::withHeaders([
            'User-Agent' => $ip, // or you can use any fake user agent
            'X-User-Id' => '123',
            'X-Api-Token' => 'cacd309f-4f98-47bb-bec0-a631b9c139f8',
        ])->get('https://api.bitlabs.ai/v2/client/offers', [
            'client_ip'         => $ip,
            'client_user_agent' => $userUa,
            'devices'           => ['android'],
            'is_game'           => 'true',
        ]);
        // Step 3: Check if API request was successful
        if (!$response->successful()) {
            return response()->json(['error' => 'API request failed'], 500);
        }
        // dd($response->json());

        // Step 4: Get the data from response
        $offers = $response->json()['data'] ?? [];

        // dd($offers);

        // Step 5: Get top 30 only
        $topOffers = array_slice($offers, 0, 30);
        // dd($topOffers);

        // Step 6: Format each game data
        $formatted = [];

        foreach ($topOffers['offers'] as $offer) {

            $eventPoints = array_sum(
                array_map('floatval', array_column($offer['events'] ?? [], 'points'))
            );


            $points = isset($offer['total_points'])
                ? (float) $offer['total_points']
                : $eventPoints;


            $price = '$' . number_format($points / 100, 2);


            // $formatted[] = [
            //     'id'          => Str::uuid()->toString(),
            //     'title'       => $offer['anchor']       ?? '',
            //     'description' => $offer['description']  ?? '',
            //     'thumbnail'   => $offer['icon_url']     ?? '',
            //     'price'       => $price,
            //     'play_url'    => $offer['click_url']    ?? '#',
            // ];

            $events = [];
            foreach ($offer['events'] as $event) {
                // dd($event);
                $points = (int) ($event['points'] ?? 0);

                if ($points === 0) {
                    continue;
                }
                  $Npoints = '$' . number_format($points / 100, 2);

                $events[] = [
                    'name' => $event['name'] ?? '',
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

                // 👇  the task list goes *inside* the offer card
                'events'      => $events,
            ];
            // dd($formatted);
        }



        // Step 7: Return to front-end
        return view('welcome', ['games' => $formatted]);
    }
}
