<?php
/**
 * Minds Queue Provider
 */

namespace Minds\Core\Queue;

use Minds\Core;
use Minds\Core\Data;
use Minds\Core\Di\Provider;

class QueueProvider extends Provider
{

    public function register()
    {
        $this->di->bind('Queue', function($di){
            $config = $di->get('Config');
            return new RabbitMQ\Client(
              $config,
              new AMQPConnection(
                    $config->rabbitmq['host'],
                    $config->rabbitmq['port'],
                    $config->rabbitmq['username'],
                    $config->rabbitmq['password'],
                    '/'
                ));
        }, ['useFactory'=>true]);
    }

}
