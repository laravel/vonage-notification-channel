<?php

namespace Illuminate\Tests\Notifications;

use Vonage\Client;
use Vonage\Client\Credentials\Keypair;

class ClientPrivateKeyCredentialsTest extends AbstractTestCase
{
    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('vonage.private_key', '/path/to/key');
        $app['config']->set('vonage.application_id', 'application-id-123');
    }

    public function testClientCreatedWithPrivateKeyCredentials()
    {
        $client = app(Client::class);

        $credentialsObject = $this->getClassProperty(Client::class, 'credentials', $client);
        $credentialsArray = $this->getClassProperty(Keypair::class, 'credentials', $credentialsObject);

        $this->assertInstanceOf(Keypair::class, $credentialsObject);
        $this->assertEquals(['key' => '===FAKE-KEY===', 'application' => 'application-id-123'], $credentialsArray);
    }
}
