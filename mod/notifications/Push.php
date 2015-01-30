<?php
/**
 * Minds Push Notifications
 */
 
namespace minds\plugin\notifications;

use Minds\Core;
use Surge;

class Push {

    public $locked = false;
    
    public static function send($message = array(), $token = NULL){
        error_log('sending..');
        error_log($token);
        if(!$token)
            return false;
        $config = new Surge\Config(array(
            'Apple' => array(
                'cert'=> '/var/secure/apns.pem',
                'sandbox'=>true
            ),
            'Google' => array(
                'api_key' => 'AIzaSyCp0LVJLY7SzTlxPqVn2-2zWZXQKb1MscQ'
            )));

        error_log('trying to send..');           
           error_log("sending for $token"); 
        $message = Surge\Messages\Factory::build($token)
            ->setTitle($message['title'])
            ->setMessage($message['message']);
            
        Surge\Surge::send($message, $config);
    }

    public static function queue($user_guid, $message = array()){
        $job = array(
            'user_guid' => $user_guid,
            'message' => $message
            );
        $guid = new \GUID();
        $db = new Core\Data\Call('entities_by_time');
        $db->insert('push:queue', array($guid->generate() => json_encode($job)));
    }
    
    public static function run(){
        if(!self::$locked)
            self::$locked = true;
        else
            return false;

        $db = new Core\Data\Call('entities_by_time');
        $jobs = $db->getRow('push:queue');
        foreach($jobs as $guid => $job){
            echo "sending $guid \n";
            $job = json_decode($job, true);
            $user = new \Minds\entities\user($job['user_guid']);
            if($user->surge_token)
                self::send($job['message'], $user->surge_token);
            $db->removeAttributes('push:queue', array($guid));
        }
        self::$locked = false;
    }
}
