<?php

namespace App\Livewire;


use Carbon\Carbon;
use App\Models\emails;
use App\Models\Gamers;
use App\Models\Wallet;
use Livewire\Component;
use PHPSTORM_META\type;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use App\Models\Transactions;
use Illuminate\Http\Request;
use SweetAlert2\Laravel\Swal;
// use Bavix\Wallet\Interfaces\Wallet;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use Jantinnerezo\LivewireAlert\Enums\Position;
use Coderflex\LaravelTurnstile\Facades\LaravelTurnstile;

// use Coderflex\LaravelTurnstile\Facades\LaravelTurnstileMETA\type;

class GameTester extends Component
{
    public array $games = [];
    public array $transactionHistory = [];
    public $turnstileToken;
    public $isTurnstile = "block";
    public $email;
    public $mailLock = "block";
    public $show = "none";
    public $saveButtonDisabled = "";
    public $UserBalance = "0.00";
    public $paypalUpdateCard = "none";
    public $paypalnewCard = "block";
    public $Uemail;
    public $updatedInputF = "";
    public $updatedBtn = "";
    public $hideModel = "hidden";



    public function History()
    {

        $NewEmail = Session::get('email');
        // dd($NewEmail);
        $UserId = Gamers::where('email', $NewEmail)->value('id');
        // dd($UserId);
        $walletId = Wallet::where('holder_id', $UserId)->value('id');
        // dd($walletId);
        $histroys = Transactions::where('wallet_id', $walletId)->get(['uuid', 'type', 'amount', 'updated_at']);
        $historyArray = $histroys->toArray();
        // dd($historyArray);   
        // $this->$transactionHistory = collect($items)



        $this->transactionHistory = collect($historyArray)->map(
            function ($histroy,  $index) {

                $NewEmail = Session::get('email');

                return [
                    'id'          => $histroy['uuid']    ?? '',
                    'type'       => $histroy['type']      ?? '',
                    'amount' => $histroy['amount'] ?? '',
                    'email' => $NewEmail,
                    'updated_at'   => $histroy['updated_at']    ?? '',
                ];
            }
        )->all();
        $this->hideModel = "";
        // $this->dispatch('openHistoryModel');

        // dd($this->transactionHistory);



    }
    public function withdraw()
    {
        $NewEmail = Session::get('email');
        $user = Gamers::where('email', $NewEmail)->first();
        $oldBalance = number_format($user->balanceInt / 100, 2, '.', '');


        if (!($oldBalance >= 5)) {
            $this->dispatch('lowBalance');
            return;
        }

        $user->withdraw(500);
        $this->UserBalance = number_format($user->balanceInt / 100, 2, '.', '');
        $this->dispatch('withdraw');
    }
    public function addWallet()
    {
        // dd(1);
        $Nemail = Session::get('email');
        $user = Gamers::where('email', $Nemail)->first();
        $user->deposit(500);
        $this->UserBalance = number_format($user->balanceInt / 100, 2, '.', '');
    }

    public function UpEmail()
    {


        $this->validate([
            'Uemail' => 'required|email',
        ]);



        $user_name = Session::get('Uname');

        $updated_count = Gamers::where('Uname', $user_name)->count();

        if ($updated_count > 3) {

            $this->dispatch('limit');
            $this->updatedInputF = "disabled";
            $this->updatedBtn = "disabled";
            return redirect();
        }



        Gamers::where('email', $this->email)
            ->create([
                'email' => $this->Uemail,
                'Uname' => $user_name,
            ]);

        // $who = Gamers::where('updated_times', $this->Uemail)->value('updated_times');
        // dd($who);
        Session::put([
            'email' => $this->Uemail,
        ]);


        request()->session()->flash('success', 'Email updated successfully');
        $this->updatedInputF = "disabled";
        $this->updatedBtn = "disabled";
        $this->Uemail = "";
        $this->dispatch('refreshPage');
    }

    public function SaveTodb()
    {

        $this->validate([
            'email' => 'required|email',
        ]);

        $email = $this->email;

        $localPart = strstr($this->email, '@', true);

        Gamers::create([
            'email' => $email,
            'Uname' => $localPart,
        ]);

        Session::put([
            'email' => $email,
            'Uname' => $localPart,
        ]);

        $user = Gamers::where('email', $email)->first();
        //bonus
        // $user->deposit(5); 

        $this->UserBalance = number_format($user->balanceInt / 100, 2, '.', '');

        $this->updatedInputF = "disabled";

        $this->updatedBtn = "disabled";

        $this->show = "block";

        $this->dispatch(
            'alert',
            type: 'success',
            title: 'Email saved ',
            position: 'center',
        );
        $this->dispatch('refreshPage');
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
        // dd($offers);
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
                    'requirements' => $offer['requirements'] ?? '',
                    'events'      => $events,
                ];
            }
        )->all();
        $this->isTurnstile = "none";
        $this->dispatch('model');
    }


    public function openModel($index)
    {

        dd($index);
    }
    public function mount(): void
    {
        if (Session::get('email')) {
            // $this->saveButtonDisabled = "disabled";
            $this->email = Session::get('email');
            $this->show = "block";
            // $this->mailLock = "none";
            $exEmail = Session::get('email');
            $user = Gamers::where('email', $exEmail)->first();

            $this->UserBalance = number_format(($user?->balanceInt ?? 0) / 100, 2, '.', '');

            $this->paypalUpdateCard = "block";
            $this->paypalnewCard = "none";
        }
    }


    public function capture() {}


    public function render()
    {
        return view('livewire.game-tester');
    }
}



// $exiting_update_times = Gamers::where('updated_times', $this->email)->value('updated_times');
        // $exiting_update_date = Gamers::where('updated_times', $this->email)->value('updated_at');

        // $daysOld = Carbon::parse($exiting_update_date)->diffInDays();

        // // dd($exiting_update_times);
        // $update = $exiting_update_times + 1;

        // // dd($upda);


        // if (!($exiting_update_times < 2)) {

        //     if (!($daysOld > 29)) {

        //         request()->session()->flash('error', 'try after 30 days');
        //         return;
        //     }
        // }

        // $this->dispatch('confirmation');
