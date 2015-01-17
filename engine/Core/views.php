<?php
/**
 * for now, a clean wrapper around the old elgg view system, with the added benefit of static caching of view.
 */
namespace Minds\Core;

class views extends base{
	
	static public $cachables = array();
	
	public function init(){
		
	}
	
	/**
	 * Load a view
	 */
	static public function view($view, $params = array()){

		global $CONFIG;
	
		if(in_array($view, self::$cachables)){
			$path = $CONFIG->system_cache_path;
			if(!is_dir($path))
				mkdir($path, 0700, true);
			$path .= md5($view);
			
			if(file_exists($path)){
				return file_get_contents($path);
			} else {

				ob_start();
				
				echo \elgg_view($view, $params);
				
				$content = ob_get_contents();
				ob_end_clean();
				
				file_put_contents($path, $content);
			}
		}
		
		return \elgg_view($view, $params);
	}
	
	/**
	 * Adds a view to the cache
	 */
	 static public function cache($view){
	 	array_push(self::$cachables, $view);
	 }
	 
}
