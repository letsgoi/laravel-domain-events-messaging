<?php

namespace Letsgoi\DomainEventsMessaging\Consumer;

use Illuminate\Support\Manager;
use Letsgoi\DomainEventsMessaging\Consumer\Contracts\ConsumerContract;
use Letsgoi\DomainEventsMessaging\Consumer\Drivers\ConsumerNullDriver;
use Letsgoi\DomainEventsMessaging\Consumer\Drivers\ConsumerSqsDriver;

class ConsumerManager extends Manager
{
    public function createSqsDriver(): ConsumerContract
    {
        return new ConsumerSqsDriver();
    }

    public function createNullDriver(): ConsumerContract
    {
        return new ConsumerNullDriver();
    }

    public function getDefaultDriver(): string
    {
        return $this->container['config']['domain_events_messaging.consumer.default'] ?? 'null';
    }
}
