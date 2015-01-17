<?php
/**
 * Notifications page handler
 */
namespace minds\plugin\notifications\pages;

use Minds\Core;
use minds\interfaces;

class count extends core\page implements interfaces\page{
	
	public function get($pages){

		$num_notifications = \minds\plugin\notifications\start::getCount(false);
		echo $num_notifications;
	}
			
	public function post($pages){}
	
	public function put($pages){}
	
	public function delete($pages){}
}