<?php
/**
 * Minds Newsfeed API
 * 
 * @version 1
 * @author Mark Harding
 */
namespace minds\pages\api\v1;

use Minds\Core;
use Minds\Entities;
use minds\interfaces;
use Minds\Api\Factory;

class neo implements Interfaces\api{

    /**
     * Neo test functions
     * 
     * API:: /v1/neo/
     */      
    public function get($pages){
        $neo = \Minds\Core\Data\Client::build('neo4j');
        $cypher = new \Minds\Core\Data\Neo4j\Prepared\CypherQuery();        

        $prepared =  new \Minds\Core\Data\Neo4j\Prepared\Common();
        //create john
        $neo->request($prepared->createUser(new Entities\User('john')));
        //create mark
        $neo->request($prepared->createUser(new Entities\User('mark')));

        $prepared->createSubscription(new Entities\User('john'), elgg_get_logged_in_user_entity());
        $req = $neo->request($prepared);

        $req = $neo->request($prepared->getSubscribers(new Entities\User('john')));
            
    }
    
    public function post($pages){}
    
    public function put($pages){
        
        return Factory::response(array());
        
    }
    
    public function delete($pages){
	$activity = new Entities\Activity($pages[0]); 
	if(!$activity->guid)
		return Factory::response(array('status'=>'error', 'message'=>'could not find activity post'));      
 
        return Factory::response(array());
        
    }
    
}
        
