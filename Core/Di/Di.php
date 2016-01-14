<?php
/**
 * Minds DI (Dependency Injector)
 * @author Mark Harding (mark@minds.com)
 */
namespace Minds\Core\Di;

class Di
{

    static private $_;
    private $bindings = [];
    private $factories = [];

    /**
     * Return the binding for an alias
     * @param string $alias
     * @return mixed
     */
    public function get(string $alias)
    {
        if(isset($this->bindings[$alias])){
            $binding = $this->bindings[$alias];
            if($binding->isFactory()){
                if(!isset($this->factories[$alias])){
                    $this->factories[$alias] = call_user_func($binding->getFunction(), $this);
                }
                return $this->factories[$alias];
            } else {
                return call_user_func($binding->getFunction(), $this);
            }
        }
        return false;
    }

    /**
     * Bind an object to an alias
     * @param string $alias
     * @param Closure $function
     * @param array $options
     * @return void
     */
    public function bind(string $alias, \Closure $function, array $options = [])
    {
        $options = array_merge([
          'useFactory' => false,
          'immutable' => false
        ], $options);
        $binding = (new DiBinding())
          ->setFunction($function)
          ->setFactory($options['useFactory'])
          ->setImmutable($options['immutable']);
        $this->bindings[$alias] = $binding;
    }

    /**
     * Singleton loader
     * @return Di
     */
    static public function _() : Di
    {
        if(!self::$_){
            self::$_ = new Di;
        }
        return self::$_;
    }

}
