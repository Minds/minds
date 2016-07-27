<?php
namespace Minds\Cli;

/**
 * CLI Router
 */
class Routes
{
    public static $routes = array();

    /**
     * Adds a custom CLI route resolution
     * @param string $route - the route, eg. multi/admin/users
     * @param string $class - the route of the class
     * @return void
     */
    public function add($route, $class)
    {
        self::$routes[$route] = $class;
    }
}
