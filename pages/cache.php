<?php
/**
 * Minds main page controller
 */
namespace minds\pages;

use minds\core;
use minds\interfaces;

class cache extends core\page implements interfaces\page{
	
	/**
	 * Get requests
	 */
	public function get($pages){
		global $CONFIG;
		$dataroot = $CONFIG->dataroot;
		$simplecache_enabled = $CONFIG->simplecache_enabled;

		$dirty_request = $pages;

		$type = $pages[0];
		$viewtype = $pages[1];
		$split = explode('.',$pages[2]);
		$view = $split[0];
		$ts = $split[1];
	
		// If is the same ETag, content didn't changed.
		$etag = $ts;
		if (isset($_SERVER['HTTP_IF_NONE_MATCH']) && trim($_SERVER['HTTP_IF_NONE_MATCH']) == "\"$etag\"") {
			header("HTTP/1.1 304 Not Modified");
			exit;
		}

		switch ($type) {
			case 'css':
				header("Content-type: text/css", true);
				$view = "css/$view";
				break;
			case 'js':
				header('Content-type: text/javascript', true);
				$view = "js/$view";
				break;
		}
		
		$filename = $dataroot . 'views_simplecache/' . md5($viewtype . $view);
		
		if (file_exists($filename)) {
			$contents = file_get_contents($filename);
		} else {
			
			//if (!isset($CONFIG->views->simplecache[$view])) {
		//		header("HTTP/1.1 404 Not Found");
			//	exit;
		//	}
			
			\elgg_set_viewtype($viewtype);
			$contents = \elgg_view($view);
		}
		
		header('Expires: ' . date('r', strtotime("+6 months")), true);
		header("Pragma: public", true);
		header("Cache-Control: public", true);
		header("ETag: $etag");

		echo $contents;

	}
	
	public function post($pages){
	}
	
	public function put($pages){
	}
	
	public function delete($pages){
	}
	
}
