<?php

namespace Letsgoi\DomainEventsMessaging\Consumer\Console;

use Illuminate\Console\Command;
use Letsgoi\DomainEventsMessaging\Consumer\Consumer;

class ConsumeCommand extends Command
{
    /** @var string */
    protected $signature = 'domain-events-messaging:consume
                            {--once : Only consume the next message on the queue}
                            {--force : Force the consumer to run even in maintenance mode}
                            {--sleep=3 : Number of seconds to sleep when no message is available}';

    /** @var string */
    protected $description = 'Consume domain messages from queue';

    /** @var Consumer */
    private $domainEventConsumer;

    public function __construct(Consumer $domainEventConsumer)
    {
        parent::__construct();

        $this->domainEventConsumer = $domainEventConsumer;
    }

    public function handle(): void
    {
        if ($this->downForMaintenance()) {
            $this->error('The application is in maintenance mode');

            return;
        }

        if ($this->option('once')) {
            $this->domainEventConsumer->consume();

            $this->info('Event consumer runned!');

            return;
        }

        $this->info('Running event consumer...');
        $this->domainEventConsumer->daemon($this->option('sleep'));
    }

    private function downForMaintenance(): bool
    {
        return $this->option('force') ? false : $this->laravel->isDownForMaintenance();
    }
}
