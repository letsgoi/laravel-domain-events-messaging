<?php

namespace Letsgoi\DomainEventsMessaging\Tests\Consumer;

use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Event;
use Letsgoi\DomainEventsMessaging\Consumer\Consumer;
use Letsgoi\DomainEventsMessaging\Exceptions\DomainEventsMessagingException;
use Letsgoi\DomainEventsMessaging\Facades\DomainEventsMessagingConsumer;
use Letsgoi\DomainEventsMessaging\Messages\DomainEventsMessage;
use Letsgoi\DomainEventsMessaging\Tests\Stubs\FakeEvent;
use Letsgoi\DomainEventsMessaging\Tests\Stubs\FakeJob;
use Letsgoi\DomainEventsMessaging\Tests\Stubs\FakeNotDispatchableEvent;
use Letsgoi\DomainEventsMessaging\Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class ConsumerTest extends TestCase
{
    #[Test]
    public function it_should_dispatch_event_on_consume_domain_event_message()
    {
        Event::fake();

        $domainEventMessage = new DomainEventsMessage('message.subject', 'domain-message');
        Config::set('domain_events_messaging.consumer.events', [
            'message.subject' => FakeEvent::class,
        ]);

        DomainEventsMessagingConsumer::shouldReceive('consume')
            ->once()
            ->andReturn($domainEventMessage);

        $consumer = app(Consumer::class);
        $this->assertSame($domainEventMessage, $consumer->consume());

        Event::assertDispatched(FakeEvent::class, static function (FakeEvent $event) {
            return $event->payload === 'domain-message';
        });
    }

    #[Test]
    public function it_should_dispatch_job_on_consume_domain_event_message()
    {
        Bus::fake();

        $domainEventMessage = new DomainEventsMessage('message.subject', 'domain-message');
        Config::set('domain_events_messaging.consumer.events', [
            'message.subject' => FakeJob::class,
        ]);

        DomainEventsMessagingConsumer::shouldReceive('consume')
            ->once()
            ->andReturn($domainEventMessage);

        $consumer = app(Consumer::class);
        $this->assertSame($domainEventMessage, $consumer->consume());

        Bus::assertDispatched(FakeJob::class, static function (FakeJob $job) {
            return $job->payload === 'domain-message';
        });
    }

    #[Test]
    public function it_should_throw_exception_on_consume_domain_event_message_with_not_dispatchable_event_associated()
    {
        $domainEventMessage = new DomainEventsMessage('message.subject', 'domain-message');
        Config::set('domain_events_messaging.consumer.events', [
            'message.subject' => FakeNotDispatchableEvent::class,
        ]);

        DomainEventsMessagingConsumer::shouldReceive('consume')
            ->once()
            ->andReturn($domainEventMessage);

        $this->expectException(DomainEventsMessagingException::class);

        $consumer = app(Consumer::class);
        $this->assertSame($domainEventMessage, $consumer->consume());
    }
}
