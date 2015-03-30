<?php
namespace Minds\Helpers;
use Minds\Core;
use Minds\Core\Data;

/**
 * A helper class to provide request metrics
 */
class RequestMetrics{

    static private $namespace = "requestmetrics";
    /**
     * Increment
     * @param $metric
     * @return void
     */
    static public function increment($metric = "all"){
        $ts = self::buildTS();
        Counters::increment($ts, $metric);
    }

    /**
     * Get request metrics
     * @param $metric
     * @param int $ts
     * @return int - the count
     */
    static public function get($metric = "all", $ts = NULL){
        $client = Core\Data\Client::build('Cassandra');
        $query = new Core\Data\Cassandra\Prepared\Counters();
        try{
            $result = $client->request($query->get(self::buildTS($ts), $metric));
            if(isset($result[0]) && isset($result[0]['count']))
                $count = $result[0]['count'];
            else 
                $count =  0;
        } catch(\Exception $e){
        var_dump($e); exit;
            return 0;
        }
        return $count;
    }

    /**
     * Get timestamp to nearest 5 minutes
     */
    static public function buildTS($ts = NULL){
        if(!$ts)
            $ts = time();     
        return ceil($ts/300)*300;
    }

}
