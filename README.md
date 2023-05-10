# Laravel Domain Events Messaging

This package allows to send/consume domain message events from your laravel app. This can be used to communicate between services or in the same application via queues.

It's divided in two main elements:

- **Publisher**: To publish messages on queue.
- **Consumer**: Read messages from queue and execute jobs/events.

## Requirements

- PHP >= 8.1
- Laravel >= 9.0

## Instalation

- Require package with composer:

```bash
composer require letsgoi/laravel-domain-events-messaging
```

- Publish configuration:

```bash
php artisan vendor:publish --provider="Letsgoi\DomainEventsMessaging\DomainEventsMessagingServiceProvider" --tag="config"
```

Service Provider will be automatically registered, however if you want to add it manually, you must add this to your `config/app.php` file:

```php
'providers' => [
    // ...
    Letsgoi\DomainEventsMessaging\DomainEventsMessagingServiceProvider::class,
];
``` 

## Usage

### Publisher

Domain Events Messaging Publisher will publish messages to exchange defined on config file (on `DOMAIN_EVENTS_PUBLISHER_CONNECTION` env variable).

#### Publishing messages

Just use `publish` method on `DomainEventsMessagingPublisher` facade to send messages with event type:

```php
DomainEventsMessagingPublisher::publish('message.subject', 'message content');
```

### Consumer

Domain Events Messaging Consumer will consume messages from the queue driver defined on config file (on `DOMAIN_EVENTS_CONSUMER_CONNECTION` env variable) and launch jobs/events setted on config file (`config/domain_events_messaging.php`) by message subject.

#### Defining events:

On config file you must set the messages to be consumed and the job/event to be dispatched when this message will be consumed:

```php
'consumer' => [
    // ...

    'events' => [
        'message.subject' => Event::class,
        'message.other' => Job::class,
        // ...
    ]
]
```

When consumer read some message with subject defined on this config, the job/event will be launch with the `array $payload` setted on his constructor.

#### Jobs/Events

The class defined on config file to be dispatched on message, could be:

- Laravel Job: It must use the `Illuminate\Foundation\Bus\Dispatchable` trait.
- Laravel Event: It must use the `Illuminate\Foundation\Events\Dispatchable` trait.

Both will be receive on his constructor message as string:

```php
public function __construct(string $message)
```

You can queue the jobs as a normal job on Laravel.

#### Running consumer:

To run the consumer as a daemon you must execute:

```bash
php artisan domain-events:consume
```

This task should be monitored with some like supervisor to be running on fails.

## Drivers

### AWS SNS-SQS

To use AWS SNS or AWS SQS driver, you must follow this instructions:

- Add (and fill) this variables on your .env file:

```
# AWS IAM
AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
AWS_DEFAULT_REGION=

# SNS
AWS_DOMAIN_EVENTS_SNS_TOPIC_ARN=

# SQS
AWS_SQS_DOMAIN_EVENTS_URL=
```

## Testing

Run tests:


```bash
composer test
```

## Contributing
Pull requests are welcome. For major changes, please open an issue first to discuss what you would like to change.

Please make sure to update tests as appropriate.

## License
[MIT](./LICENSE.md)
