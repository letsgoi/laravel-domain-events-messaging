<?php

namespace Letsgoi\DomainEventsMessaging\Facades;

use Illuminate\Support\Facades\Facade;

class DomainEventsMessagingPublisher extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'domain-events-messaging-publisher';
    }
}
