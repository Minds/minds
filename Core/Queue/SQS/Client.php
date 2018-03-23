<?php

/**
 * Amazon SQS Queue Client
 *
 * @author emi
 */

namespace Minds\Core\Queue\SQS;

use Minds\Core\Di\Di;
use Minds\Core\Queue\Interfaces\QueueClient;
use Minds\Core\Queue\Message;

use Aws\Sqs\SqsClient;
use Aws\Sqs\Exception\SqsException;

class Client implements QueueClient
{
    /** @var SqsClient $client */
    protected $client;

    protected $config;
    protected $queueName = 'default';

    public function __construct($config = null, $sqsClientMock = null)
    {
        $this->config = $config ?: Di::_()->get('Config');
        $this->setUp($sqsClientMock);
    }

    protected function setUp($sqsClientMock = null)
    {
        $awsConfig = $this->config->get('aws');

        $opts = [
            'version' => '2012-11-05',
            'region' => $awsConfig['region'],
            'http' => [
                'scheme'  => 'http'
            ]
        ];

        if (!isset($awsConfig['useRoles']) || !$awsConfig['useRoles']) {
            $opts['credentials'] = [
                'key' => $awsConfig['key'],
                'secret' => $awsConfig['secret'],
            ];
        }

        $this->client = $sqsClientMock ?: new sqsClient($opts);

        // Reset exchange
        $this->setExchange();
    }

    public function setExchange($name = "default_exchange", $type = "direct")
    {
        // TODO: Implement setExchange() method with Standard queue / FIFO queue smart selection based on $name or $type.
        return $this;
    }

    public function setQueue($name = "default")
    {
        $this->queueName = $name;

        return $this;
    }

    public function getQueueUrl()
    {
        $awsConfig = $this->config->get('aws');
        $namespace = '';

        $queueUrl = "https://sqs.{$awsConfig['region']}.amazonaws.com/{$awsConfig['account_id']}/";

        if (isset($awsConfig['queue']['namespace']) && $awsConfig['queue']['namespace']) {
            $namespace = $awsConfig['queue']['namespace'] . '';
        }

        return $queueUrl . $namespace . $this->queueName;
    }

    public function send($message)
    {
        $msgClass = new Message();
        $body = [
            'queueName' => $this->queueName,
            'message' => $msgClass->setData($message)
        ];

        $asyncMessageResponse = null;
        $response = null;

        try {
            $asyncMessageResponse = $this->client->sendMessageAsync([
                'QueueUrl' => $this->getQueueUrl(),
                'MessageBody' => json_encode($body)
            ]);

            if ($asyncMessageResponse) {
                $response = $asyncMessageResponse->wait();
            }
        } catch (SqsException $e) {
            error_log('[SQS Queue:send] ' . get_class($e) . ': ' . $e->getMessage());
        }

        // Reset exchange
        $this->setExchange();

        return $response;
    }

    public function receive($callback)
    {
        $config = $this->config->get('aws');

        $maxMessages = isset($config['queue']['max_messages']) ? $config['queue']['max_messages'] : 10;
        $waitSeconds = isset($config['queue']['wait_seconds']) ? $config['queue']['wait_seconds'] : 20;

        // All values should be read here because
        // queue calls within $callback can introduce
        // subtle bugs (as setting the wrong queue for
        // deletion, etc).
        $queueUrl = $this->getQueueUrl();
        $queueOpts = [
            'QueueUrl' => $queueUrl,
            'MaxNumberOfMessages' => $maxMessages,
            'WaitTimeSeconds' => $waitSeconds
        ];

        echo 'Queue Options: ' . print_r($queueOpts, 1);

        while (true) {
            $result = null;
            try {
                $result = $this->client->receiveMessage($queueOpts);
            } catch (SqsException $e) {
                echo '[SQS Queue:receive:receive] ' . get_class($e) . ': ' . $e->getMessage();
            }

            if (!$result || !$result->search('Messages[*].Body')) {
                echo '.';
                continue;
            }

            foreach ($result->search('Messages') as $message) {
                $receiptHandle = $message['ReceiptHandle'];
                $body = json_decode($message['Body']);

                try {
                    $callback(new Message($body->message));
                } catch (\Exception $e) {
                    echo '[SQS Queue:receive:callback] ' . get_class($e) . ': ' . $e->getMessage();
                    echo $e->getTraceAsString();
                }

                try {
                    $this->client->deleteMessage([
                        'QueueUrl' => $queueUrl,
                        'ReceiptHandle' => $receiptHandle
                    ]);
                } catch (\Exception $e) {
                    echo '[SQS Queue:receive:purge] ' . get_class($e) . ': ' . $e->getMessage();
                }
            }
        }
    }
}
