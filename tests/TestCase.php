<?php

namespace Letsgoi\DomainEventsMessaging\Tests;

use Letsgoi\DomainEventsMessaging\DomainEventsMessagingServiceProvider;
use Orchestra\Testbench\TestCase as BaseTestClass;

abstract class TestCase extends BaseTestClass
{
    protected function getPackageProviders($app): array
    {
        return [
            DomainEventsMessagingServiceProvider::class,
        ];
    }
}
