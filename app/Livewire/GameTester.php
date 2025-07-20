<?php

namespace App\Livewire;


use Carbon\Carbon;
use App\Models\emails;
use App\Models\Gamers;
use App\Models\Wallet;
use App\Models\Ipcatch;
use Livewire\Component;
use PHPSTORM_META\type;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use App\Models\Transactions;
use Illuminate\Http\Request;
use Livewire\WithPagination;
use SweetAlert2\Laravel\Swal;
// use Bavix\Wallet\Interfaces\Wallet;
use Illuminate\Support\Facades\DB;
use Bavix\Wallet\Models\Transaction;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use Jantinnerezo\LivewireAlert\Enums\Position;
use Coderflex\LaravelTurnstile\Facades\LaravelTurnstile;
// use Coderflex\LaravelTurnstile\Facades\LaravelTurnstileMETA\type;

class GameTester extends Component
{
    use WithPagination;

    public $UserIp;
    public $UserAgent;
    public $Userhash;
    public $search = "";
    public array $games = [];
    public array $progress = [];
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
    public $inProgressModel = "hidden";
    public $empty = "hidden";
    public $addShow = "disabled";
    public $withdrawShow = "disabled";


    public function Inprogress()
    {
        $this->inProgressModel = "";
    }

    public function History()
    {

        $NewEmail = Session::get('email');
        // dd($NewEmail);
        $UserId = Gamers::where('email', $NewEmail)->value('id');
        // dd($UserId);
        $walletId = Wallet::where('holder_id', $UserId)->value('id');

        $this->hideModel = "";
    }

    public function withdraw()
    {
        $NewEmail = Session::get('email');
        $user = Gamers::where('email', $NewEmail)->first();
        $oldBalance = number_format($user->balanceFloat, 2, '.', '');


        if (!($oldBalance > 5)) {
            $this->dispatch('lowBalance');
            return;
        }

        $amount = $oldBalance - 5.00;

        $user->withdrawFloat($amount);
        $this->UserBalance = number_format($user->balanceFloat, 2, '.', '');
        $this->dispatch('withdraw');
    }

    public function addWallet()
    {
        // dd(1);
        $Nemail = Session::get('email');
        $user = Gamers::where('email', $Nemail)->first();
        $user->depositFloat(8.00);
        $this->UserBalance = number_format($user->balanceFloat, 2, '.', '');
    }

    public function UpEmail()
    {


        $this->validate([
            'Uemail' => 'required|email',
        ]);

        $new_email = $this->Uemail;

        $similarEmail = Gamers::where('email', 'LIKE', '%' . $new_email . '%')->first();

        if ($similarEmail) {
            $this->dispatch('already');
            return;
        }


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
                'ip' => $this->UserIp,
                'hash_id' => $this->Userhash,
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

        $similarEmail = Gamers::where('email', 'LIKE', '%' . $email . '%')->first();

        if ($similarEmail) {
            $this->dispatch('already');
            return;
        }

        $ip = $this->UserIp;

        $hashedId = hash('sha256', $ip);

        $this->Userhash = $hashedId;

        $localPart = strstr($this->email, '@', true);

        Gamers::create([
            'email' => $email,
            'Uname' => $localPart,
            'ip' => $this->UserIp,
            'hash_id' => $this->Userhash,
        ]);

        Session::put([
            'email' => $email,
            'Uname' => $localPart,
        ]);

        $user = Gamers::where('email', $email)->first();
        //bonus
        // $user->deposit(5); 

        $this->UserBalance = number_format($user->balanceFloat, 2, '.', '');

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

    // public function updatedturnstileToken(Request $request)
    // {

    //     // logger($request->headers->all());
    //     // logger($this->UserIp);
    //     logger("hi");
    //     $response = LaravelTurnstile::validate(
    //         $this->turnstileToken // this will be created from the cloudflare widget.
    //     );
    //     if (!$response['success']) {
    //         $this->dispatch('turnstile-fail');
    //         session()->flash('error', 'Captcha verification failed. Please try again.');
    //         return;
    //     }

    //     $storedEmail = session('email');

    //     logger('Stored Email:');
    //     logger($storedEmail);

    //     $hashedId    = $storedEmail ? hash('sha256', $storedEmail) : '';

    //     $ip = $this->UserIp;

    //     $hashedId = hash('sha256', $ip);

    //     $this->Userhash = $hashedId;

    //     $userUa = $request->userAgent();

    //     $this->UserAgent = $userUa;

    //     // Detect device type
    //     if (stripos($this->UserAgent, 'Android') !== false) {
    //         $deviceType = ['android'];
    //     } elseif (stripos($this->UserAgent, 'iPhone') !== false) {
    //         $deviceType = ['iphone'];
    //     } elseif (stripos($this->UserAgent, 'iPad') !== false) {
    //         $deviceType = ['ipad'];
    //     } else {
    //         $deviceType = ['android'];
    //     }

    //     $response = Http::withHeaders([
    //         'User-Agent' => $userUa,
    //         'X-User-Id' => $hashedId,
    //         'X-Api-Token' => 'cacd309f-4f98-47bb-bec0-a631b9c139f8',
    //         // 'X-Api-Token' => 'f94fbb03-47a6-48b5-9aa3-bd7f04cc156d',
    //     ])->get('https://api.bitlabs.ai/v2/client/offers', [
    //         'client_ip'         => $ip,
    //         'client_user_agent' => $userUa,
    //         'devices' => ['android', 'iphone', 'ipad'],
    //         'is_game'           => 'true',
    //     ]);

    //     // logger($response);

    //     if (! $response->successful()) {

    //         logger()->error('BitLabs API failed', [

    //             'status' => $response->status(),

    //             'body'   => $response->body(),
    //         ]);
    //         return;
    //     }
    //     $response_json = $response->json();

    //     // dd($response_json['data']);

    //     if (isset($response_json['data']['started_offers'])) {
    //         $started_offers = data_get($response->json(), 'data.started_offers', []);
    //     }

    //     $offers = data_get($response->json(), 'data.offers', []);   // safer than $array['data']

    //     $iphoneOffers = collect($offers)->filter(function ($offer) {
    //         return in_array('iphone', $offer['categories'] ?? []);
    //     })->values();

    //     $androidOffers = collect($offers)->filter(function ($offer) {
    //         return in_array('Android', $offer['categories'] ?? []);
    //     })->values();

    //     if ($deviceType == ['iphone', 'ipad']) {
    //         $offers = $iphoneOffers->all();
    //     }
    //     if ($deviceType == ['android']) {
    //         $offers = $androidOffers->all();
    //     }

    //     $offers = array_slice($offers, 0, 30);

    //     // Gathering started offers
    //     if (isset($response_json['data']['started_offers'])) {
    //         $this->progress = collect($started_offers)->map(
    //             function ($started_offers) {

    //                 $date = $started_offers['latest_date'] ?? null;

    //                 $points = (float) ($started_offers['total_points']);

    //                 $price  = '$' . number_format($points, 2);

    //                 $events = collect($started_offers['events'] ?? [])

    //                     ->map(fn($e) => [
    //                         'name'   => $e['name'],
    //                         'points' => '$' . number_format((float) $e['points'], 2),
    //                         'status' => $e['status']
    //                     ])

    //                     ->sortBy('points')
    //                     ->values()
    //                     ->all();

    //                 $relativeTime = $date ? Carbon::parse($date)->diffForHumans() : '';

    //                 return [
    //                     'name' => $started_offers['anchor'],
    //                     'date'   => $relativeTime   ?? '',
    //                     'id'          => Str::uuid()->toString(),
    //                     'title'       => $started_offers['anchor']      ?? '',
    //                     'description' => $offestarted_offersr['description'] ?? '',
    //                     'categories'  => $offer['categories']  ?? [],
    //                     'thumbnail'   => $started_offers['icon_url']    ?? '',
    //                     'price'       => $price,
    //                     'play_url'    => $started_offers['continue_url']   ?? '#',
    //                     'disclaimer'  => $started_offers['disclaimer']  ?? '',
    //                     'requirements' => $started_offers['requirements'] ?? '',
    //                     'events'      => $events,
    //                     'event_count' => count($events),
    //                 ];
    //             }
    //         )->all();
    //     }
    //     // Gethering Fresh offers
    //     $this->games = collect($offers)->map(
    //         function ($offer) {

    //             $eventPoints = collect($offer['events'] ?? [])

    //                 ->sum(fn($e) => floatval($e['points']));

    //             $points = (float) ($offer['total_points'] ?? $eventPoints);

    //             $price  = '$' . number_format($points, 2);

    //             $events = collect($offer['events'] ?? [])

    //                 ->map(fn($e) => [
    //                     'name'   => $e['name'],
    //                     'points' => '$' . number_format((float) $e['points'], 2),
    //                 ])

    //                 ->sortBy('points')
    //                 ->values()
    //                 ->all();

    //             return [
    //                 'id'          => Str::uuid()->toString(),
    //                 'title'       => $offer['anchor']      ?? '',
    //                 'description' => $offer['description'] ?? '',
    //                 'categories'  => $offer['categories']  ?? [],
    //                 'thumbnail'   => $offer['icon_url']    ?? '',
    //                 'price'       => $price,
    //                 'play_url'    => $offer['click_url']   ?? '#',
    //                 'disclaimer'  => $offer['disclaimer']  ?? '',
    //                 'requirements' => $offer['requirements'] ?? '',
    //                 'events'      => $events,
    //                 'event_count' => count($events),
    //             ];
    //         }
    //     )->all();
    //     $this->isTurnstile = "none";
    //     $this->dispatch('model');
    // }


    public function local(Request $request)
    {

        // // logger($request->headers->all());
        // logger($this->UserIp);
        // logger("hi");
        // $response = LaravelTurnstile::validate(
        //     $this->turnstileToken // this will be created from the cloudflare widget.
        // );
        // if (!$response['success']) {
        //     $this->dispatch('turnstile-fail');
        //     session()->flash('error', 'Captcha verification failed. Please try again.');
        //     return;
        // }
        // logger('Dont call this:');

        $storedEmail = session('email');


        logger($storedEmail);



        $hashedId    = $storedEmail ? hash('sha256', $storedEmail) : '';

        $ip = $this->UserIp;

        logger($ip);

        $hashedId = hash('sha256', $ip);

        $this->Userhash = $hashedId;

        $userUa = $request->userAgent();

        $this->UserAgent = $userUa;

        // dd($userUa);

        // Detect device type
        if (stripos($this->UserAgent, 'Android') !== false) {
            $deviceType = ['android'];
        } elseif (stripos($this->UserAgent, 'iPhone') !== false) {
            $deviceType = ['iphone'];
        } elseif (stripos($this->UserAgent, 'iPad') !== false) {
            $deviceType = ['ipad'];
        } else {
            $deviceType = ['android'];
        }

        $response = Http::withHeaders([
            'User-Agent' => $userUa,
            'X-User-Id' => $hashedId,
            'X-Api-Token' => 'cacd309f-4f98-47bb-bec0-a631b9c139f8',
            // 'X-Api-Token' => 'f94fbb03-47a6-48b5-9aa3-bd7f04cc156d',
        ])->get('https://api.bitlabs.ai/v2/client/offers', [
            'client_ip'         => $ip,
            'client_user_agent' => $userUa,
            'devices' => $deviceType,
            'is_game'           => 'true',
        ]);

        // logger($response);

        if (! $response->successful()) {

            logger()->error('BitLabs API failed', [

                'status' => $response->status(),

                'body'   => $response->body(),
            ]);
            return;
        }
        $response_json = $response->json();

        // dd($response_json);
        //exept for dekstop
        // if ($deviceType == ['desktop']) {
        //     //i want to filter out is web_to_mobile is true and dont return those offers
        //     $offers = collect($response_json['data']['offers'] ?? [])
        //         ->filter(fn($offer) => !isset($offer['web_to_mobile']) || !$offer['web_to_mobile'])
        //         ->values()
        //         ->all();
        //     $this->isTurnstile = "none";
        //     $this->empty = "";
        //     return;
        // }

        // dd($response_json);
        // logger($response_json['data']);

        if (isset($response_json['data']['started_offers'])) {
            $started_offers = data_get($response->json(), 'data.started_offers', []);
        }
        // dd($started_offers);

        $offers = data_get($response->json(), 'data.offers', []);   // safer than $array['data']

        $iphoneOffers = collect($offers)->filter(function ($offer) {
            return in_array('iphone', $offer['categories'] ?? []);
        })->values();

        $androidOffers = collect($offers)->filter(function ($offer) {
            return in_array('Android', $offer['categories'] ?? []);
        })->values();

        if ($deviceType == ['iphone', 'ipad']) {
            $offers = $iphoneOffers->all();
        }
        if ($deviceType == ['android']) {
            $offers = $androidOffers->all();
        }
        // dd('called');

        $offers = array_slice($offers, 0, 30);

        if (isset($response_json['data']['started_offers'])) {
            $this->progress = collect($started_offers)->map(
                function ($started_offers) {

                    $date = $started_offers['latest_date'] ?? null;

                    $points = (float) ($started_offers['total_points']);

                    $price  = '$' . number_format($points, 2);

                    $events = collect($started_offers['events'] ?? [])

                        ->map(fn($e) => [
                            'name'   => $e['name'],
                            'points' => '$' . number_format((float) $e['points'], 2),
                            'status' => $e['status']
                        ])

                        ->sortBy('points')
                        ->values()
                        ->all();

                    $relativeTime = $date ? Carbon::parse($date)->diffForHumans() : '';

                    return [
                        'name' => $started_offers['anchor'],
                        'date'   => $relativeTime   ?? '',
                        'id'          => Str::uuid()->toString(),
                        'title'       => $started_offers['anchor']      ?? '',
                        'description' => $offestarted_offersr['description'] ?? '',
                        'thumbnail'   => $started_offers['icon_url']    ?? '',
                        'price'       => $price,
                        'play_url'    => $started_offers['continue_url']   ?? '#',
                        'disclaimer'  => $started_offers['disclaimer']  ?? '',
                        'requirements' => $started_offers['requirements'] ?? '',
                        'events'      => $events,
                        'event_count' => count($events),
                    ];
                }
            )->all();
        }

        // dd($offers);
        $this->games = collect($offers)->map(
            function ($offer) {

                $eventPoints = collect($offer['events'] ?? [])

                    ->sum(fn($e) => floatval($e['points']));

                $points = (float) ($offer['total_points'] ?? $eventPoints);

                $price  = '$' . number_format($points, 2);

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
                    'event_count' => count($events),
                ];
            }
        )->all();
        $this->isTurnstile = "none";
        $this->dispatch('model');
    }

    public function updatedUserIp()
    {
        $this->local(request());
    }

    public function mount(): void
    {
        //i want to call the local function when the page loads
        // $this->local(request());
        if (Session::get('email')) {
            // $this->saveButtonDisabled = "disabled";
            $this->email = Session::get('email');
            $this->addShow = "";
            $this->show = "block";
            // $this->mailLock = "none";
            $exEmail = Session::get('email');
            $user = Gamers::where('email', $exEmail)->first();

            $this->UserBalance = number_format(($user?->balanceFloat ?? 0), 2, '.', '');

            $this->paypalUpdateCard = "block";
            $this->paypalnewCard = "none";
            $this->Uemail = $exEmail;
            $this->withdrawShow = "";
        }
    }

    public function render()
    {

        $NewEmail = Session::get('email');

        $UserId = Gamers::where('email', $NewEmail)->value('id');

        $walletId = Wallet::where('holder_id', $UserId)->value('id');

        // $status = Transactions::pluck('status');


        // dd($status);

        if ($this->search == "") {

            return view('livewire.game-tester', ['historys' => Transaction::where('wallet_id', $walletId)
                ->select('uuid', 'type', 'amount', 'updated_at', 'status', 'game_name', 'event_name')
                ->orderByDesc('updated_at')
                ->paginate(30)]);
        }
        return view('livewire.game-tester', ['historys' => Transaction::where('wallet_id', $walletId)
            ->where('type', "like", "%" . $this->search . "%")
            ->orWhere('uuid', "like", "%" . $this->search . "%")
            ->orWhere('amount', "like", "%" . $this->search . "%")
            ->orWhere('updated_at', "like", "%" . $this->search . "%")
            ->orWhere('status', "like", "%" . $this->search . "%")
            ->select('uuid', 'type', 'amount', 'updated_at', 'status', 'game_name', 'event_name')
            ->orderByDesc('updated_at')
            ->paginate(30)]);
    }
}
