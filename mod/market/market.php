<?php
/**
 * Minds Market
 */
 
namespace minds\plugin\market;

use minds\core;

class market extends \ElggPlugin{
	
	public function __construct(){
		parent::__construct('market');
		
		$this->init();
	}
	
	public function init(){
		//\elgg_register_page_handler('market', array($this, 'pageHandler'));
		$routes = core\router::registerRoutes($this->registerRoutes());
	}
	
	/**
	 * Handler the pages
	 * 
	 * @param array $pages - the page slugs
	 * @return bool
	 */
	public function registerRoutes(){
		$path = "minds\\plugin\\market";
		return array(
			'/market' => "$path\\pages\\lists",
			'/market/owner' => "$path\\pages\\lists",
			'/market/item' => "$path\\pages\\view",
			'/market/item/{{ID}}/edit' => "$path\\pages\\edit"
		);
	}
	
	
}
