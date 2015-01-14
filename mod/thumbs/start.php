<?php
/**
 * Minds gatherings.
 * 
 * @package Minds
 * @subpackage gatherings
 * @author Mark Harding (mark@minds.com)
 * 
 */

namespace minds\plugin\thumbs;

use minds\bases;
use minds\core;
use ElggMenuItem;

class start extends Components\Plugin{
	
	public function init(){
		elgg_extend_view('css/elgg', 'thumbs/css');
	
		$thumbs_js = elgg_get_simplecache_url('js', 'thumbs');
		elgg_register_simplecache_view('js/thumbs');
		elgg_register_js('elgg.thumbs', $thumbs_js, 'footer');
	
		//remove comments icons
		elgg_unregister_menu_item('river', 'comment');
		elgg_unregister_menu_item('menu:entity', 'comment');
	
		// registered with priority < 500 so other plugins can remove likes
		elgg_register_plugin_hook_handler('register', 'menu:entity', array($this, 'menu'));
		elgg_register_plugin_hook_handler('register', 'menu:comments',  array($this, 'menu'));
	
		$actions_base = elgg_get_plugins_path() . 'thumbs/actions/thumbs';
		elgg_register_action('thumbs/up', "$actions_base/up.php");
		elgg_register_action('thumbs/down', "$actions_base/down.php");
		
		
		core\router::registerRoutes(array(
			'/thumbs/actions' => "\\minds\\plugin\\thumbs\\pages\\actions",
			'/api/v1/thumbs' => "\\minds\\plugin\\thumbs\\api\\v1\\thumbs",
		));
	}
	
	public function menu($hook, $type, $return, $params) {
		
		$entity = $params['entity'];
		if(!$entity && isset($params['comment']))
			$entity = $params['comment'];
		
		if ($entity -> type != "group" && $entity -> type != "user") {
	
			// likes button
			$options = array('name' => 'thumbs:up', 'text' => elgg_view('thumbs/button-up', array('entity' => $entity)), 'href' => false, 'priority' => 98, );
			$return[] = ElggMenuItem::factory($options);
	
			// down button
			$options = array('name' => 'thumbs:down', 'text' => elgg_view('thumbs/button-down', array('entity' => $entity)), 'href' => false, 'priority' => 99, );
			$return[] = ElggMenuItem::factory($options);
	
		}
	
		return $return;
	}

	static public function countUP($entity){
	}
	static public function countDOWN($entity){
	}
	
}