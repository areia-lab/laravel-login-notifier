<?php

namespace AreiaLab\LoginNotifier\Facades;

use Illuminate\Support\Facades\Facade;

class LoginHistory extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'login-history-service';
    }
}
