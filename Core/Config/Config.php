<?php
/**
 * Minds Config manager
 *
 * @todo - move out events, hooks and views from config
 * @todo - make this not an array access but simple 1 param
 * @todo - make so we don't have a global $CONFIG.
 */
namespace Minds\Core\Config;

class Config
{
    public static $_;
    private $config = array();

    public function __construct()
    {
        $this->init();
    }

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

    public function get($key)
    {
        if (isset($this->config[$key])) {
            return $this->config[$key];
        }
        return null;
    }

    public function set($key, $value)
    {
        $this->config[$key] = $value;
    }

    public function __get($key)
    {
        return $this->get($key);
    }

    public function __set($key, $value)
    {
        $this->set($key, $value);
        $this->$key = $value; //nasty fallback;
    }
}
