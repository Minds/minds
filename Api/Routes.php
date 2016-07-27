<?php
namespace Minds\Api;

/**
 * API Router
 */
class Routes
{
    public static $routes = array();

    /**
     * Adds a custom API route resolution
     * @param string $route - the route, eg. v1/newsfeed
     * @param string $class - the route of the class
     * @return void
     */
    public function add($route, $class)
    {
        self::$routes[$route] = $class;
    }
}
