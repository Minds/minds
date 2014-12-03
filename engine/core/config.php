<?php
/**
 * Minds Config manager
 * 
 * @todo - move out events, hooks and views from config
 * @todo - make this not an array access but simple 1 param
 * @todo - make so we don't have a global $CONFIG. 
 */
namespace minds\core;

class config extends base implements \ArrayAccess{
	
	static public $config = array();
	
	public function init(){
		//$this->lastcache = 0;
	}
	
	public function get(){
	}
	
	public function offsetSet($offset, $value) {
        if (is_null($offset)) {
            self::$config[] = $value;
        } else {
            self::$config[$offset] = $value;
        }
    }
	
    public function offsetExists($offset) {
        return isset(self::$config[$offset]);
    }
	
    public function offsetUnset($offset) {
        unset(self::$config[$offset]);
    }
	
    public function offsetGet($offset) {
        return isset(self::$config[$offset]) ? self::$config[$offset] : null;
    }
}
