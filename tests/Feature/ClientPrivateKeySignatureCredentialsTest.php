<?php

namespace Illuminate\Notifications\Tests\Feature;

use Orchestra\Testbench\Attributes\WithConfig;
use Vonage\Client;
use Vonage\Client\Credentials\Container;
use Vonage\Client\Credentials\Keypair;
use Vonage\Client\Credentials\SignatureSecret;

#[WithConfig('vonage.api_key', 'my_api_key')]
#[WithConfig('vonage.signature_secret', 'my_signature')]
#[WithConfig('vonage.private_key', __DIR__.'/../fixtures/private.key')]
#[WithConfig('vonage.application_id', 'application-id-123')]
class ClientPrivateKeySignatureCredentialsTest extends FeatureTestCase
{
    public function testClientCreatedWithPrivateKeySignatureCredentials()
    {
        $credentials = $this->app->make(Client::class)->getCredentials();

        $keypairCredentials = $credentials->asArray()[Keypair::class]->asArray();
        $signatureCredentials = $credentials->asArray()[SignatureSecret::class]->asArray();

        $this->assertInstanceOf(Container::class, $credentials);
        $this->assertEquals(['key' => '===FAKE-KEY===', 'application' => 'application-id-123'], $keypairCredentials);
        $this->assertEquals([
            'api_key' => 'my_api_key',
            'signature_secret' => 'my_signature',
            'signature_method' => 'md5hash',
        ], $signatureCredentials);
    }
}
