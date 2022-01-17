<?php

namespace Illuminate\Tests\Notifications;

use RuntimeException;
use Vonage\Client;

class NoVonageConfigurationTest extends AbstractTestCase
{
    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('nexmo.api_key', 'my_api_key');
    }

    public function testWhenNoConfigurationIsGivenExceptionIsRaised()
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Please provide Vonage API credentials. Possible combinations: api_key + api_secret, api_key + signature_secret, private_key + application_id, api_key + api_secret + private_key + application_id, api_key + signature_secret + private_key + application_id');

        app(Client::class);
    }
}
