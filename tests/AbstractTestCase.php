<?php

namespace Illuminate\Tests\Notifications;

use Illuminate\Notifications\VonageChannelServiceProvider;
use Orchestra\Testbench\TestCase;
use ReflectionClass;

abstract class AbstractTestCase extends TestCase
{
    /**
     * Get package providers.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [VonageChannelServiceProvider::class];
    }

    /**
     * Gets the property of an object of a class.
     *
     * @param  string  $class
     * @param  string  $property
     * @param  mixed  $object
     * @return mixed
     */
    public function getClassProperty($class, $property, $object)
    {
        $reflectionClass = new ReflectionClass($class);
        $refProperty = $reflectionClass->getProperty($property);
        $refProperty->setAccessible(true);

        return $refProperty->getValue($object);
    }
}
