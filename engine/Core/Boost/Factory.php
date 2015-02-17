<?php
namespace Minds\Core\Boost;
use Minds\Core\Data;
use minds\interfaces;

/**
 * Provide core functionality for boosting items
 */
class Factory{
	
    /**
     * Build the handler
     * @param string $handler
     * @param array $options (optional)
     * @return BoostHandlerInterface
     */
    public static function build($handler, $options = array()){
        if(class_exists($handler)){
            $class = new $handler($options);
            if($class instanceof interfaces\BoostHandlerInterface){
                return $class;
            }
        }
        throw new \Exception("Handler not found");
    }
    
}
