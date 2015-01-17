<?php
/**
 * Minder
 */
namespace minds\plugin\minder;

use Minds\Components;
use Minds\Core\router;

class start extends Components\Plugin{
	
	public function __construct(){
		parent::__construct('minder');	

		$this->init();
	}
	
	public function init(){
			$routes = router::registerRoutes($this->registerRoutes());
			
			//lists
			$queue = array();
			$up = array();
			$down = array();
	}
	
	/**
	 * Handler the pages
	 * 
	 * @param array $pages - the page slugs
	 * @return bool
	 */
	public function registerRoutes(){
		$path = "minds\\plugin\\minder";
		return array(
			'/minder' => "$path\\pages\\main"
		);
	}
	
	public function queue($user){
		$db = \Minds\Core\data\lookup('minder:queue');
		$guids = $db->get($user->guid);
		if(count($guids) < 10){
			//the user doesn't have enough 'up votes' yet, so grab some random people from the site
			$db->set($user->guid, array(''));
		}
	}
	
	
}
