<?php
namespace Minds\Core\Queue;

/**
 * Messaging queue
 */

class Client{

    private static $clients = array();

    /**
     * Build the client
     */
     public static function build($client = "RabbitMQ", $options = array()){

        if(substr($client, 0, 1) != "\\")
            $client = "\\Minds\\Core\\Queue\\$client\\Client";

        //@todo be able to cache with different $options variables
        if(isset(self::$clients[$client]))
            return self::$clients[$client];

        if(class_exists($client)){
            $class = new $client($options);
            if($class instanceof Interfaces\QueueClient){
                self::$clients[$client] = $class;
                return $class;
            }

            throw new \Exception("Queue factory is not of Interface QueueClient");
        } else {
            throw new \Exception("Factory not found");
        }

     }

}
