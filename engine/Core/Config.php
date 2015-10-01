<?php
/**
 * Minds Config manager
 *
 * @todo - move out events, hooks and views from config
 * @todo - make this not an array access but simple 1 param
 * @todo - make so we don't have a global $CONFIG.
 */
namespace Minds\Core;

class Config{

	static public $_;
	private $config = array();

	public function init(){
		//$this->lastcache = 0;
	}

	public function get($key){
		if(isset($this->config[$key]))
			return $this->config[$key];
		return null;
	}

	public function set($key, $value){
		$this->config[$key] = $value;
	}

	/**
	 * Build the configuration
	 */
	static public function build(){
		if(!self::$_)
			self::$_ = new Config();
		return self::$_;
	}
}
