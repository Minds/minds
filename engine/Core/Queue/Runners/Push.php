<?php
namespace Minds\Core\Queue\Runners;
use Minds\Core\Queue\Interfaces;
use Minds\Core\Queue;
use Minds\entities\user;
use Surge;

/**
 * Push notifications runner
 */

class Push implements Interfaces\QueueRunner{
    
   public function run(){
       $client = Queue\Client::Build();
       $client->setExchange("mindsqueue", "direct")
               ->setQueue("Push")
               ->receive(function($data){
                   echo "Received a push notification";
                   
                   $data = $data->getData();
                   
                   $config = new Surge\Config(array(
                        'Apple' => array(
                            'cert'=> '/var/secure/apns-production.pem'
                         //   'sandbox'=>true,
                         //   'cert'=> '/var/secure/apns.pem'
                        ),
                        'Google' => array(
                            'api_key' => 'AIzaSyCp0LVJLY7SzTlxPqVn2-2zWZXQKb1MscQ'
                        )));
                        
                    $user = new user($data['user_guid'], false);
                    
                    if(!$user->surge_token){
                        echo "$user->username hasn't configured push yet.. not sending \n";
                        return false;
                    }
                 
                    $message = Surge\Messages\Factory::build($user->surge_token)
                        ->setTitle($data['message'])
                        ->setMessage($data['message'])
                        ->setURI(isset($data['uri']) ? $data['uri'] : 'chat');
                        
                    Surge\Surge::send($message, $config);

                    echo "sent a push notification to $user->guid \n";
               });
   }   
           
}   
