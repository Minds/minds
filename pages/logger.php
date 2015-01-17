<?php
/**
 * Notifications page handler
 */
namespace minds\pages;

use Minds\Core;
use minds\interfaces;

class logger extends core\page implements interfaces\page{
	
	/**
	 * Get requests
	 */
	public function get($pages){
		core\logger::get();
	}
	

	public function post($pages){}
	
	public function put($pages){}
	
	public function delete($pages){}
	
}
