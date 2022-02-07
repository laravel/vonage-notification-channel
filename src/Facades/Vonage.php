<?php

namespace Illuminate\Notifications\Facades;

use Illuminate\Support\Facades\Facade;
use Vonage\Client;

class Vonage extends Facade
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
