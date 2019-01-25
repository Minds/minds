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

        $timestamp = (int) $this->redis->forReading()->get("{$this->key}::timestamp");

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

        $this->redis->forWriting()->del($this->key);
        $this->redis->forWriting()->set("{$this->key}::timestamp", time());
        $this->initialized = true;

        return $this;
    }

    /**
     * @param int $ttl
     * @return SortedSet
     */
    public function expiresIn($ttl)
    {
        $this->redis->forWriting()->expire($this->key, $ttl);
        $this->redis->forWriting()->expire("{$this->key}::timestamp", $ttl);

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

        $this->redis->forWriting()->zAdd($this->key, $order, $value);

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

        $data = $this->redis->forReading()->zRange($this->key, $start, $end);

        // Build Response

        $response = new Response($data ?: [], $end + 1);
        $response->setLastPage(!$data || count($data) < $size);

        if (!is_array($data)) {
            $response->setException(new \Exception('Invalid response from server'));
        }

        return $response;
    }
}
