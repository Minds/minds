<?php
/**
 * Minds cluster subscriptions
 */
namespace minds\pages\clusters;

use minds\core;
use minds\interfaces;
use minds\entities;

class subscriptions extends core\page implements interfaces\page{
	
	public function get($pages){
		
	}
	
	public function post($pages){
		
	}
	
	public function put($pages){
		throw new \Exception('Sorry, the put method is not supported for the page');
	}
	
	public function delete($pages){
		throw new \Exception('Sorry, the delete method is not supported for the page');
	}
	
}
