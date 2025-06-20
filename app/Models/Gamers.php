<?php

namespace App\Models;

use Bavix\Wallet\Traits\HasWallet;
use Bavix\Wallet\Interfaces\Wallet;
use Illuminate\Database\Eloquent\Model;

class Gamers extends Model implements Wallet
{
    use HasWallet;
    protected $fillable = [
        'id',
        'email',
        'Uname',
    ];
}
