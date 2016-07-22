<?php
/**
 * Cli Router
 */

namespace Minds\CLi;

class Routes
{
    public static $routes = array();

    /**
     * Add to router
     * @param string $route - the route, eg. v1/banners
     * @param string $class - the route of the class
     * @return void
     */
    public function add($route, $class)
    {
        self::$routes[$route] = $class;
    }
}
