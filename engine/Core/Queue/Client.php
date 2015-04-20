<?php
namespace Minds\Core\Queue;

/**
 * Messaging queue
 */

class Client{
    
    /**
     * Build the client
     */
     public static function build($client = "RabbitMQ", $options = array()){
      
        if(substr($client, 0, 1) != "\\")
            $client = "\\Minds\\Core\\Queue\\$client\\Client";

        if(class_exists($client)){
            $class = new $client($options);
            if($class instanceof Interfaces\QueueClient)
                return $class;
            
            throw new \Exception("Queue factory is not of Interface QueueClient");
        } else {
            throw new \Exception("Factory not found");
        }
         
     }
           
}   