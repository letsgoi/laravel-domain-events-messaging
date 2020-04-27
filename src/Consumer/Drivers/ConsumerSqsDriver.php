<?php

namespace Letsgoi\DomainEventsMessaging\Consumer\Drivers;

use Aws\Sdk;
use Aws\Sqs\SqsClient;
use Illuminate\Support\Facades\Config;
use Letsgoi\DomainEventsMessaging\Consumer\Contracts\ConsumerContract;
use Letsgoi\DomainEventsMessaging\Messages\DomainEventsMessage;

class ConsumerSqsDriver implements ConsumerContract
{
    private const MAX_NUMBER_OF_MESSAGES = 1;

    /** @var SqsClient */
    private $awsSqsClient;

    /** @var string */
    private $queueUrl;

    public function __construct(?SqsClient $awsSqsClient = null)
    {
        $this->awsSqsClient = $awsSqsClient ?: $this->getAwsSqsClient();
        $this->queueUrl = Config::get('domain_events_messaging.consumer.connections.sqs.queue_url');
    }

    public function consume(): ?DomainEventsMessage
    {
        $message = $this->receiveMessage();

        if ($message === null) {
            return null;
        }

        $this->deleteMessage($message['ReceiptHandle']);

        $messageBody = json_decode($message['Body'], true);

        return new DomainEventsMessage($messageBody['Subject'], $messageBody['Message']);
    }

    private function getAwsSqsClient(): SqsClient
    {
        $config = array_merge(
            Config::get('domain_events_messaging.consumer.connections.sqs'),
            [
                'version' => 'latest',
                'http' => [
                    'timeout' => 60,
                    'connect_timeout' => 60,
                ],
            ]
        );

        return (new Sdk($config))->createClient('sqs');
    }

    private function receiveMessage(): ?array
    {
        $response = $this->awsSqsClient->receiveMessage([
            'QueueUrl' => $this->queueUrl,
            'MaxNumberOfMessages' => self::MAX_NUMBER_OF_MESSAGES,
        ]);

        if (!is_array($response['Messages']) || count($response['Messages']) === 0) {
            return null;
        }

        return $response['Messages'][0];
    }

    private function deleteMessage(string $receiptHandle): void
    {
        $this->awsSqsClient->deleteMessage([
            'QueueUrl' => $this->queueUrl,
            'ReceiptHandle' => $receiptHandle,
        ]);
    }
}
