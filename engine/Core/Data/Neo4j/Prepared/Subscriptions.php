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
                                "MATCH (u:User {guid:row.guid}), (subscription:User {guid:row.subscription_guid}) " .
                                "CREATE (u)-[:SUBSCRIBED]->(subscription)";
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
                            "MERGE (user)-[:SUBSCRIBED]->(to)";
        $this->values = array(
            'user_guid' => is_numeric($user) ? $user : $user->guid,
            'subscriber_guid' => is_numeric($to) ? $to: $to->guid,
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
                            "WHERE NOT (user)-[:SUBSCRIBED]-(fof) AND NOT (fof.guid = user.guid) " .
                            "RETURN fof, COUNT(*) ".
                            "ORDER BY COUNT(*) DESC ".
                            "LIMIT 10";
        $this->values = array(
                            'guid' => (string) $user->guid
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
