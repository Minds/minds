<?php
/**
* Payments Factory
*/
namespace Minds\Core\Payments;

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
    public static function build($handler, $options = array()){
        $handler = ucfirst($handler);
        $handler = "Minds\\Core\\Payments\\$handler\\$handler";
        if(class_exists($handler)){
            $class = new $handler($options);
            if($class instanceof PaymentServiceInterface){
                return $class;
            }
        }
        throw new \Exception("Payment service not found");
    }

}
