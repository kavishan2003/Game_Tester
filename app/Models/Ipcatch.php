<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ipcatch extends Model
{
      protected $fillable = [
        'id',
        'ip_address',
        'user_agent',
    ];
}
