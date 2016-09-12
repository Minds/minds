<?php
/**
 * A simple factory
 */

namespace Minds\plugin\archive\Core\Services;

class Factory
{

    /**
     * Build the service factory
     * @param string $service
     * @return ServiceInterface
     * @throws Exception - "Service not found"
     */
    public static function build($service)
    {
        $service = ucfirst($service);
        $class = "Minds\\plugin\\archive\\Core\\Services\\$service";
        if (class_exists($class)) {
            return new $class;
        }

        throw new \Exception("Service `$service` not found");
    }

}
