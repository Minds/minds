<?php
/**
 * Redis client
 */
namespace Minds\Core\Data\Redis;

class Client
{

    /** @var Redis */
    private $redis;

    public function __construct($redis = null)
    {
        $this->redis = $redis ?: class_exists('Redis') ? new \Redis : null;
    }

    public function connect(...$args)
    {
        if (!$this->redis) {
            return false;
        }
        return $this->redis->connect(...$args);
    }

    public function get(...$args)
    {
        return $this->redis->get(...$args);
    }

    public function set(...$args)
    {
        return $this->redis->set(...$args);
    }

    public function delete(...$args)
    {
        return $this->redis->delete(...$args);
    }

    public function __call($function, $arguments)
    {
        return $this->redis->$function(...$arguments);
    }

}
