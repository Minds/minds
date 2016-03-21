<?php
/**
* Thrid Party Social Network Factory
*/
namespace Minds\Core\ThirdPartyNetworks;

class Factory
{
    /**
     * Build the handler
     * @param string $handler
     * @param array $options (optional)
     * @return BoostHandlerInterface
     */
    public static function build($handler, $options = array())
    {
        $handler = ucfirst($handler);
        $handler = "Minds\\Core\\ThirdPartyNetworks\\$handler";
        if (class_exists($handler)) {
            $class = new $handler($options);
            if ($class instanceof NetworkInterface) {
                return $class;
            }
        }
        throw new \Exception("Social Network not found");
    }
}
