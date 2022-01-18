<?php

namespace Illuminate\Notifications\Tests\Feature;

use Vonage\Client;
use Vonage\Client\Credentials\Keypair;

class ClientPrivateKeyCredentialsTest extends FeatureTestCase
{
    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('vonage.private_key', __DIR__.'/../fixtures/private.key');
        $app['config']->set('vonage.application_id', 'application-id-123');
    }

    public function testClientCreatedWithPrivateKeyCredentials()
    {
        $credentials = $this->app->make(Client::class)->getCredentials();

        $this->assertInstanceOf(Keypair::class, $credentials);
        $this->assertEquals(['key' => '===FAKE-KEY===', 'application' => 'application-id-123'], $credentials->asArray());
    }
}
