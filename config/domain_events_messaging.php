<?php

return [
    'publisher' => [
        /*
        |--------------------------------------------------------------------------
        | Default Domain Event Publisher Connection Name (sns|null)
        |--------------------------------------------------------------------------
        |
        */

        'default' => env('DOMAIN_EVENTS_PUBLISHER_CONNECTION', 'null'),

        /*
        |--------------------------------------------------------------------------
        | Domain Event Publisher Connections
        |--------------------------------------------------------------------------
        |
        */

        'connections' => [
            'sns' => [
                'topic_arn' => env('AWS_DOMAIN_EVENTS_SNS_TOPIC_ARN'),
                'key' => env('AWS_ACCESS_KEY_ID'),
                'secret' => env('AWS_SECRET_ACCESS_KEY'),
                'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
                'version' => 'latest',
            ],
        ],
    ],

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
