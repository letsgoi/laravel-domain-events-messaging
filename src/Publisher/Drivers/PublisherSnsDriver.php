<?php

namespace Letsgoi\DomainEventsMessaging\Publisher\Drivers;

use Aws\Sdk;
use Aws\Sns\SnsClient;
use Illuminate\Support\Facades\Config;
use Letsgoi\DomainEventsMessaging\Publisher\Contracts\PublisherContract;

class PublisherSnsDriver implements PublisherContract
{
    /** @var SnsClient */
    private $awsSnsClient;

    /** @var string */
    private $topicArn;

    public function __construct(?SnsClient $awsSnsClient = null)
    {
        $this->awsSnsClient = $awsSnsClient ?: $this->getAwsSnsClient();
        $this->topicArn = Config::get('domain_events_messaging.publisher.connections.sns.topic_arn');
    }

    public function publish(string $event, string $message): void
    {
        $this->awsSnsClient->publish([
            'TopicArn' => $this->topicArn,
            'Message' => $message,
            'Subject' => $event,
            'MessageAttributes' => [
                'event_type' => [
                    'DataType' => 'String',
                    'StringValue' => $event,
                ],
            ],
        ]);
    }

    private function getAwsSnsClient(): SnsClient
    {
        $config = Config::get('domain_events_messaging.publisher.connections.sns');

        return (new Sdk($config))->createClient('sns');
    }
}
