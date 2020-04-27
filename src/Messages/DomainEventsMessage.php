<?php

namespace Letsgoi\DomainEventsMessaging\Messages;

class DomainEventsMessage
{
    /** @var string */
    private $message;

    /** @var string */
    private $event;

    public function __construct(string $event, string $message)
    {
        $this->event = $event;
        $this->message = $message;
    }

    public function getEvent(): string
    {
        return $this->event;
    }

    public function getMessage(): string
    {
        return $this->message;
    }
}
