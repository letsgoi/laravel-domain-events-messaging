<?php

namespace Letsgoi\DomainEventsMessaging\Publisher\Contracts;

interface PublisherContract
{
    /**
     * Publish message to domain event exchange
     *
     * @param string $event
     * @param string $message
     */
    public function publish(string $event, string $message): void;
}
