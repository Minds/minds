<?php
/**
 * Minds main page controller
 */
namespace minds\pages;

use minds\core;
use minds\interfaces;

class actions extends core\page implements interfaces\page{
	
	public function action($pages){
		$action = implode('/', $pages);
                action($action);
	}

	/**
	 * Get requests
	 */
	public function get($pages){
		$this->action($pages);
	}
	
	public function post($pages){
		$this->action($pages);
	}
	
	public function put($pages){
		$this->action($pages);
	}
	
	public function delete($pages){
		$this->action($pages);
	}
	
}
