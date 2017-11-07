<?php
namespace Minds\Core\Queue;
use Minds\Core\Di\Di;

/**
 * Messaging queue
 */

class Client
{
    /**
     * Build the client
     * @param string $client
     * @return mixed
     * @throws \Exception
     */
     public static function build($client = '')
     {
        $alias = 'Queue';

        if ($client) {
            $alias = "Queue\\{$client}";
        }

        $instance = Di::_()->get($alias);

        if (!$instance) {
            throw new \Exception("DI binding not found: {$alias}");
        } elseif (!($instance instanceof Interfaces\QueueClient)) {
            throw new \Exception("DI binding is not of Interface QueueClient: {$alias}");
        }

        return $instance;
     }
}
