<?php

namespace Illuminate\Notifications\Tests\Feature;

use Orchestra\Testbench\Attributes\WithConfig;
use Vonage\Client;
use Vonage\Client\Credentials\Basic;

#[WithConfig('vonage.api_key', 'my_api_key')]
#[WithConfig('vonage.api_secret', 'my_secret')]
class ClientBasicAPICredentialsTest extends FeatureTestCase
{
    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('vonage.api_key', 'my_api_key');
        $app['config']->set('vonage.api_secret', 'my_secret');
    }

    public function testClientCreatedWithBasicAPICredentials()
    {
        $credentials = $this->app->make(Client::class)->getCredentials();

        $this->assertInstanceOf(Basic::class, $credentials);
        $this->assertEquals(['api_key' => 'my_api_key', 'api_secret' => 'my_secret'], $credentials->asArray());
    }
}
