<?php
/**
 * Redis Pub/Sub Client interface
 */
namespace Minds\Core\Data\PubSub\Redis;

use Minds\Core\Config;
use \Redis as RedisServer;

class Client
{
    private $redis;

    public function __construct($redis = null)
    {
        $this->redis = $redis ?: new RedisServer();

        $config = Config::_()->get('redis');
        $this->redis->connect($config['pubsub'] ?: $config['master'] ?: '127.0.0.1');
    }

    public function __destruct()
    {
        try {
            $this->redis->close();
        } catch (\Exception $e) {
        }
    }

    public function publish($channel, $data = '')
    {
        return $this->redis->publish($channel, $data);
    }
}
