<?php

namespace Illuminate\Notifications\Tests\Feature;

use Orchestra\Testbench\Attributes\WithConfig;
use Vonage\Client;
use Vonage\Client\Credentials\SignatureSecret;

#[WithConfig('vonage.api_key', 'my_api_key')]
#[WithConfig('vonage.signature_secret', 'my_signature')]
class ClientSignatureAPICredentialsTest extends FeatureTestCase
{
    public function testClientCreatedWithSignatureAPICredentials()
    {
        $credentials = $this->app->make(Client::class)->getCredentials();

        $this->assertInstanceOf(SignatureSecret::class, $credentials);
        $this->assertEquals([
            'api_key' => 'my_api_key',
            'signature_secret' => 'my_signature',
            'signature_method' => 'md5hash',
        ], $credentials->asArray());
    }
}
