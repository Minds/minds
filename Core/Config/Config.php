<?php
namespace Minds\Core\Config;

/**
 * Minds Config manager
 *
 * @todo - move out events, hooks and views from config
 * @todo - make this not an array access but simple 1 param
 * @todo - make so we don't have a global $CONFIG.
 */
class Config
{
    public static $_;
    private $config = array();

    public function __construct()
    {
        $this->init();
    }

    /**
     * Initializes Config. Adds default configurations.
     * @return null
     */
    public function init()
    {
        //$this->lastcache = 0;
        $this->config['icon_sizes'] = [
            'topbar' => array('w'=>16, 'h'=>16, 'square'=>true, 'upscale'=>true),
            'tiny' => array('w'=>25, 'h'=>25, 'square'=>true, 'upscale'=>true),
            'small' => array('w'=>40, 'h'=>40, 'square'=>true, 'upscale'=>true),
            'medium' => array('w'=>100, 'h'=>100, 'square'=>true, 'upscale'=>true),
            'large' => array('w'=>425, 'h'=>425, 'square'=>false, 'upscale'=>false),
            //'xlarge'=> array('w'=>400, 'h'=>400, 'square'=>false, 'upscale'=>false),
            'master' => array('w'=>550, 'h'=>550, 'square'=>false, 'upscale'=>false)
        ];
        $this->config['minusername'] = 2;
    }

    /**
     * Gets a config value
     * @param  string $key
     * @return mixed
     */
    public function get($key)
    {
        if (isset($this->config[$key])) {
            return $this->config[$key];
        }
        return null;
    }

    /**
     * Sets a config value
     * @param  string $key
     * @param  mixed  $value
     * @param  array  $opts   Optional.
     * @return null
     */
    public function set($key, $value = null, $opts = [])
    {

        //legacy nasty fallback
        if(property_exists($this, $key)){
            $this->$key = $value;
        }

        $opts = array_merge([ 'recursive' => false ], $opts);
        if($value && is_array($value) && isset($this->config[$key]) && is_array($this->config[$key]) && $opts['recursive']){
            $this->config[$key] = array_merge($this->config[$key], $value);
            return;
        }
        $this->config[$key] = $value;
    }

    /**
     * Magic Method to get a value
     */
    public function __get($key)
    {
        return $this->get($key);
    }

    /**
     * Magic method to set a value.
     * @todo Deprecate.
     */
    public function __set($key, $value)
    {
        $this->set($key, $value);
        $this->$key = $value; //nasty fallback;
    }
}
