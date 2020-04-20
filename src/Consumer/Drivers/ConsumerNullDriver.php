<?php

namespace Letsgoi\DomainEventsMessaging\Consumer\Drivers;


use Letsgoi\DomainEventsMessaging\Consumer\Contracts\ConsumerContract;
use Letsgoi\DomainEventsMessaging\Messages\DomainEventsMessage;

class ConsumerNullDriver implements ConsumerContract
{
    public function consume(): ?DomainEventsMessage
    {
        return null;
    }
}
