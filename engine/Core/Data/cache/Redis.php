<?php
/**
 * Redis cacher
 * @author Mark Harding
 */
namespace Minds\Core\Data\cache;

use Redis as RedisServer;

class Redis extends abstractCacher{
	
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
        
        try{
		    $redis = new RedisServer();
            $redis->connect($this->slave);
            $value = $redis->get($key);
            if($value !== FALSE){
             
                $value = json_decode($value, true);

                if(is_numeric($value)){
                    return (int) $value;
                }
                return $value;
            }
        } catch(\Exception $e){
            error_log("could not read redis $this->slave");
        }
        return false;
	}

	public function set($key, $value, $ttl = 0){
        //error_log("still setting $key with value $value for $ttl seconds");
        try{
		    $redis = new RedisServer();
            $redis->connect($this->master);
            if($ttl)
                $redis->set($key, json_encode($value), array('ex'=>$ttl));
            else
                $redis->set($key, json_encode($value));
        } catch(\Exception $e){
            error_log("could not write to redis $this->master");
            error_log($e->getMessage());
        }
	}

	public function destroy($key){
		try{
            $redis = new RedisServer();
            $redis->connect($this->master);
            $redis->delete($key);
        } catch(\Exception $e){
            error_log("could not delete from redis $this->master");
        }
	}
}
	
