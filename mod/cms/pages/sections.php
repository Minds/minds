<?php

namespace minds\plugin\cms\pages;

use minds\core;
use minds\interfaces;
use minds\plugin\cms\entities;

class sections extends core\page implements interfaces\page{
	
	public $context = 'cms';
	
	/**
	 * Get requests
	 */
	public function get($pages){}

	public function post($pages){
		
		$section = new entities\section();
		$section->group = $pages[0];
		
		$guid = $section->save();
		
		echo $guid;
		
	}
	
	public function put($pages){}
	
	public function delete($pages){}
	
}
    
