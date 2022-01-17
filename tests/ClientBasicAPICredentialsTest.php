<?php

namespace Illuminate\Tests\Notifications;

use Vonage\Client;
use Vonage\Client\Credentials\Basic;

class ClientBasicAPICredentialsTest extends AbstractTestCase
{
    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('vonage.api_key', 'my_api_key');
        $app['config']->set('vonage.api_secret', 'my_secret');
    }

    public function testClientCreatedWithBasicAPICredentials()
    {
        $client = app(Client::class);
        $credentialsObject = $this->getClassProperty(Client::class, 'credentials', $client);
        $credentialsArray = $this->getClassProperty(Basic::class, 'credentials', $credentialsObject);

        $this->assertInstanceOf(Basic::class, $credentialsObject);
        $this->assertEquals(['api_key' => 'my_api_key', 'api_secret' => 'my_secret'], $credentialsArray);
    }
}
