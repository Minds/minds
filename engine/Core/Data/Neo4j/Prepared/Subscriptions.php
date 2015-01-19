<?php
/**
 * Subscriptions, prepared cyphers
 */
namespace Minds\Core\Data\Neo4j\Prepared;

use Minds\Core\Data\Interfaces;
use Minds\Entities;

class Subscriptions implements Interfaces\PreparedInterface{
    
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
                                "MATCH (u:User {guid:row.guid}), (subscriber:User {guid:row.subscription_guid}) " .
                                "CREATE (u)-[:SUBSCRIBED]->(subscriber)";
         return $this;
     }
     
     /**
      * Import bulk subscriber
      */
    
    /**
     * Create a subscription
     * @param User $user
     * @param User $subscriber
     * @return $this
     */
    public function createSubscription(Entities\User $user, Entities\User $subscriber){
        $this->template =   "MATCH (user {guid: {user_guid}, username: {user_username}})," .
                            "(subscriber {guid: {subscriber_guid}, username: {subscriber_username}}) " . 
                            "MERGE (subscriber)-[r:SUBSCRIBED]->(user) RETURN r";
        $this->values = array(
            'user_guid' => $user->guid,
            'user_username' => $user->username,
            'subscriber_guid' => $subscriber->guid,
            'subscriber_username' => $subscriber->username
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
        $this->template = "MATCH (user {guid: {guid}})-[:SUBSCRIBED*2..2]->(fof) ".
                            "WHERE NOT (user)-[:SUBSCRIBED]-(fof) " .
                            "RETURN fof, COUNT(*) ".
                            "ORDER BY COUNT(*) DESC ".
                            "LIMIT 10";
        $this->values = array(
                            'guid' => $user->guid
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
        
}