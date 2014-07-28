<?php
/**
 * A base object for plugins
 * 
 * 
 * @todo this is a work in progress and will replace the ElggPlugin object
 */
 
namespace minds\bases;

class plugin extends \ElggPlugin{
	
	public function start($flags = null){
		//only legacy plugins use the start function
		$this->registerViews();
	}
	
	public function init(){
		
	}
	
}
