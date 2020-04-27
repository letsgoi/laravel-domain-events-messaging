<?php

namespace Letsgoi\DomainEventsMessaging\Consumer;

use Illuminate\Contracts\Bus\Dispatcher as BusDispatcher;
use Illuminate\Contracts\Events\Dispatcher as EventDispatcher;
use Illuminate\Foundation\Bus\Dispatchable as JobDispatchable;
use Illuminate\Foundation\Events\Dispatchable as EventDispatchable;
use Illuminate\Support\Facades\Config;
use Letsgoi\DomainEventsMessaging\Exceptions\DomainEventsMessagingException;
use Letsgoi\DomainEventsMessaging\Facades\DomainEventsMessagingConsumer;
use Letsgoi\DomainEventsMessaging\Messages\DomainEventsMessage;

class Consumer
{
    /** @var BusDispatcher */
    private $busDispatcher;

    /** @var EventDispatcher */
    private $eventDispatcher;

    public function __construct(BusDispatcher $busDispatcher, EventDispatcher $eventDispatcher)
    {
        $this->busDispatcher = $busDispatcher;
        $this->eventDispatcher = $eventDispatcher;
    }

    public function daemon(int $sleepSeconds = 3): void
    {
        while (true) {
            $event = $this->consume();

            if ($event === null) {
                sleep($sleepSeconds);
            }
        }
    }

    public function consume(): ?DomainEventsMessage
    {
        $event = DomainEventsMessagingConsumer::consume();

        if ($event !== null) {
            $this->processDomainEvent($event);
        }

        return $event;
    }

    private function processDomainEvent(DomainEventsMessage $event): void
    {
        $domainEvents = Config::get('domain_events_messaging.consumer.events');

        if (array_key_exists($event->getEvent(), $domainEvents)) {
            $this->dispatch($domainEvents[$event->getEvent()], $event);
        }
    }

    private function dispatch(string $class, DomainEventsMessage $domainEvent): void
    {
        if (!$this->isDispatchable($class)) {
            throw new DomainEventsMessagingException("{$class} is not a job or event");
        }

        $dispatchable = new $class($domainEvent->getMessage());

        if ($this->isJob($class)) {
            $this->busDispatcher->dispatch($dispatchable);
        }

        if ($this->isEvent($class)) {
            $this->eventDispatcher->dispatch($dispatchable);
        }
    }

    private function isDispatchable(string $class): bool
    {
        return $this->isJob($class) || $this->isEvent($class);
    }

    private function isJob(string $class): bool
    {
        return in_array(JobDispatchable::class, class_uses($class));
    }

    private function isEvent(string $class): bool
    {
        return in_array(EventDispatchable::class, class_uses($class));
    }
}
