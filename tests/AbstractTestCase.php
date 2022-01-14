<?php

namespace Nexmo\Laravel\Tests;

use Orchestra\Testbench\TestCase;
use Nexmo\Laravel\NexmoServiceProvider;
use Vonage\Client;
use Nexmo\Client as NexmoClient;

abstract class AbstractTestCase extends TestCase
{
    /**
     * Get package providers.
     *
     * @param  \Illuminate\Foundation\Application $app
     *
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [
            NexmoServiceProvider::class,
        ];
    }

    /**
     * Get package aliases.
     *
     * @param  \Illuminate\Foundation\Application $app
     *
     * @return array
     */
    protected function getPackageAliases($app)
    {
        return [
            'Nexmo' => \Nexmo\Laravel\Facade\Nexmo::class,
        ];
    }

    /**
     * Gets the property of an object of a class.
     *
     * @param string $class
     * @param string $property
     * @param mixed  $object
     *
     * @return mixed
     */
    public function getClassProperty($class, $property, $object)
    {
        $reflectionClass = new \ReflectionClass($class);
        $refProperty = $reflectionClass->getProperty($property);
        $refProperty->setAccessible(true);

        return $refProperty->getValue($object);
    }

    /**
     * Returns a list of classes we should attempt to create
     */
    public function classNameProvider(): array
    {
        return [
            [Client::class],
            [NexmoClient::class],
        ];
    }
}
