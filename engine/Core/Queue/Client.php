<?php
namespace Minds\Core\Queue;

/**
 * Messaging queue
 */

class Client{
    
    /**
     * Build the client
     */
     public function build($client = "RabbitMQ", $options = array()){
      
        if(substr($handler, 0, 1) != "\\")
            $handler = "\\Minds\\Core\\Queue\\$handler\\Client";

        if(class_exists($handler)){
            return new $handler($options);
        } else {
            throw new \Exception("Factory not found");
        }
         
     }
           
}   