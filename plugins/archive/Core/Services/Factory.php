<?php
/**
 * A simple factory
 */

namespace Minds\Plugin\Archive\Core\Services;

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
        $class = "Minds\\Plugin\\Archive\\Core\\Services\\$service";
        if (class_exists($class)) {
            return new $class;
        }

        throw new \Exception("Service `$service` not found");
    }

}
