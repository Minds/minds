<?php
/**
 * WP page handler
 */
namespace minds\plugin\wp\pages;

use Minds\Core;
use minds\interfaces;
use minds\plugin\minds_wordpress;

class wp extends core\page implements Interfaces\page{
	
	
	public function get($pages){
		
		switch($pages[0]){
			case 'logout':
				//return minds_wordpress::loggedoutHook();
			break;
		}
	}
	
	public function post($pages){}
	public function put($pages){}
	public function delete($pages){}
	
}
