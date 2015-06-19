<?php
/**
 * Redis cacher
 * @author Mark Harding
 */
namespace Minds\Core\Data\cache;

use Redis;

class apcu extends abstractCacher{
	
    private $master = "127.0.0.1";
    private $slave = "127.0.0.1";

	public function __construct(){
		global $CONFIG;
        if(isset($CONFIG->redis)){
            if(isset($CONFIG->redis['master']))
                $this->master =  $CONFIG->redis['master'];
            if(isset($CONFIG->redis['slave']))
                $this->slave =  $CONFIG->redis['slave'];
        }
	}

	public function get($key){
		$redis = new Redis();
        $redis->connect($this->slave);
        if($value = $redis->get($key))
            return json_decode($key, true);
        return false;
	}

	public function set($key, $value, $ttl = 0){
		$redis = new Redis();
        $redis->connect($this->master);
        $redis->set($key, json_encode($value), array('xx', 'px'=>$ttl));
	}

	public function destroy($key){
		$redis = new Redis();
        $redis->connect($this->master);
        $redis->delete($key);
	}
}
	
