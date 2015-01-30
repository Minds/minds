<?php
/**
 * Minds Push Notifications
 */
 
namespace minds\plugin\notifications;

use Minds\Core;
use Surge;

class Push {
    
    public static function send($message = array(), $token){
        $config = new Surge\Config(array(
            'Apple' => array(
                'cert'=> '/var/secure/private/apns.pem',
                'sandbox'=>true
            ),
            'Google' => array(
                'api_key' => 'AIzaSyCp0LVJLY7SzTlxPqVn2-2zWZXQKb1MscQ'
            )));
            
        $message = Surge\Messages\Factory::build($token)
            ->setTitle($message['title'])
            ->setMessage($message['message']);
            
        Surge\Surge::send($message, $config);
    }

}