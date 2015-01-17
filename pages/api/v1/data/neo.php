<?php
/**
 * Minds Newsfeed API
 * 
 * @version 1
 * @author Mark Harding
 */
namespace minds\pages\api\v1;

use Minds\Core;
use minds\entities;
use minds\interfaces;
use minds\api\factory;

class neo implements interfaces\api{

    /**
     * Neo test functions
     * 
     * API:: /v1/neo/
     */      
    public function get($pages){
        $neo = \Minds\Core\Data\Client::build('neo4j');
        $cypher = new \Minds\Core\Data\Neo4j\Prepared\CypherQuery();        

        $prepared =  new \Minds\Core\Data\Neo4j\Prepared\Subscriptions();
        //create john
        $neo->request($prepared->createUser(new entities\user('john')));
        //create mark
        $neo->request($prepared->createUser(new entities\user('mark')));

        $prepared->createSubscription(new entities\user('john'), elgg_get_logged_in_user_entity());
        $req = $neo->request($prepared);

        $req = $neo->request($prepared->getSubscribers(new entities\user('john')));
            
    }
    
    public function post($pages){}
    
    public function put($pages){
        
        return factory::response(array());
        
    }
    
    public function delete($pages){
	$activity = new entities\activity($pages[0]); 
	if(!$activity->guid)
		return factory::response(array('status'=>'error', 'message'=>'could not find activity post'));      
 
        return factory::response(array());
        
    }
    
}
        
