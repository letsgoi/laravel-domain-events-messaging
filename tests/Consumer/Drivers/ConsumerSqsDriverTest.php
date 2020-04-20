<?php

namespace Letsgoi\DomainEventsMessaging\Tests\Consumer\Drivers;

use Aws\Sqs\SqsClient;
use Illuminate\Support\Facades\Config;
use Letsgoi\DomainEventsMessaging\Consumer\Drivers\ConsumerSqsDriver;
use Letsgoi\DomainEventsMessaging\Tests\TestCase;
use Mockery\MockInterface;

class ConsumerSqsDriverTest extends TestCase
{
    /** @test */
    public function it_should_receive_message_and_delete_it_from_sqs_on_consume()
    {
        Config::set('domain_events_messaging.consumer.connections.sqs.queue_url', 'queue_url');

        $sqsClient = $this->mock(SqsClient::class, static function (MockInterface $mock) {
            $mock->shouldReceive('receiveMessage')
                ->withArgs([[
                    'QueueUrl' => 'queue_url',
                    'MaxNumberOfMessages' => 1,
                ]])
                ->once()
                ->andReturn([
                    'Messages' => [
                        [
                            'ReceiptHandle' => 'message-id',
                            'Body' => json_encode([
                                'Subject' => 'message.subject',
                                'Message' => 'domain-message'
                            ]),
                        ],
                    ],
                ]);

            $mock->shouldReceive('deleteMessage')
                ->withArgs([[
                    'QueueUrl' => 'queue_url',
                    'ReceiptHandle' => 'message-id',
                ]])
                ->once();
        });

        $consumerSqsDriver = new ConsumerSqsDriver($sqsClient);
        $domainEventMessage = $consumerSqsDriver->consume();

        $this->assertSame('message.subject', $domainEventMessage->getEvent());
        $this->assertSame('domain-message', $domainEventMessage->getMessage());
    }
}
