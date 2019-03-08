<?php
/**
 * SortedSet
 *
 * @author: Emiliano Balbuena <edgebal>
 */

namespace Minds\Core\Data;

use Minds\Common\Repository\Response;
use Minds\Core\Data\cache\Redis;
use Minds\Core\Di\Di;

class SortedSet
{
    /** @var Redis */
    protected $redis;

    /** @var bool */
    protected $initialized = false;

    /** @var string */
    protected $key;

    /** @var int */
    protected $throttle;

    /** @var array */
    protected $pool = [];

    /**
     * SortedSet constructor.
     * @param Client
     */
    public function __construct($redis = null)
    {
        $this->redis = $redis ?: Di::_()->get('Cache\Redis');
    }

    /**
     * @param string $key
     * @return SortedSet
     */
    public function setKey($key)
    {
        $this->key = $key;
        return $this;
    }

    /**
     * @param int $throttle
     * @return SortedSet
     */
    public function setThrottle($throttle)
    {
        $this->throttle = $throttle;
        return $this;
    }

    /**
     * @return bool
     * @throws \Exception
     */
    public function isThrottled()
    {
        if (!$this->key) {
            throw new \Exception('Missing key');
        }

        if (!$this->throttle) {
            return false;
        }

        $timestamp = (int) $this->redis->forReading()->get($this->getTimestampKey());

        if (!$timestamp) {
            return false;
        }

        return $timestamp + $this->throttle > time();
    }

    /**
     * @return SortedSet
     * @throws \Exception
     */
    public function clean()
    {
        if (!$this->key) {
            throw new \Exception('Missing key');
        }

        $this->redis->forWriting()->del($this->getSetKey());
        $this->redis->forWriting()->set($this->getTimestampKey(), time());
        $this->initialized = true;

        return $this;
    }

    /**
     * @param int $ttl
     * @return SortedSet
     */
    public function expiresIn($ttl)
    {
        $this->redis->forWriting()->expire($this->getSetKey(), $ttl);
        $this->redis->forWriting()->expire($this->getTimestampKey(), $ttl);

        return $this;
    }

    /**
     * @param float $order
     * @param string $value
     * @return SortedSet
     * @throws \Exception
     */
    public function add($order, $value)
    {
        if (!$this->key) {
            throw new \Exception('Missing key');
        }

        $this->redis->forWriting()->zAdd($this->getSetKey(), $order, $value);

        return $this;
    }

    /**
     * @param $order
     * @param $value
     * @return SortedSet
     */
    public function lazyAdd($order, $value)
    {
        $this->pool[] = $order;
        $this->pool[] = $value;
        return $this;
    }

    /**
     * @param int $threshold
     * @return SortedSet
     * @throws \Exception
     */
    public function flush($threshold = 0)
    {
        if ((!$threshold && count($this->pool) > 0) || (count($this->pool) >= $threshold)) {
            if (!$this->key) {
                throw new \Exception('Missing key');
            }

            $this->redis->forWriting()->zAdd($this->getSetKey(), ...$this->pool);
            $this->pool = [];
        }

        return $this;
    }

    /**
     * @param int $size
     * @param int $start
     * @return Response
     * @throws \Exception
     */
    public function fetch($size, $start = 0)
    {
        if (!$this->key) {
            throw new \Exception('Missing key');
        }

        if ($size <= 0) {
            throw new \Exception('Invalid slice size');
        }

        if (!is_numeric($start) || $start < 0) {
            throw new \Exception('Invalid slice offset');
        }

        $end = $start + $size - 1;

        // Fetch data

        $data = $this->redis->forReading()->zRange($this->getSetKey(), $start, $end);

        // Build Response

        $response = new Response($data ?: [], $end + 1);
        $response->setLastPage(!$data || count($data) < $size);

        if (!is_array($data)) {
            $response->setException(new \Exception('Invalid response from server'));
        }

        return $response;
    }

    /**
     * @return string
     */
    protected function getSetKey()
    {
        return "SortedSet:{$this->key}";
    }

    /**
     * @return string
     */
    protected function getTimestampKey()
    {
        return "SortedSet\$ts:{$this->key}";
    }
}
