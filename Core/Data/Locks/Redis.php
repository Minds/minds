<?php


namespace Minds\Core\Data\Locks;

use Minds\Core\Di\Di;
use Minds\Core\Data\Redis\Client as RedisServer;

class Redis
{
    /** @var Redis */
    protected $redis;

    protected $key;
    protected $ttl;

    public function __construct($redis = null, $config = null)
    {
        $this->config = $config ?: Di::_()->get('Config');
        $this->redis = $redis ?: new RedisServer();
        $this->redis->connect($this->config->redis['master']);
    }

    public function setKey($key)
    {
        $this->key = $key;
        return $this;
    }

    public function setTTL($ttl)
    {
        $this->ttl = $ttl;
        return $this;
    }

    public function isLocked() {
        if (!isset($this->key)) {
            throw new KeyNotSetupException();
        }

        return (bool) $this->redis->get("lock:$this->key");
    }

    public function lock()
    {
        if (!isset($this->key)) {
            throw new KeyNotSetupException();
        }

        $opts = [
            'nx' => true, //only if exists
        ];

        if (isset($this->ttl)) {
            $opts['ex'] = $this->ttl;
        }

        $result = $this->redis->set("lock:$this->key", 1, $opts);

        if (!$result || $result != "OK") {
            throw new LockFailedException();
        }

        return $result;
    }

    public function unlock()
    {
        if (!isset($this->key)) {
            throw new KeyNotSetupException();
        }
        
        $result = $this->redis->delete("lock:$this->key");

        return $result == "OK";
    }
}
