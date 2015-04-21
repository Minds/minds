<?php
namespace Minds\Core\Queue\Runners;
use Minds\Core\Queue\Interfaces;
use Minds\Core\Queue;

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
                   var_dump($data->getData());
                   echo "\n\n";
               });
   }   
           
}   