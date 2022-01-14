<?php

namespace Nexmo\Laravel\Tests;

use Vonage\Client;

class TestClientPrivateKeyBasicCredentials extends AbstractTestCase
{
    /**
     * Define environment setup.
     *
     * @param  \Illuminate\Foundation\Application $app
     *
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('nexmo.private_key', '/path/to/key');
        $app['config']->set('nexmo.application_id', 'application-id-123');
        $app['config']->set('nexmo.api_key', 'my_api_key');
        $app['config']->set('nexmo.api_secret', 'my_secret');
    }

    /**
     * Test that our Nexmo client is created with
     * a container with key + basic credentials.
     *
     * @dataProvider classNameProvider
     *
     * @return void
     */
    public function testClientCreatedWithPrivateKeyBasicCredentials($className)
    {
        $client = app($className);
        $credentialsObject = $this->getClassProperty(Client::class, 'credentials', $client);
        $credentialsArray = $this->getClassProperty(Client\Credentials\Container::class, 'credentials', $credentialsObject);
        $keypairCredentials = $this->getClassProperty(Client\Credentials\Keypair::class, 'credentials', $credentialsArray[Client\Credentials\Keypair::class]);
        $basicCredentials = $this->getClassProperty(Client\Credentials\Basic::class, 'credentials', $credentialsArray[Client\Credentials\Basic::class]);

        $this->assertInstanceOf(Client\Credentials\Container::class, $credentialsObject);
        $this->assertEquals(['key' => '===FAKE-KEY===', 'application' => 'application-id-123'], $keypairCredentials);
        $this->assertEquals(['api_key' => 'my_api_key', 'api_secret' => 'my_secret'], $basicCredentials);
    }
}
