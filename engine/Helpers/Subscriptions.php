<?php
/**
 * Subscriptions helpers
 */
namespace Minds\Helpers;

use Minds\Core;

class Subscriptions{

    /**
     * Subscribe a user to a user
     * @param long $user_guid - the user who is doing the action, eg. me
     * @param long $to_guid - the user to subscribe to
     * @return boolean
     */
    public static function subscribe($user_guid, $to_guid, $data = array()){
        $return = false;
        if(empty($data))
            $data = time();
        
        $friends = new Core\Data\Call('friends');
        $friendsof = new Core\Data\Call('friendsof');
        
        
        if(is_array($data))
            $data = json_encode($data);
        
        if($friends->insert($user_guid, array($to_guid=>$data)) && $friendsof->insert($to_guid, array($user_guid=>$data)))
            $return =  true;
        
        $prepared = new Core\Data\Neo4j\Prepared\Subscriptions();
        $return =  Core\Data\Client::build('Neo4j')->request($prepared->createSubscription($user_guid, $to_guid));
        return $return;
    }
    
    public static function unSubscribe($user, $from){
        
    }
    
    public static function isSubscribed($user, $to){
    }
    
    public static function getSubscriptions($user){
    }
    
    public static function getSubscribers($user){
    }
        
}   