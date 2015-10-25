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

	public function __construct(){
		$this->init();
	}

	public function init(){
		//$this->lastcache = 0;
		$this->set('icon_sizes', array(
			'topbar' => array('w'=>16, 'h'=>16, 'square'=>TRUE, 'upscale'=>TRUE),
			'tiny' => array('w'=>25, 'h'=>25, 'square'=>TRUE, 'upscale'=>TRUE),
			'small' => array('w'=>40, 'h'=>40, 'square'=>TRUE, 'upscale'=>TRUE),
			'medium' => array('w'=>100, 'h'=>100, 'square'=>TRUE, 'upscale'=>TRUE),
			'large' => array('w'=>425, 'h'=>425, 'square'=>FALSE, 'upscale'=>FALSE),
			//'xlarge'=> array('w'=>400, 'h'=>400, 'square'=>false, 'upscale'=>false),
			'master' => array('w'=>550, 'h'=>550, 'square'=>FALSE, 'upscale'=>FALSE)
		));
		$this->set('minusername', 2);
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

	static public function _(){
		if(!self::$_)
			self::$_ = new Config();
		return self::$_;
	}

}
