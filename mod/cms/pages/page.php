<?php

namespace minds\plugin\cms\pages;

use minds\core;
use minds\interfaces;
//use minds\plugin\comments;
use minds\plugin\bitcoin\entities;

class page extends core\page implements interfaces\page{
	
	public $context = 'cms';
	
	/**
	 * Get requests
	 */
	public function get($pages){
		
		echo 1; exit;
		
		echo $this->render(array('body'=>$body));
		
	}
	
	/**
	 * Post comments
	 */
	public function post($pages){}
	
	public function put($pages){}
	
	public function delete($pages){}
	
}
    
