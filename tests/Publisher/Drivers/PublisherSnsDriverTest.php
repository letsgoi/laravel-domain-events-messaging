<?php

namespace Letsgoi\DomainEventsMessaging\Tests\Publisher\Drivers;

use Aws\Sns\SnsClient;
use Illuminate\Support\Facades\Config;
use Letsgoi\DomainEventsMessaging\Publisher\Drivers\PublisherSnsDriver;
use Letsgoi\DomainEventsMessaging\Tests\TestCase;
use Mockery\MockInterface;
use PHPUnit\Framework\Attributes\Test;

class PublisherSnsDriverTest extends TestCase
{
    #[Test]
    public function it_should_send_message_on_sns_on_publish()
    {
        Config::set('domain_events_messaging.publisher.connections.sns.topic_arn', 'sns_topic_arn');

        $snsClient = $this->mock(SnsClient::class, static function (MockInterface $mock) {
            $mock->expects('publish')
                ->withArgs([[
                    'TopicArn' => 'sns_topic_arn',
                    'Message' => 'message',
                    'Subject' => 'event',
                    'MessageAttributes' => [
                        'event_type' => [
                            'DataType' => 'String',
                            'StringValue' => 'event',
                        ],
                    ],
                ]]);
        });

        $publisherSnsDriver = new PublisherSnsDriver($snsClient);
        $publisherSnsDriver->publish('event', 'message');
    }
}
