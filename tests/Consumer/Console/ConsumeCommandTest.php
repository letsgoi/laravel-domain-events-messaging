<?php

namespace Letsgoi\DomainEventsMessaging\Tests\Consumer\Console;

use Letsgoi\DomainEventsMessaging\Consumer\Consumer;
use Letsgoi\DomainEventsMessaging\Tests\TestCase;
use Mockery\MockInterface;

class ConsumeCommandTest extends TestCase
{
    /** @test */
    public function it_should_launch_consumer_as_a_daemon_on_consume_command()
    {
        $this->mock(Consumer::class, static function (MockInterface $mock) {
            $mock->shouldReceive('daemon')
                ->once();
        });

        $this->artisan('domain-events-messaging:consume');
    }

    /** @test */
    public function it_should_launch_consumer_as_a_daemon_with_sleep_seconds_on_consume_command_with_sleep_parameter()
    {
        $this->mock(Consumer::class, static function (MockInterface $mock) {
            $mock->shouldReceive('daemon')
                ->with(5)
                ->once();
        });

        $this->artisan('domain-events-messaging:consume', ['--sleep' => 5]);
    }

    /** @test */
    public function it_should_launch_consumer_once_on_consume_command_with_once_parameter()
    {
        $this->mock(Consumer::class, static function (MockInterface $mock) {
            $mock->shouldReceive('consume')
                ->once();
        });

        $this->artisan('domain-events-messaging:consume', ['--once' => true]);
    }

    /** @test */
    public function it_should_not_launch_consumer_when_app_is_in_maintenance_on_consume_command()
    {
        $this->mock(Consumer::class, static function (MockInterface $mock) {
            $mock->shouldReceive('daemon')
                ->never();
        });

        $this->artisan('down');
        $this->artisan('domain-events-messaging:consume');
        $this->artisan('up');
    }

    /** @test */
    public function it_should_launch_consumer_when_app_is_in_maintenance_on_consume_command_with_force_parameter()
    {
        $this->mock(Consumer::class, static function (MockInterface $mock) {
            $mock->shouldReceive('daemon')
                ->once();
        });

        $this->artisan('domain-events-messaging:consume', ['--force' => true]);
    }
}
