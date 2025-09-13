<?php

namespace AreiaLab\LoginNotifier\Models;

use Illuminate\Database\Eloquent\Model;

class LoginHistory extends Model
{
    protected $fillable = [
        'user_id', 'ip_address', 'device', 'platform', 'browser', 'geo_location'
    ];
}
