<?php
/**
 * Minds Data Client Factory
 */
namespace Minds\Core\data;

class Client{
    
    private static $default = '\Minds\Core\Data\cassandra\client';

    /**
     * Build the client
     * @param string $table - Table controller
     * @param string $handler - The handler to load
     * @return object
     */
    public static function build($handler = null, $options = array()){

        if(!$handler)
            $handler = self::$default;
        
        
        if(substr($handler, 0, 1) != "\\")
            $handler = "\\Minds\\Core\\Data\\$handler\\Client";

        if(class_exists($handler)){
            return new $handler($options);
        } else {
            throw new \Exception("Factory not found");
        }
    }   
}
    