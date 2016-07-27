<?php
namespace Minds\Cli;

use Minds\Interfaces;
use Minds\Helpers;
use Minds\Core\Security;

/**
 * CLI Factory
 */
class Factory
{
    /**
     * Returns a Cli\Controller instance for the passed $segments,
     * or null if the class is not found.
     * @param  string $segments
     * @return mixed|null
     */
    public static function build($segments)
    {
        $route = implode('\\', $segments);
        $loop = count($segments);
        while ($loop >= 0) {
            $offset = $loop -1;
            if ($loop < count($segments)) {
                $slug_length = strlen($segments[$offset+1].'\\');
                $route_length = strlen($route);
                $route = substr($route, 0, $route_length-$slug_length);
            }

            //Literal routes
            $actual = str_replace('\\', '/', static::toCamelNsp($route));
            if (isset(Routes::$routes[$actual])) {
                $class_name = Routes::$routes[$actual];
                if (class_exists($class_name)) {
                    $handler = new $class_name();
                    $handler->setArgs(array_splice($segments, $loop) ?: []);
                    return $handler;
                }
            }

            //autloaded routes
            $class_name = '\\Minds\\Controllers\\Cli\\' . static::toCamelNsp($route);
            if (class_exists($class_name)) {
                $handler = new $class_name();
                $handler->setArgs(array_splice($segments, $loop) ?: []);
                return $handler;
            }
            --$loop;
        }

        return false;
    }

    /**
     * Camelizes namespaces and paths (from underscore notation)
     * @todo Create an Inflector class and use this on other routers (e.g. /api)
     * @param  string $namespace [description]
     * @return string
     */
    public static function toCamelNsp($namespace)
    {
        $namespace = explode('\\', $namespace);
        $replacer = function($matches) {
            return strtoupper($matches[1]);
        };

        array_walk($namespace, function(&$segment) use ($replacer) {
            $segment = ucfirst(preg_replace_callback('/_([a-z])/', $replacer, $segment));
        });

        return implode('\\', $namespace);
    }
}
