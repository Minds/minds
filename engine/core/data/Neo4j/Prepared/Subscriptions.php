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
}