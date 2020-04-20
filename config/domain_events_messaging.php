<?php

return [
    'consumer' => [
        /*
        |--------------------------------------------------------------------------
        | Default Domain Event Consumer Connection Name (sqs|null)
        |--------------------------------------------------------------------------
        |
        */

        'default' => env('DOMAIN_EVENTS_CONSUMER_CONNECTION', 'null'),

        /*
        |--------------------------------------------------------------------------
        | Domain Event Consumer Connections
        |--------------------------------------------------------------------------
        |
        */

        'connections' => [
            'sqs' => [
                'queue_url' => env('AWS_DOMAIN_EVENTS_SQS_URL'),
                'key' => env('AWS_ACCESS_KEY_ID'),
                'secret' => env('AWS_SECRET_ACCESS_KEY'),
                'queue' => env('AWS_SQS_URL'),
                'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
            ],
        ],


        /*
        |--------------------------------------------------------------------------
        | Domain Event Consumer Events
        |
        | Set events/jobs to receive messages on events.
        |--------------------------------------------------------------------------
        |
        */

        'events' => [
//            'application.event.subject' => EventJob::class,
        ],
    ],
];
