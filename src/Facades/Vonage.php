<?php

namespace Illuminate\Notifications\Facades;

use Vonage\Client;
use Illuminate\Support\Facades\Facade;

class Nexmo extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return Client::class;
    }
}
