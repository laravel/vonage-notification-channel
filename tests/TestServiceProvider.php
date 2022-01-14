<?php

namespace Nexmo\Laravel\Tests;

use Vonage\Client;

class TestServiceProvider extends AbstractTestCase
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
        $app['config']->set('nexmo.api_key', 'my_api_key');
        $app['config']->set('nexmo.api_secret', 'my_secret');
    }

    /**
     * Test that we can create the Nexmo client
     * from container binding.
     *
     * @dataProvider classNameProvider
     *
     * @return void
     */
    public function testClientResolutionFromContainer($className)
    {
        $client = app($className);

        $this->assertInstanceOf($className, $client);
    }
}
