<?php

namespace Illuminate\Notifications\Tests\Feature;

use Orchestra\Testbench\Attributes\WithConfig;
use Vonage\Client;
use Vonage\Client\Credentials\Basic;
use Vonage\Client\Credentials\Container;
use Vonage\Client\Credentials\Keypair;

#[WithConfig('vonage.api_key', 'my_api_key')]
#[WithConfig('vonage.api_secret', 'my_secret')]
#[WithConfig('vonage.private_key', __DIR__.'/../fixtures/private.key')]
#[WithConfig('vonage.application_id', 'application-id-123')]
class ClientPrivateKeyBasicCredentialsTest extends FeatureTestCase
{
    public function testClientCreatedWithPrivateKeyBasicCredentials()
    {
        $credentials = $this->app->make(Client::class)->getCredentials();

        $keypairCredentials = $credentials->asArray()[Keypair::class]->asArray();
        $basicCredentials = $credentials->asArray()[Basic::class]->asArray();

        $this->assertInstanceOf(Container::class, $credentials);
        $this->assertEquals(['key' => '===FAKE-KEY===', 'application' => 'application-id-123'], $keypairCredentials);
        $this->assertEquals(['api_key' => 'my_api_key', 'api_secret' => 'my_secret'], $basicCredentials);
    }
}
