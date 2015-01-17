<?php
/**
 * Minds Data Client Factory
 */
namespace minds\core\data;

class Client{
    
    private static $default = '\minds\core\data\cassandra\client';

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
    