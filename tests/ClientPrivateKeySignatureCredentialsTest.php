<?php

namespace Illuminate\Tests\Notifications;

use Vonage\Client;
use Vonage\Client\Credentials\Container;
use Vonage\Client\Credentials\Keypair;
use Vonage\Client\Credentials\SignatureSecret;

class ClientPrivateKeySignatureCredentialsTest extends AbstractTestCase
{
    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('vonage.private_key', '/path/to/key');
        $app['config']->set('vonage.application_id', 'application-id-123');
        $app['config']->set('vonage.api_key', 'my_api_key');
        $app['config']->set('vonage.signature_secret', 'my_signature');
    }

    public function testClientCreatedWithPrivateKeySignatureCredentials()
    {
        $client = app(Client::class);

        $credentialsObject = $this->getClassProperty(Client::class, 'credentials', $client);

        $credentialsArray = $this->getClassProperty(Container::class, 'credentials', $credentialsObject);
        $keypairCredentials = $this->getClassProperty(Keypair::class, 'credentials', $credentialsArray[Keypair::class]);
        $signatureCredentials = $this->getClassProperty(SignatureSecret::class, 'credentials', $credentialsArray[SignatureSecret::class]);

        $this->assertInstanceOf(Container::class, $credentialsObject);
        $this->assertEquals(['key' => '===FAKE-KEY===', 'application' => 'application-id-123'], $keypairCredentials);
        $this->assertEquals([
            'api_key' => 'my_api_key',
            'signature_secret' => 'my_signature',
            'signature_method' => 'md5hash',
        ], $signatureCredentials);
    }
}
