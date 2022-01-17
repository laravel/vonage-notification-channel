<?php

namespace Illuminate\Tests\Notifications;

use Vonage\Client;
use Vonage\Client\Credentials\SignatureSecret;

class ClientSignatureAPICredentialsTest extends AbstractTestCase
{
    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('vonage.api_key', 'my_api_key');
        $app['config']->set('vonage.signature_secret', 'my_signature');
    }

    public function testClientCreatedWithSignatureAPICredentials()
    {
        $client = app(Client::class);

        $credentialsObject = $this->getClassProperty(Client::class, 'credentials', $client);
        $credentialsArray = $this->getClassProperty(SignatureSecret::class, 'credentials', $credentialsObject);

        $this->assertInstanceOf(SignatureSecret::class, $credentialsObject);
        $this->assertEquals([
            'api_key' => 'my_api_key',
            'signature_secret' => 'my_signature',
            'signature_method' => 'md5hash',
        ], $credentialsArray);
    }
}
