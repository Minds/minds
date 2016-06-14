<?php
/**
 * Redis cacher
 * @author Mark Harding
 */
namespace Minds\Core\Data\cache;

use Redis as RedisServer;

class Redis extends abstractCacher
{
    private $master = "127.0.0.1";
    private $slave = "127.0.0.1";
    private $redisMaster;
    private $redisSlave;

    private $local = []; //a local cache before we check the remote

    public function __construct()
    {
        global $CONFIG;
        if (isset($CONFIG->redis)) {
            if (isset($CONFIG->redis['master'])) {
                $this->master =  $CONFIG->redis['master'];
            }
            if (isset($CONFIG->redis['slave'])) {
                $this->slave =  $CONFIG->redis['slave'];
            }
        }
    }

    private function getMaster()
    {
        if (!$this->redisMaster) {
            $this->redisMaster = new RedisServer();
            $this->redisMaster->connect($this->master);
        }
        return $this->redisMaster;
    }

    private function getSlave()
    {
        if (!$this->redisSlave) {
            $this->redisSlave = new RedisServer();
            $this->redisSlave->connect($this->slave);
        }
        return $this->redisSlave;
    }

    public function get($key)
    {
        if (isset($this->local[$key])) {
            return $this->local[$key];
        }
        try {
            $redis = $this->getSlave();
            $value = $redis->get($key);
            if ($value !== false) {
                $value = json_decode($value, true);
                if (is_numeric($value)) {
                    $this->local[$key] = (int) $value;
                    return (int) $value;
                }
                $this->local[$key] = $value;
                return $value;
            }
        } catch (\Exception $e) {
            //error_log("could not read redis $this->slave");
            //error_log($e->getMessage());
        }
        return false;
    }

    public function set($key, $value, $ttl = 0)
    {
        //error_log("still setting $key with value $value for $ttl seconds");
    try {
        $redis = $this->getMaster();
        if ($ttl) {
            $redis->set($key, json_encode($value), array('ex'=>$ttl));
        } else {
            $redis->set($key, json_encode($value));
        }
    } catch (\Exception $e) {
        //error_log("could not write ($key) to redis $this->master");
        //error_log($e->getMessage());
    }
    }

    public function destroy($key)
    {
        try {
            $redis = $this->getMaster();
            $redis->delete($key);
        } catch (\Exception $e) {
            //error_log("could not delete ($key) from redis $this->master");
        }
    }

    public function __destruct()
    {
        try {
            if ($this->redisSlave) {
                $this->redisSlave->close();
            }
            if ($this->redisMaster) {
                $this->redisMaster->close();
            }
        } catch (\Exception $e) {
        }
    }
}
