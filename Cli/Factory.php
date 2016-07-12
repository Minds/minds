<?php

namespace Minds\Cli;

use Minds\Interfaces;
use Minds\Helpers;
use Minds\Core\Security;

/**
 * The Minds Cli factory
  */
class Factory
{
    /**
     * Builds the Cli controller
     * This is almost like an autoloader
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
            $actual = str_replace('\\', '/', $route);
            if (isset(Routes::$routes[$actual])) {
                $class_name = Routes::$routes[$actual];
                if (class_exists($class_name)) {
                    $handler = new $class_name();
                    $pages = array_splice($segments, $loop) ?: array();
                    return $handler->exec($pages);
                }
            }

            //autloaded routes
            $class_name = "\\Minds\\Controllers\\cli\\$route";
            if (class_exists($class_name)) {
                $handler = new $class_name();
                $pages = array_splice($segments, $loop) ?: array();
                return $handler->exec($pages);
            }
            --$loop;
        }
    }

    public static function getOpts(array $opts = [], $argv = [])
    {
        $return = array_flip($opts);
        foreach($opts as $opt){
            $pos = array_search("--$opt", $argv);
            if($pos !== FALSE){
                $return[$opt] = $argv[$pos+1];
            }
        }
        return $return;
    }

}
