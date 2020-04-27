<?php

namespace Letsgoi\DomainEventsMessaging\Consumer\Contracts;

use Letsgoi\DomainEventsMessaging\Messages\DomainEventsMessage;

interface ConsumerContract
{
    /**
     * Consume message from domain event exchange
     *
     * @return DomainEventsMessage|null DomainEventsMessage
     */
    public function consume(): ?DomainEventsMessage;
}
