<?php

namespace Illuminate\Tests\Notifications;

use Vonage\Client;
use Vonage\Client\Credentials\Basic;
use Vonage\Client\Credentials\Container;
use Vonage\Client\Credentials\Keypair;

class ClientPrivateKeyBasicCredentialsTest extends AbstractTestCase
{
    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('vonage.private_key', '/path/to/key');
        $app['config']->set('vonage.application_id', 'application-id-123');
        $app['config']->set('vonage.api_key', 'my_api_key');
        $app['config']->set('vonage.api_secret', 'my_secret');
    }

    public function testClientCreatedWithPrivateKeyBasicCredentials()
    {
        $client = app(Client::class);

        $credentialsObject = $this->getClassProperty(Client::class, 'credentials', $client);
        $credentialsArray = $this->getClassProperty(Container::class, 'credentials', $credentialsObject);
        $keypairCredentials = $this->getClassProperty(Keypair::class, 'credentials', $credentialsArray[Keypair::class]);
        $basicCredentials = $this->getClassProperty(Basic::class, 'credentials', $credentialsArray[Basic::class]);

        $this->assertInstanceOf(Container::class, $credentialsObject);
        $this->assertEquals(['key' => '===FAKE-KEY===', 'application' => 'application-id-123'], $keypairCredentials);
        $this->assertEquals(['api_key' => 'my_api_key', 'api_secret' => 'my_secret'], $basicCredentials);
    }
}
