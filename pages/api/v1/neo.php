<?php
/**
 * Minds Newsfeed API
 * 
 * @version 1
 * @author Mark Harding
 */
namespace minds\pages\api\v1;

use minds\core;
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
        var_dump($req);
        exit;

        $neo = \Minds\Core\Data\Client::build('neo4j');
        $cypher = new \Minds\Core\Data\Neo4j\Prepared\CypherQuery();
        
        $cypher->setQuery("MATCH (n) OPTIONAL MATCH (n)-[r]-() DELETE n,r");
        $neo->request($cypher);
        
        foreach(array('mark', 'john', 'bill') as $username){
            $cypher->setQuery("CREATE (channel:Channel { username : {username} })", array('username'=>$username));
            $neo->request($cypher);
        }
       
        //mark is subscribed to john
        $cypher->setQuery("MATCH (a {username: 'mark'}),(b {username: 'john'}) CREATE (a)-[r:SUBSCRIBED]->(b) RETURN r");
        $req = $neo->request($cypher);
        //bill is subscribed to mark
        $cypher->setQuery("MATCH (a {username: 'bill'}),(b {username: 'mark'}) CREATE (a)-[r:SUBSCRIBED]->(b) RETURN r");
        $req = $neo->request($cypher);
       
        //return johns subscriptions 2 degrees
        $cypher->setQuery("MATCH (a {username: 'john'})<-[:SUBSCRIBED*..2]-(friendoffriend) WHERE NOT (a)-[:SUBSCRIBED]-(friendoffriend) RETURN friendoffriend");
        $req = $neo->request($cypher);
        foreach($req as $row){
            echo "<pre>";
            var_dump($row['channel']->getProperty('username'));
            echo "</pre>";
        }
           
        exit;
            
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
        
