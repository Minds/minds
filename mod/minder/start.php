<?php
/**
 * Minder
 */
namespace minds\plugin\minder;

use minds\core\router;

\elgg_register_event_handler('init', 'system', function(){
	new start();
});


class start extends \ElggPlugin{
	
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
		$db = \minds\core\data\lookup('minder:queue');
		$guids = $db->get($user->guid);
		if(count($guids) < 10){
			//the user doesn't have enough 'up votes' yet, so grab some random people from the site
			$db->set($user->guid, array(''));
		}
	}
	
	/**
	 * Returns the guids of the channels a user has up voted
	 */
	public function ups($user){
		$db = \minds\core\data\indexes('minder:ups');
		$guids = $db->get($user->guid);
	}
	
	/**
	 * Return the guids of the channels a user has down voted
	 */
	public function downs($user){
		$db = \minds\core\data\indexes('minder:downs');
		$guids = $db->get($user->guid);
	}
	
	/**
	 * Returns a list of mutuals
	 */
	public function mutuals($user){
		
	}
}
