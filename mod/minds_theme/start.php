<?php
/**
 * Minds theme
 *
 * @package Minds
 * @author Kramnorth (Mark Harding)
 *
 */

function minds_theme_init(){
										
	elgg_extend_view('css/elgg','minds/css');
	
	elgg_register_simplecache_view('minds');	
	
	elgg_register_event_handler('pagesetup', 'system', 'minds_pagesetup');
	
	elgg_register_page_handler('news', 'elgg_river_page_handler');
		
 	elgg_extend_view('page/elements/head','minds/meta');
		
	//set the custom index
	elgg_register_plugin_hook_handler('index', 'system','minds_index');
	
}

function minds_index($hook, $type, $return, $params) {
	if ($return == true) {
		// another hook has already replaced the front page
		return $return;
	}
	
	if(!include_once(dirname(__FILE__) . '/pages/index.php')){
		return false;
	}
	
	return true;
}


function minds_pagesetup(){
	//Top Bar Menu
	elgg_unregister_menu_item('topbar', 'elgg_logo');
	elgg_unregister_menu_item('topbar', 'administration');
	elgg_unregister_menu_item('topbar', 'friends');
	
	elgg_register_menu_item('topbar', array(
			'name' => 'search',
			'href' => '#',
			'text' => elgg_view('search/header'),
			'priority' => 50,
			'section' => 'alt',
		));
		
	elgg_register_menu_item('topbar', array(
			'name' => 'login',
			'href' => '#',
			'text' => elgg_view('core/account/login_dropdown'),
			'priority' => 20,
			'section' => 'alt',
		));
	elgg_register_menu_item('topbar', array(
			'name' => 'minds_logo',
			'href' => '/',
			'text' => '<img src=\''. elgg_get_site_url() . 'mod/minds_theme/graphics/topbar_logo.gif\'>',
			'priority' => 0
		));
	
	//rename activity news	
	elgg_unregister_menu_item('site', 'activity');
	
	$item = new ElggMenuItem('news', elgg_echo('news'), 'news');
	elgg_register_menu_item('site', $item);
}
elgg_register_event_handler('init','system','minds_theme_init');		

?>
