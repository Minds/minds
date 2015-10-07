<?php
/**
 * Minds Push Notifications
 */
 
namespace minds\plugin\notifications;

use Minds\Core;
use Surge;

class Push {

    public static $locked = false;
    
    public static function send($message = array(), $token = NULL){
        if(!$token)
            return false;
        
        $config = new Surge\Config(array(
            'Apple' => array(
                'cert'=> '/var/secure/apns-production.pem'
             //   'sandbox'=>true,
             //   'cert'=> '/var/secure/apns.pem'
            ),
            'Google' => array(
                'api_key' => 'AIzaSyCp0LVJLY7SzTlxPqVn2-2zWZXQKb1MscQ'
            )));

        error_log('trying to send..');           
        error_log("sending for $token"); 
        $message = Surge\Messages\Factory::build($token)
            ->setTitle($message['title'])
            ->setMessage($message['message'])
            ->setURI(isset($message['uri']) ? $message['uri'] : 'chat');
            
        Surge\Surge::send($message, $config);
    }

    public static function queue($user_guid, $message = array()){
        $job = array(
            'user_guid' => $user_guid,
            'message' => $message
            );
        $db = new Core\Data\Call('entities_by_time');
        $db->insert('push:queue', array(Core\Guid::build() => json_encode($job)));
    }
    
    
    /**Deprecated**/
    public static function run(){
        if(!self::$locked)
            self::$locked = true;
        else
            return false;

        $db = new Core\Data\Call('entities_by_time');
        $jobs = $db->getRow('push:queue');
        if($jobs){
            foreach($jobs as $guid => $job){
                echo "sending $guid \n";
                $job = json_decode($job, true);
                $user = new \Minds\Entities\user($job['user_guid'], false); //dont cache... or find a better way to grab token id
                if($user->surge_token)
                    self::send($job['message'], $user->surge_token);
                echo "Surge token was :: $user->surge_token \n";
                $db->removeAttributes('push:queue', array($guid));
            }
        }
        self::$locked = false;
    }
}
