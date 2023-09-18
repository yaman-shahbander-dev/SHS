<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class ChatifyMessenger extends Facade
{

    protected static function getFacadeAccessor()
    {
        return 'ChatifyMessenger';
    }
}