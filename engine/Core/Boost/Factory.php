<?php
namespace Minds\Core\Boost;
use Minds\Core\Data;
use Minds\Interfaces;

/**
 * A factory providing handlers boosting items
 */
class Factory{
	
    /**
     * Build the handler
     * @param string $handler
     * @param array $options (optional)
     * @return BoostHandlerInterface
     */
    public static function build($handler, $options = array(), $db = NULL){
        $handler = ucfirst($handler);
        $handler = "Minds\\Core\\Boost\\$handler";
        if(class_exists($handler)){
            $class = new $handler($options, $db);
            if($class instanceof Interfaces\BoostHandlerInterface){
                return $class;
            }
        }
        throw new \Exception("Handler not found");
    }
    
}
