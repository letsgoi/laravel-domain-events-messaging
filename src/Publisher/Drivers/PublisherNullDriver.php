<?php

namespace Letsgoi\DomainEventsMessaging\Publisher\Drivers;

use Letsgoi\DomainEventsMessaging\Publisher\Contracts\PublisherContract;

class PublisherNullDriver implements PublisherContract
{
    public function publish(string $event, string $message): void
    {
        //
    }
}
