<?php
namespace Minds\Core\Queue\Runners;

/**
 * Queue runner factory
 */

class Factory{
    
    /**
     * Build the client
     */
     public static function build($runner, $client = "RabbitMQ"){
      
        if(substr($runner, 0, 1) != "\\")
            $runner = "\\Minds\\Core\\Queue\\Runners\\$runner";

        if(class_exists($runner)){
            $class = new $runner();
            //if($class instanceof Interfaces\QueueRunner)
                return $class;
            
            throw new \Exception("Queue runner is not of Interface QueueRunner");
        } else {
            throw new \Exception("Runner not found");
        }
         
     }
     
     
           
}   