<?php

namespace App\Livewire;


use App\Models\emails;
use Livewire\Component;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use Coderflex\LaravelTurnstile\Facades\LaravelTurnstile;


class GameTester extends Component
{
    public array $games = [];
    public $turnstileToken;
    public $is_turnstile = "block";
    public $email;
    public $mailLock = "block";
    public $show = "none";
    public $saveButtonDisabled = "";



    public function SaveTodb()
    {

        $this->validate([
            'email' => 'required|email',
        ]);

        // session()->flash('success', 'Email is valid!');

        $email = $this->email;

        emails::create([
            'email' => $email,

        ]);

        Session::put([
            'email' => $email,
        ]);



        request()->session()->flash('success', 'Email valid and User logged');

        $this->saveButtonDisabled = "disabled";

        // $this->dispatch('mailLock');
        $this->mailLock = "none";
        $this->show = "block";
        //  $this->dispatch('unlock');

    }


    public function updatedturnstileToken(Request $request)
    {



        $response = LaravelTurnstile::validate(
            $this->turnstileToken // this will be created from the cloudflare widget.
        );

        // $response['seccess'];
        // dd($response);

        if (!$response['success']) {
            dd('hrll');
            $this->dispatch('turnstile-fail');
            session()->flash('error', 'Captcha verification failed. Please try again.');
            return;
        }

        $storedEmail = session('paypal_email');


        $hashedId    = $storedEmail ? hash('sha256', $storedEmail) : '';


        // $ip = file_get_contents('https://api64.ipify.org');


        $ip = $request->ip();                      
        $hashedId = hash('sha256', $ip);


        $userUa   = $request->userAgent();


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

        if (! $response->successful()) {

            logger()->error('BitLabs API failed', [

                'status' => $response->status(),

                'body'   => $response->body(),
            ]);
            return;
        }


        $offers = data_get($response->json(), 'data.offers', []);   // safer than $array['data']

        $offers = array_slice($offers, 0, 30);

        $this->games = collect($offers)->map(
            function ($offer) {

                // Sum all event points if total_points is missing

                $eventPoints = collect($offer['events'] ?? [])

                    ->sum(fn($e) => floatval($e['points']));

                $points = (float) ($offer['total_points'] ?? $eventPoints);

                $price  = '$' . number_format($points, 2);

                // Per‑event breakdown
                $events = collect($offer['events'] ?? [])

                    ->map(fn($e) => [
                        'name'   => $e['name'],
                        'points' => '$' . number_format((float) $e['points'], 2),
                    ])

                    ->sortBy('points')
                    ->values()
                    ->all();

                return [
                    'id'          => Str::uuid()->toString(),
                    'title'       => $offer['anchor']      ?? '',
                    'description' => $offer['description'] ?? '',
                    'thumbnail'   => $offer['icon_url']    ?? '',
                    'price'       => $price,
                    'play_url'    => $offer['click_url']   ?? '#',
                    'disclaimer'  => $offer['disclaimer']  ?? '',
                    'events'      => $events,
                ];
            }
        )->all();
        $this->is_turnstile = "none";
        $this->dispatch('model');
    }


    public function openModel($index)
    {

        dd($index);
    }
    public function mount(): void
    {
        if (Session::get('email')) {
            $this->saveButtonDisabled = "disabled";
            $this->email = Session::get('email');
            $this->show = "block";
            $this->mailLock = "none";
        }
    }


    public function capture() {}


    public function render()
    {
        return view('livewire.game-tester');
    }
}
