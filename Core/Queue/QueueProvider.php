<?php
/**
 * Minds Queue Provider
 */

namespace Minds\Core\Queue;

use Minds\Core\Di\Provider;

use PhpAmqpLib\Connection\AMQPConnection;

use Aws\Sqs\SqsClient;

class QueueProvider extends Provider
{
    public function register()
    {
        $this->di->bind('Queue', function($di) {
            $client = $di->get('Config')->get('queue_engine') ?: 'RabbitMQ';
            return $di->get('Queue\\' . $client);
        });

        // Clients

        $this->di->bind('Queue\RabbitMQ', function ($di) {
            $config = $di->get('Config');

            return new RabbitMQ\Client(
                $config,
                new AMQPConnection(
                    $config->rabbitmq['host'] ?: 'localhost',
                    $config->rabbitmq['port'] ?: 5672,
                    $config->rabbitmq['username'] ?: 'guest',
                    $config->rabbitmq['password'] ?: 'guest',
                    '/'
                )
            );
        }, [ 'useFactory' => true ]);

        $this->di->bind('Queue\SQS', function ($di) {
            return new SQS\Client($di->get('Config'));
        }, [ 'useFactory' => true ]);
    }
}
