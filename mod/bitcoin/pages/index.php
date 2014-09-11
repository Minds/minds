<?php

namespace minds\plugin\bitcoin\pages;

use minds\core;
use minds\interfaces;
//use minds\plugin\comments;
use minds\plugin\bitcoin\entities;

class index extends core\page implements interfaces\page{
	
	/**
	 * Get requests
	 */
	public function get($pages){
		$this->forward('bitcoin/wallet');
	}
	
	public function post($pages){}
	
	public function put($pages){}
	
	public function delete($pages){}
	
}
    