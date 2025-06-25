<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bitlabs_callback extends Model
{
    protected $fillable = [
        'user_id',
        'transaction_id',
        'ip',
        'offer_value',
        'offer_name',
        'status',
    ];
}
