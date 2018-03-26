<?php

namespace Spec\Minds\Core\Queue\SQS;

use Aws\Sns\Exception\SnsException;
use GuzzleHttp\Promise\Promise;
use Minds\Core\Queue\Message;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

use Minds\Core\Config\Config;

use Aws\Sqs\SqsClient;

class ClientSpec extends ObjectBehavior
{
    protected $_config;
    protected $_sqsClient;

    function let(Config $config, SqsClient $sqsClient)
    {
        $this->_config = $config;
        $this->_sqsClient = $sqsClient;

        $this->_config->get('aws')
            ->shouldBeCalled()
            ->willReturn([
                'useRoles' => true,
                'region' => 'x-test-1',
                'account_id' => '5000'
            ]);

        $this->beConstructedWith($config, $sqsClient);
    }

    function it_is_initializable_with_roles()
    {
        $this->shouldHaveType('Minds\Core\Queue\SQS\Client');
    }

    function it_is_initializable_without_roles()
    {
        $this->_config->get('aws')
            ->shouldBeCalled()
            ->willReturn([
                'useRoles' => false,
                'key' => 'test',
                'secret' => 'test00',
                'region' => 'x-test-1',
                'account_id' => '5000'
            ]);

        $this->shouldHaveType('Minds\Core\Queue\SQS\Client');
    }

    //public function it_should_set_exchange()
    //{
        // TODO: Implement setExchange() and test it
    //}

    public function it_should_set_queue()
    {
        $this
            ->setQueue('test')
            ->shouldReturn($this);
    }

    public function it_should_get_queue_url()
    {
        $this
            ->setQueue('test')
            ->getQueueUrl()
            ->shouldReturn('https://sqs.x-test-1.amazonaws.com/5000/test');
    }

    public function it_should_get_queue_url_with_namespace()
    {
        $this->_config->get('aws')
            ->shouldBeCalled()
            ->willReturn([
                'useRoles' => true,
                'region' => 'x-test-1',
                'account_id' => '5000',
                'queue' => [
                    'namespace' => 'phpspec'
                ]
            ]);

        $this
            ->setQueue('test')
            ->getQueueUrl()
            ->shouldReturn('https://sqs.x-test-1.amazonaws.com/5000/phpspectest');
    }

    public function it_should_send(
        Promise $response
    )
    {
        $msgClass = new Message();
        $body = [
            'queueName' => 'test',
            'message' => $msgClass->setData([ 'foo' => 'bar' ])
        ];

        $this->_sqsClient->sendMessageAsync([
            'QueueUrl' => 'https://sqs.x-test-1.amazonaws.com/5000/test',
            'MessageBody' => json_encode($body)
        ])
            ->shouldBeCalled()
            ->willReturn($response);

        $response->wait()
            ->shouldBeCalled()
            ->willReturn(true);

        $this
            ->setQueue('test')
            ->send([
                'foo' => 'bar'
            ])
            ->shouldReturn(true);
    }

    public function it_should_not_send_if_send_message_async_threw(
        Promise $response
    )
    {
        $msgClass = new Message();
        $body = [
            'queueName' => 'test',
            'message' => $msgClass->setData([ 'foo' => 'bar' ])
        ];

        $this->_sqsClient->sendMessageAsync([
            'QueueUrl' => 'https://sqs.x-test-1.amazonaws.com/5000/test',
            'MessageBody' => json_encode($body)
        ])
            ->shouldBeCalled()
            ->willThrow(SnsException::class);

        $response->wait()
            ->shouldNotBeCalled();

        $this
            ->setQueue('test')
            ->shouldThrow(SnsException::class)
            ->duringSend([
                'foo' => 'bar'
            ]);
    }

    public function it_should_not_send(
        Promise $response
    )
    {
        $msgClass = new Message();
        $body = [
            'queueName' => 'test',
            'message' => $msgClass->setData([ 'foo' => 'bar' ])
        ];

        $this->_sqsClient->sendMessageAsync([
            'QueueUrl' => 'https://sqs.x-test-1.amazonaws.com/5000/test',
            'MessageBody' => json_encode($body)
        ])
            ->shouldBeCalled()
            ->willReturn($response);

        $response->wait()
            ->shouldBeCalled()
            ->willThrow(SnsException::class);

        $this
            ->setQueue('test')
            ->shouldThrow(SnsException::class)
            ->duringSend([
                'foo' => 'bar'
            ]);
    }
}
