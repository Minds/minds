<?php
/**
 * SEO Manager
 */
namespace Minds\Core\SEO;

class Manager
{
    public static $routes = array();
    public static $defaults = array(
    'title' => ''
  );

  /**
   * Add a callback to provide metadata
   * @param string $route
   * @param function $callback
   */
  public static function add($route, $callback)
  {
      self::$routes[$route] = $callback;
  }

  /**
   * Set default metadata
   * @param array $meta
   * @return void
   */
  public static function setDefaults($meta)
  {
      self::$defaults = array_merge(self::$defaults, $meta);
  }

  /**
   * Return metadata for given route
   * @param string $route (optional)
   * @return array
   */
  public static function get($route = null)
  {
      if (!$route) { //detect route
      $route = rtrim(strtok($_SERVER["REQUEST_URI"], '?'), '/');
      }

      $slugs = array();
      $meta = array();

      while ($route) {
          if (isset(self::$routes[$route])) {
              $meta = call_user_func_array(self::$routes[$route], array(array_reverse($slugs)));
              break;
          } else {
              $slugs[] = substr($route, strrpos($route, '/')+1);
              if (strrpos($route, '/') === 0) {
                  $route = '/';
              } else {
                  $route = substr($route, 0, strrpos($route, '/'));
              }
          }
      }

      return array_merge(self::$defaults, $meta);
  }
}
