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
			'/market/item' => "$path\\pages\\view",
			'/market/item/edit' => "$path\\pages\\edit"
		);
	}
	
	/**
	 * Categories for market items
	 * 
	 * Categories are seperated by a ":" colon. 
	 * @return array
	 */
	static public function getCategories(){
		return array(
			'uncategorised',
			'food',
			'food:chocolate'
		);
	}
	
}
