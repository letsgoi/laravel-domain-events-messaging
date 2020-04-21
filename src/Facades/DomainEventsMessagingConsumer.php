<?php

namespace Letsgoi\DomainEventsMessaging\Facades;

use Illuminate\Support\Facades\Facade;

class DomainEventsMessagingConsumer extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'domain-events-messaging-consumer';
    }
}
