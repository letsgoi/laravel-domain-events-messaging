<?php

namespace Letsgoi\DomainEventsMessaging\Publisher;

use Illuminate\Support\Manager;
use Letsgoi\DomainEventsMessaging\Publisher\Contracts\PublisherContract;
use Letsgoi\DomainEventsMessaging\Publisher\Drivers\PublisherNullDriver;
use Letsgoi\DomainEventsMessaging\Publisher\Drivers\PublisherSnsDriver;

class PublisherManager extends Manager
{
    public function createSnsDriver(): PublisherContract
    {
        return new PublisherSnsDriver();
    }

    public function createNullDriver(): PublisherContract
    {
        return new PublisherNullDriver();
    }

    public function getDefaultDriver(): string
    {
        return $this->container['config']['domain_events_messaging.publisher.default'] ?? 'null';
    }
}
