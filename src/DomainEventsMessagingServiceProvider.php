<?php

namespace Letsgoi\DomainEventsMessaging;

use Illuminate\Support\ServiceProvider;
use Letsgoi\DomainEventsMessaging\Consumer\Console\ConsumeCommand;
use Letsgoi\DomainEventsMessaging\Consumer\ConsumerManager;

class DomainEventsMessagingServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->registerPublishes();
    }

    public function register(): void
    {
        $this->registerCommands();

        $this->app->bind('domain-events-messaging-consumer', function () {
            return new ConsumerManager($this->app);
        });

        $this->mergeConfigFrom(__DIR__ . '/../config/domain_events_messaging.php', 'domain-events-messaging');
    }

    private function registerPublishes(): void
    {
        $this->publishes([
            __DIR__ . '/../config/domain_events_messaging.php' => config_path('domain_events_messaging.php'),
        ], 'config');
    }

    private function registerCommands(): void
    {
        $this->commands([
            ConsumeCommand::class,
        ]);
    }
}
