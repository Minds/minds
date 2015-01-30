<?php
/**
 * Common prepared cypher queries
 */
namespace Minds\Core\Data\Neo4j\Prepared;

use Minds\Core\Data\Interfaces;
use Minds\Entities;

class Common implements Interfaces\PreparedInterface{
    
    private $template;
    private $values = array(); 
    
    public function build(){
        return array(
            'string' => $this->template,
            'values'=>$this->values
            );
            
    }
    
    /**
     * Create a user node 
     * @param User $user
     * @return $this
     */
    public function createUser(Entities\User $user){
        $this->template = "MERGE (user:User { guid: {guid}, username:{username} })";
        $this->values = array('guid'=>$user->guid, 'username'=>$user->username);
        return $this;
    }
    
    /**
     * Import bulk users
     */
    public function createBulkUsers(array $users = array()){
        foreach($users as $user){
           $exp[] = array(
                        'username'=>$user->username,
                        'guid'=>$user->guid
                        );
        }
        $this->template = "FOREACH (u IN " . preg_replace('/"([^"]+)"\s*:\s*/', '$1:', json_encode($exp))  . " | MERGE(user:User {guid: str(u.guid), username: u.username}))";
        return $this;
    }
    
    /**
     * Import bulk subscriptions
     */
     public function createBulkSubscriptions(array $subscriptions = array()){
         foreach($subscriptions as $subscriber=> $array){
              foreach($array as $subscription_guid => $ts){
                  $exp[] = array(
                            'guid'=> (string) $subscriber,
                            'subscription_guid' => (string) $subscription_guid
                            );
              }
         }
           
         $this->template = "UNWIND " . preg_replace('/"([^"]+)"\s*:\s*/', '$1:', json_encode($exp)) . " AS row ".
                                "MATCH (u:User {guid:row.guid}), (subscription:User {guid:row.subscription_guid}) " .
                                "MERGE (u)-[:SUBSCRIBED]->(subscription) MERGE (u)-[:ACTED]->(subscription)";
         return $this;
     }
     
     /**
      * Import bulk subscriber
      */
    
    /**
     * Create a subscription
     * @param User or integer $user
     * @param User or integer $to
     * @return $this
     */
    public function createSubscription($user, $to){
        $this->template =   "MATCH (user:User {guid: {user_guid}})," .
                            "(to:User {guid: {subscriber_guid}}) " . 
                            "MERGE (user)-[:SUBSCRIBED]->(to) MERGE (user)-[:ACTED]->(to)";
        $this->values = array(
            'user_guid' => is_numeric($user) ? (string) $user : $user->guid,
            'subscriber_guid' => is_numeric($to) ? (string) $to: $to->guid,
            );
        return $this;
    }
    
    /**
     * Create a pass
     * @param integer $user
     * @param integer $to
     * @return $this
     */
    public function createPass($user, $to){
	   $this->template =   "MATCH (user:User {guid: {user_guid}})," .
                            "(to {guid: {subscriber_guid}}) " . 
                            "MERGE (user)-[:PASS]->(to) MERGE (user)-[:ACTED]->(to)";
        $this->values = array(
            'user_guid' => (string) $user,
            'subscriber_guid' => (string) $to,
            );
        return $this;
    }
    
    /**
     * Return subscribers
     * @param User $user
     * @return $this
     */
    public function getSubscribers(Entities\User $user){
        $this->template = "MATCH (user {guid: {guid}})<-[:SUBSCRIBED]-(subscriber) RETURN subscriber";
        $this->values = array('guid'=>$user->guid);
        return $this;
    }
    
    /**
     * Return subscriptions of subscriptions
     * @param User $user
     * @return $this
     */
    public function getSubscriptionsOfSubscriptions(Entities\User $user){
        $this->template = "MATCH (user:User {guid: {guid}})-[:SUBSCRIBED*2..2]->(fof:User) ".
                            "WHERE " . 
			                 "NOT (user)-[:ACTED]->(fof) " .
			                 "AND NOT (fof.guid = user.guid) " . 
                            "RETURN fof ".
                            //"ORDER BY COUNT(*) DESC ".
                            "LIMIT {limit}";
        $this->values = array(
                            'guid' => (string) $user->guid,
			                'limit' => 12
                            );
        return $this;
    }
    
    /**
     * Return degree
     * @param User $user
     * @return $this
     */
    public function getDegree($a, $b){
        $this->template = "MATCH (a {guid:{a_guid}}), (b {guid:{b_guid}}), " .
                            "p = shortestPath( a-[*..16]->b ) " .
                            "RETURN length(p)-1";
        $this->values = array(
                            'a_guid'=> (string) $a->guid,
                            'b_guid'=> (string) $b->guid
                            );
        return $this;
    }
    
    /**
     * Create objects
     */
    public function createObject(Entities\entity $object){
        $this->template = "MERGE (object:$object->subtype { guid: {guid} })";
        $this->values = array('guid'=>$object->guid);
        return $this;
    }
    
    /**
     * Import bulk users
     */
    public function createBulkObjects(array $objects = array(), $subtype="video"){
        foreach($objects as $object){
           $exp[] = array(
                        'guid'=>$object->guid,
                        'subtype'=>$object->subtype
                        );
        }
        $this->template = "FOREACH (o IN " . preg_replace('/"([^"]+)"\s*:\s*/', '$1:', json_encode($exp))  . " | MERGE(object:$subtype {guid: str(o.guid)}))";
        return $this;
    }
    
    /**
     * Return suggested content, based on 
     */
    public function getSuggestedObjects($user_guid, $subtype = 'video'){
        $this->template = "MATCH (a:User {guid:{user_guid}})-[:UP*..4]-(object:$subtype) " .
                            "WHERE NOT a-[:ACTED]->(object) " .
                            "RETURN object LIMIT 12";
        $this->values = array(
                            'user_guid'=> (string) $user_guid
                            );
        return $this;
    }

    /**
     * To be used only when no suggested content is found..
     */
    public function getObjects($user_guid, $subtype='video'){
        $this->template = "MATCH (object:$subtype), (user:User {guid:{user_guid}}) " .
                            "WHERE NOT user-[:ACTED]->(object) " .
                            "RETURN object LIMIT 12";
        $this->values = array(
                            'user_guid'=> (string) $user_guid
                            );
        return $this;
    }
    
    /**
     * Create a vote on an object
     * @param int $guid
     * @param string $subtype
     * @param int (optional) $user_guid
     * @return $this
     */
    public function createVoteUP($guid, $subtype, $user_guid = NULL){
        if(!$user_guid)
            $user_guid = \Minds\Core\session::getLoggedinUser()->guid;
        
        $this->template =   "MATCH (user:User {guid: {user_guid}})," .
                            "(object:$subtype {guid: {object_guid}}) " . 
                            "MERGE (user)-[:UP]->(object) MERGE (user)-[:ACTED]->(object)";
        $this->values = array(
            'user_guid' => (string) $user_guid,
            'object_guid' => (string) $guid,
            );
        return $this;
    }
   
    /**
     * Create a down vote on an object 
     * @param int $guid
     * @param string $subtype
     * @param int (optional) $user_guid
     * @return $this;
     */
    public function createVoteDOWN($guid, $subtype, $user_guid = NULL){
        if(!$user_guid)
            $user_guid = \Minds\Core\session::getLoggedinUser()->guid;

        $this->template =   "MATCH (user:User {guid: {user_guid}})," .
                            "(object:$subtype {guid: {object_guid}}) " .
                            "MERGE (user)-[:DOWN]->(object) MERGE (user)-[:ACTED]->(object)";
        $this->values = array(
            'user_guid' => (string) $user_guid,
            'object_guid' => (string) $guid,
            );
        return $this;
    } 
        
}
