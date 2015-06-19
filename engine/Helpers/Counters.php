<?php
/**
 * Counters
 */
namespace Minds\Helpers;

use Minds\Core;

class Counters{

    /**
     * Increment a count
     * 
     * @param mixed Entity or number - $entity
     * @param string $metric
     * @param int $value - defaults to 1
     * @return void
     */
    public static function increment($entity, $metric, $value = 1){
        if(is_numeric($entity)){
            $guid = $entity;
            //error_log($guid);
        } else {
            if($entity->guid)
                $guid = $entity->guid;
            else
                return null;
        }
        $client =Core\Data\Client::build('Cassandra');
        $query = new Core\Data\Cassandra\Prepared\Counters();
        try{
            $client->request($query->update($guid, $metric, $value));
        }catch(\Exception $e){}
        //error_log("$guid:$metric:$value");
        $cacher = Core\Data\cache\factory::build();
        $cacher->destroy("counter:$guid:$metric");
    }
    
    /**
     * Decrement a count
     * 
     * @param mixed Entity or number - $entity
     * @param string $metric
     * @return void
     */
    public static function decrement($entity, $metric, $value = 1){
        if(is_numeric($entity)){
            $guid = $entity;
        } else {
            $guid = $entity->guid;
        }
        $value = $value * -1; //force negative
        try{
            $client =Core\Data\Client::build('Cassandra');
            $query = new Core\Data\Cassandra\Prepared\Counters();
            $client->request($query->update($guid, $metric, $value));
        }catch(\Exception $e){}
    }
    
    /**
     * Increment a batch
     * @return $this
     */
    public static function incrementBatch($entities, $metric, $value = 1){
        $prepared = array();
        $client = Core\Data\Client::build('Cassandra');
        $query = new Core\Data\Cassandra\Prepared\Counters();
        foreach($entities as $entity){
            if(is_numeric($entity)){
                $prepared[] = $query->update($entity, $metric, $value)->build();
            } elseif($entity->guid) {
		        $prepared[] = $query->update($entity->guid, $metric, $value)->build();
                if($entity->remind_object && isset($entity->remind_object['guid']))
                    $prepared[] = $query->update($entity->remind_object['guid'], $metric, $value)->build();

                if($entity->owner_guid)
                    $prepared[] = $query->update($entity->owner_guid, $metric, $value)->build();
            }
        }
        try {
            $client->batchRequest($prepared);
        } catch(\Exception $e){
            error_log("exception in batch increment " . $e->getMessage());
        }  
     }
    
    /**
     * Return the count for a single entity/metric
     * @param mixed Entity or number - $entity
     * @param string $metric
     * @return int
     */
    public static function get($entity, $metric, $cache = true){
        $cacher = Core\Data\cache\factory::build();
        if(is_numeric($entity)){
            $guid = $entity;
        } else {
            $guid = $entity->guid;
        }
        $cached = $cacher->get("counter:$guid:$metric");
        if($cached !== FALSE && $cache){
            return (int) $cached;
        }
        $client = Core\Data\Client::build('Cassandra');
        $query = new Core\Data\Cassandra\Prepared\Counters();
        try{
	    $result = $client->request($query->get($guid, $metric));
            if(isset($result[0]) && isset($result[0]['count']))
                $count = $result[0]['count'];
            else 
                $count =  0;
       	 } catch(\Exception $e){
		return 0;
	}
	 $cacher->set("counter:$guid:$metric", $count, 360); //cache for 10 minutes
        return (int) $count;
    }

    /**
     * Reset a counter
     * @param mixed - Entity or number $entity
     * @param string $metric
     * @param int $value (optional) 
     * @return void;
     */
    public static function clear($entity, $metric, $value = 0){
        if(is_numeric($entity)){
            $guid = $entity;
        } else {
            if($entity->guid)
                $guid = $entity->guid;
            else
                return null;
        }
        $client =Core\Data\Client::build('Cassandra');
        $query = new Core\Data\Cassandra\Prepared\Counters();
        //$client->request($query->clear($guid, $metric));
        $count = self::get($entity, $metric, false);
        self::decrement($entity, $metric, $count);
    } 
            
}   
