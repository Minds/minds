<?php
/**
 * Minds main page controller
 */
namespace minds\pages;

use Minds\Core;
use minds\interfaces;

class index extends core\page implements interfaces\page{
	
	/**
	 * Get requests
	 */
	public function get($pages){
		include(dirname(dirname(__FILE__)) . '/ui/index.php');
	}
	
	public function post($pages){
		echo 'this is a post request';
	}
	
	public function put($pages){
		echo 'this is a put request';
	}
	
	public function delete($pages){
		echo 'this is a delete request';
	}
	
}
