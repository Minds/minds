<?php
/**
 * Minder
 */
namespace minds\plugin\minder;

use minds\core;

\elgg_register_event_handler('init', 'system', function(){
	new start();
});


class start extends \ElggPlugin{
	
	public function __construct(){
		parent::__construct('minder');	

		$this->init();
	}
	
	public function init(){
		
	}
	
}
