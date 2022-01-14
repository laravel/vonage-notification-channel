<?php

namespace Nexmo\Laravel\Tests;

use Vonage\Client;

class TestClientBasicAPICredentials extends AbstractTestCase
{
    /**
     * Define environment setup.
     *
     * @param  \Illuminate\Foundation\Application $app
     *
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('nexmo.api_key', 'my_api_key');
        $app['config']->set('nexmo.api_secret', 'my_secret');
    }

    /**
     * Test that our Nexmo client is created with
     * the Basic API credentials.
     *
     * @return void
     */
    public function testClientCreatedWithBasicAPICredentials()
    {
        $client = app(Client::class);
        $credentialsObject = $this->getClassProperty(Client::class, 'credentials', $client);
        $credentialsArray = $this->getClassProperty(Client\Credentials\Basic::class, 'credentials', $credentialsObject);

        $this->assertInstanceOf(Client\Credentials\Basic::class, $credentialsObject);
        $this->assertEquals(['api_key' => 'my_api_key', 'api_secret' => 'my_secret'], $credentialsArray);
    }
}
