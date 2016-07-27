<?php
namespace Minds\Fs;

/**
 * FS Router
 */
class Routes
{
    public static $routes = array();

    /**
     * Adds a custom FS route resolution
     * @param string $route - the route, eg. v1/banners
     * @param string $class - the route of the class
     * @return void
     */
    public function add($route, $class)
    {
        self::$routes[$route] = $class;
    }
}
