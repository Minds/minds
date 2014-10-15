<?php
/**
 * Minds Market
 * 
 * This is an OOP plugin and is an example of the new structure Minds plugins should follow. 
 * 
 * @package Minds.Core
 * @subpackage Plugins
 * @author Mark Harding (mark@minds.com)
 */

namespace minds\plugin\market;

use minds\core;
use minds\bases;

class start extends bases\plugin{
	
	public function __construct(){
		parent::__construct('market');
		
		$this->init();
	}
	
	public function init(){
		//\elgg_register_page_handler('market', array($this, 'pageHandler'));
		$routes = core\router::registerRoutes($this->registerRoutes());
		
		/**
		 * Register a site menu 
		 * @todo make this oop friendly
		 */
		\elgg_register_menu_item('site', array(
		    'name' => 'market',
		    'text' => '<span class="entypo">&#59197;</span> Market',
		    'href' => 'market',
		    'title' => elgg_echo('market')
	    ));
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
			'/market/add' => "$path\\pages\\edit",
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
