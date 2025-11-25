<?php

namespace Illuminate\Notifications\Tests\Feature;

use Orchestra\Testbench\Attributes\WithConfig;
use Vonage\Client;
use Vonage\Client\Credentials\Keypair;

#[WithConfig('vonage.private_key', __DIR__.'/../fixtures/private.key')]
#[WithConfig('vonage.application_id', 'application-id-123')]
class ClientPrivateKeyCredentialsTest extends FeatureTestCase
{
    public function testClientCreatedWithPrivateKeyCredentials()
    {
        $credentials = $this->app->make(Client::class)->getCredentials();

        $this->assertInstanceOf(Keypair::class, $credentials);
        $this->assertEquals(['key' => '===FAKE-KEY===', 'application' => 'application-id-123'], $credentials->asArray());
    }
}
