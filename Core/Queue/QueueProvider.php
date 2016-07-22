<?php
/**
 * Minds Queue Provider
 */

namespace Minds\Core\Queue;

use Minds\Core;
use Minds\Core\Data;
use Minds\Core\Di\Provider;

use PhpAmqpLib\Connection\AMQPConnection;
use PhpAmqpLib\Message\AMQPMessage;

class QueueProvider extends Provider
{
    public function register()
    {
        $this->di->bind('Queue', function ($di) {
            $config = $di->get('Config');
            return new RabbitMQ\Client(
              $config,
              new AMQPConnection(
                    $config->rabbitmq['host'] ?: 'localhost',
                    $config->rabbitmq['port'] ?: 5672,
                    $config->rabbitmq['username'] ?: 'guest',
                    $config->rabbitmq['password'] ?: 'guest',
                    '/'
                ));
        }, ['useFactory'=>true]);
    }
}
