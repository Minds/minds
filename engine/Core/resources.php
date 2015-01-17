<?php
/**
 * Resource (css/js) manager
 */
namespace Minds\Core;

class resources extends base{
	
	//LOCATION => TYPE => NAME => SRC => PRIORITY
	private static $resources = array(
		'header'=>array(),
		'foot'=>array()
	);
	
	private static $loadedResources = array(
		'header' => array(),
		'foot' => array()
	);
	
	/**
	 * Register a resource
	 * 
	 * @param $name The name of the resource
	 * @return void
	 */
	public function register($name, $src, $type = 'css', $location = 'header', $priority = 500){
		if(!$src || !$name)
			return false;
		
		//$priority = self::findPriority($location, $type, $priority);
		self::$resources[$location][$type][$name] = array('src'=>$src, 'priority'=>$priority);
	}
	
	/**
	 * Register a view as a resource
	 */
	public function registerView($name, $view, $type = 'css', $location = 'header', $priority = 500){
		$src = \elgg_get_simplecache_url($type, $view);
		self::register($name, $src, $type, $location, $priority);
	}
	
	public function unRegister($name, $type = 'css'){
		foreach(self::$resources as $location => $types){
			if(isset($types[$type])){
				if(isset(self::$resources[$location][$type][$name]))
					unset(self::$resources[$location][$type][$name]);
			}
		}
	}
	 
	 /**
	  * Load a resource. This places it into the queue which the views system will make use of
	  * 
	  * @param $name
	  * @param $type
	  * @return void
	  */
	public function load($name, $type = 'css'){
		foreach(self::$resources as $location => $types){
			if(isset($types[$type])){
				if(isset(self::$resources[$location][$type][$name]))
					self::$loadedResources[$location][$type][$name] = self::$resources[$location][$type][$name];
			}
		}
	}
	
	public function unLoad($name, $type = 'css'){
		foreach(self::$loadedResources as $location => $types){
			if(isset($types[$type])){
				if(isset(self::$loadedResources[$location][$type][$name]))
					unset(self::$loadedResources[$location][$type][$name]);
			}
		}
	}
	
	/**
	 * Get loaded resources
	 */
	public function getLoaded($type = 'css', $location = 'header'){
		if(isset(self::$loadedResources[$location][$type])){

			//sort by priority
			usort(self::$loadedResources[$location][$type], function($a, $b){
					return $a['priority'] - $b['priority'];
			});
			
			return self::$loadedResources[$location][$type];
		}
		return false;
	}

}
