<?php
/**
 * SEO Manager
 */
namespace Minds\Core\SEO;

class Manager {

  static $routes = array();
  static $defaults = array();

  /**
   * Add a callback to provide metadata
   * @param string $route
   * @param function $callback
   */
  static public function add($route, $callback){
    self::$routes[$route] = $callback;
  }

  /**
   * Set default metadata
   * @param array $meta
   * @return void
   */
  static public function setDefaults($meta){
    self::$defaults = array_merge(self::$defaults, $meta);
  }

  /**
   * Return metadata for given route
   * @param string $route (optional)
   * @return array
   */
  static public function get($route = NULL){
    if(!$route) //detect route
      $route = rtrim(strtok($_SERVER["REQUEST_URI"],'?'), '/');

    $slugs = array();
    $meta = array();

    while($route){
      if(isset(self::$routes[$route])){
        $meta = call_user_func_array(self::$routes[$route], array(array_reverse($slugs)));
        break;
      } else {
        $slugs[] = substr($route, strrpos($route,'/')+1);
        $route = substr($route, 0, strrpos($route,'/'));
      }
    }

    return array_merge(self::$defaults, $meta);

  }

}
