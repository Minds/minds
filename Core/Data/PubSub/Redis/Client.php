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
        if (class_exists('\Redis')) {
            $this->redis = $redis ?: new RedisServer();

            $config = Config::_()->get('redis');
            try {
                $this->redis->connect($config['pubsub'] ?: $config['master'] ?: '127.0.0.1');
            } catch (\Exception $e) { }
        }
    }

    public function __destruct()
    {
        try {
            if ($this->redis) {
                $this->redis->close();
            }
        } catch (\Exception $e) {
        }
    }

    public function publish($channel, $data = '')
    {
        if (!$this->redis) {
            return false;
        }
        return $this->redis->publish($channel, $data);
    }
}
