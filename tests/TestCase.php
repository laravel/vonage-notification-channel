<?php

namespace Illuminate\Tests\Notifications;

use Mockery as m;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase as BaseTestCase;

class TestCase extends BaseTestCase
{
    use MockeryPHPUnitIntegration;

    protected function tearDown(): void
    {
        m::close();
    }
}
