<?php

namespace Illuminate\Notifications\Tests\Feature;

use Vonage\Client;
use Vonage\Client\Credentials\SignatureSecret;

class ClientSignatureAPICredentialsTest extends FeatureTestCase
{
    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('vonage.api_key', 'my_api_key');
        $app['config']->set('vonage.signature_secret', 'my_signature');
    }

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
