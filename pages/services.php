<?php
/**
 * Minds service pages
 */
namespace minds\pages;

use minds\core;
use minds\interfaces;

class services extends core\page implements interfaces\page{
	
	public function service($pages){
		array_shift($pages);
		$handler = array_shift($pages); 
		$request = implode('/', $pages); 
		service_handler($handler, $request);
	}

	/**
	 * Get requests
	 */
	public function get($pages){
		$this->service($pages);
	}
	
	public function post($pages){
		$this->service($pages);
	}
	
	public function put($pages){
		$this->service($pages);
	}
	
	public function delete($pages){
		$this->service($pages);
	}
	
}
