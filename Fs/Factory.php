<?php

namespace Minds\Fs;
use Minds\Interfaces;
use Minds\Helpers;
use Minds\Core\Security;

/**
 * The Minds Fs factory
  */
class Factory {

    /**
     * Builds the FS controller
     * This is almost like an autoloader
     */
    public static function build($segments){

        $method = strtolower($_SERVER['REQUEST_METHOD']);

        $route = implode('\\',$segments);
        $loop = count($segments);
        while($loop >= 0){

            $offset = $loop -1;
            if($loop < count($segments)){
                $slug_length = strlen($segments[$offset+1].'\\');
                $route_length = strlen($route);
                $route = substr($route, 0, $route_length-$slug_length);
            }

            //Literal routes
            $actual = str_replace('\\', '/', $route);
            if(isset(Routes::$routes[$actual])){
                $class_name = Routes::$routes[$actual];
                if(class_exists($class_name)){
                    $handler = new $class_name();
                    self::pamCheck();
                    $pages = array_splice($segments, $loop) ?: array();
                    return $handler->$method($pages);
                }
            }

            //autloaded routes
            $class_name = "\\Minds\\Controllers\\fs\\$route";
            if(class_exists($class_name)){
                $handler = new $class_name();
                self::pamCheck();
                $pages = array_splice($segments, $loop) ?: array();
                return $handler->$method($pages);

            }
            --$loop;
        }
    }

    /**
     * PAM checker
     * @todo list to tokens for private pieces of data
     */
    public static function pamCheck(){
	     return true;
    }

}
