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
        } else {
            if($entity->guid)
                $guid = $entity->guid;
            else
                return null;
        }
        $client =Core\Data\Client::build('Cassandra');
        $query = new Core\Data\Cassandra\Prepared\Counters();
        $client->request($query->update($guid, $metric, $value));
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
        $client =Core\Data\Client::build('Cassandra');
        $query = new Core\Data\Cassandra\Prepared\Counters();
        $client->request($query->update($guid, $metric, $value));
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
            } else {
                $prepared[] = $query->update($entity->guid, $metric, $value)->build();
            }
        }
        $client->batchRequest($prepared);
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
        if($count = $cacher->get("counter:$guid:$metric") && $cache){
            return $count;
        }
        $client = Core\Data\Client::build('Cassandra');
        $query = new Core\Data\Cassandra\Prepared\Counters();
        $result = $client->request($query->get($guid, $metric));
        if(isset($result[0]) && isset($result[0]['count']))
            $count = $result[0]['count'];
        else 
            $count =  0;
        $cacher->set("counter:$guid:$metric", $count, 360); //cache for 10 minutes
        return $count;
    }
        
}   
