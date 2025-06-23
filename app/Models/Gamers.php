<?php

namespace App\Models;

use Bavix\Wallet\Traits\HasWallet;
use Bavix\Wallet\Interfaces\Wallet;
use Bavix\Wallet\Traits\HasWalletFloat;
use Illuminate\Database\Eloquent\Model;
use Bavix\Wallet\Interfaces\WalletFloat;

class Gamers extends Model implements Wallet
{
   use HasWalletFloat;
    protected $fillable = [
        'id',
        'email',
        'Uname',
    ];
}
