<?php
/**
 * Minds Queue Provider
 */

namespace Minds\Core\Queue;

use Minds\Core\Di\Provider;

use PhpAmqpLib\Connection\AMQPConnection;

class QueueProvider extends Provider
{
    public function register()
    {
        $this->di->bind('Queue', function($di) {
            return $di->get('Queue\RabbitMQ');
        });

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
    }
}
