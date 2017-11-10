<?php

/**
 * Amazon SQS Queue Client
 *
 * @author emi
 */

namespace Minds\Core\Queue\SQS;

use Aws\Common\Facade\Sqs;
use Aws\Sqs\Exception\SqsException;
use Minds\Core\Di\Di;
use Minds\Core\Queue\Interfaces\QueueClient;

use Aws\Sqs\SqsClient;

class Client implements QueueClient
{
    /** @var SqsClient $client */
    protected $client;

    protected $config;
    protected $queueName = 'default';
    protected $queueUrl;

    public function __construct($config = null)
    {
        $this->config = $config ?: Di::_()->get('Config');

        $this->setUp();
    }

    protected function setUp()
    {
        $awsConfig = $this->config->get('aws');

        $this->client = SqsClient::factory([
            'key' => $awsConfig['key'],
            'secret' => $awsConfig['secret'],
            'region' => 'us-east-1'
        ]);

        // Reset exchange
        $this->setExchange();
    }

    public function setExchange($name = "default_exchange", $type = "direct")
    {
        // TODO: Implement setExchange() method with Standard queue / FIFO queue smart selection based on $name or $type.

        $awsConfig = $this->config->get('aws');

        $this->queueUrl = "https://sqs.{$awsConfig['region']}.amazonaws.com/{$awsConfig['account_id']}/";

        return $this;
    }

    public function setQueue($name = "default")
    {
        $this->queueName = $name;

        return $this;
    }

    public function send($message)
    {
        $body = [
            'queueName' => $this->queueName,
            'message' => base64_encode(serialize($message))
        ];

        try {
            $this->client->sendMessage([
                'QueueUrl' => $this->queueUrl . $this->queueName,
                'MessageBody' => json_encode($body)
            ]);
        } catch (SqsException $e) {
            error_log('[SQS Queue] ' . get_class($e) . ': ' . $e->getMessage());
        }

        // Reset exchange

        $this->setExchange();
    }

    public function receive($callback)
    {
        // TODO: Implement graceful exits, etc
        var_dump($this->queueUrl . $this->queueName);
        while (true) {
            $result = $this->client->receiveMessage([
                'QueueUrl' => $this->queueUrl . $this->queueName,
                'MaxNumberOfMessages' => 1
            ]);

            if (!$result || !$result->getPath('Messages/*/Body')) {
                echo 'no messages';
                continue;
            }

            foreach ($result->getPath('Messages/*/Body') as $messageBody) {
                $message = json_decode($messageBody, true);

                var_dump(unserialize(base64_decode($message['message'])));
            }

            foreach ($result->getPath('Messages/*/ReceiptHandle') as $asd) {
                $this->client->deleteMessage([
                    'QueueUrl' => $this->queueUrl . $this->queueName,
                    'ReceiptHandle' => $asd
                ]);
            }
        }
    }
}
