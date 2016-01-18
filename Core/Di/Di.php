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
    public function get($alias)
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
    public function bind($alias, \Closure $function, array $options = [])
    {
        $options = array_merge([
          'useFactory' => false,
          'immutable' => false
        ], $options);

        if($options['immutable'] && isset($this->bindings[$alias])){
            throw new ImmutableException();
        }

        $binding = (new Binding())
          ->setFunction($function)
          ->setFactory($options['useFactory'])
          ->setImmutable($options['immutable']);
        $this->bindings[$alias] = $binding;
    }

    /**
     * Singleton loader
     * @return Di
     */
    static public function _()
    {
        if(!self::$_){
            self::$_ = new Di;
        }
        return self::$_;
    }

}
