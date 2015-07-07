<?php
/**
 * Minds main page controller
 */
namespace minds\pages;

use Minds\Core;
use minds\interfaces;

class app extends core\page implements interfaces\page{

	/**
	 * Get requests
	 */
	public function get($pages){
	    $this->forward('/');
    }
	
	public function post($pages){
	}
	
	public function put($pages){
	}
	
	public function delete($pages){
	}
	
}
