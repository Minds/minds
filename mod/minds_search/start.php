<?php
/**
 * elasticsearch
 *
 * @package elasticsearch
 */

elgg_register_event_handler('init', 'system', 'minds_search_init');

/**
 * Init function
 */
function minds_search_init() {
	elgg_extend_view('css/elgg', 'minds_search/css');
	//elgg_extend_view('page/elements/header', 'minds_search/header');

	elgg_register_library('elasticsearch', elgg_get_plugins_path() . 'minds_search/lib/elasticsearch.php');
	elgg_register_library('minds_search', elgg_get_plugins_path() . 'minds_search/lib/minds_search.php');

	elgg_load_library('minds_search');
	elgg_load_library('elasticsearch');

	//create handlers
//	elgg_register_event_handler('create', 'user', 'elasticsearch_add');
//	elgg_register_event_handler('create', 'group', 'elasticsearch_add');
//	elgg_register_event_handler('create', 'object', 'elasticsearch_add');

	//update handlers
/*	elgg_register_event_handler('update', 'user', 'elasticsearch_update');
	elgg_register_event_handler('update', 'group', 'elasticsearch_update');
	elgg_register_event_handler('update', 'object', 'elasticsearch_update');
*/
	//delete handler
	elgg_register_event_handler('delete', 'user', 'elasticsearch_remove');
	elgg_register_event_handler('delete', 'group', 'elasticsearch_remove');
	elgg_register_event_handler('delete', 'object', 'elasticsearch_remove');
	
	global $CONFIG;
	define('elasticsearch_server', $CONFIG->elasticsearch_server);
	define('elasticsearch_index', isset($CONFIG->elasticsearch_sitesearch_index) ? $CONFIG->elasticsearch_sitesearch_index : elgg_get_plugin_setting('index')); //we need to move this over to settings soon, this only used by SITE search
	
	$wikiCSS = elgg_get_simplecache_url('css', 'wiki');
	elgg_register_css('wiki', $wikiCSS);

	elgg_extend_view('js/elgg', 'minds_search/js');
	
	elgg_register_plugin_hook_handler('register', 'menu:search_result', 'minds_search_result_menu_setup');

	// Page handler for the modal media embed
	elgg_register_page_handler('search', 'minds_search_page_handler');
}

/**
 * Search page handler
 *
 * @param array $page
 * @return bool
 */
function minds_search_page_handler($page) {

	if (!isset($page[0])) {
		$page[0] = 'all';
	}

	$file_dir = elgg_get_plugins_path() . 'minds_search/pages/minds_search';

	$page_type = $page[0];
	switch ($page_type) {

		case 'live' :
			elasticsearch_live();
			break;
		case 'result' :
			set_input('id', $page[1]);
			include "$file_dir/result.php";
			break;
		case 'service-index' :
			//if(elgg_is_admin_logged_in())
			minds_search_index($page[1]);
			break;
		case 'index' :
			if (elgg_is_admin_logged_in())
				elasticsearch_index_once();
			break;
		case 'all' :
		default :
		include "$file_dir/search.php";
			return false;
	}
	return true;
}

/**
 * Setup the search result menu
 */
function minds_search_result_menu_setup($hook, $type, $return, $params) {
	if (elgg_is_logged_in()) {

		$item_id = $params['item_id'];
		$source = $params['source'];
		$source_href = $params['source_href'];

		//Remind button
		$options = array('name' => 'remind', 'href' => "action/minds/remind/external?item_id=$item_id", 'text' => elgg_view_icon('share'), 'title' => elgg_echo('minds:remind'), 'is_action' => true, 'priority' => 1, );
		$return[] = ElggMenuItem::factory($options);

		/*$options = array('name' => 'download', 'href' => elgg_get_site_url().'search/download/'.$item_id, 'text'=>elgg_echo('minds_search:download'), 'title' =>elgg_echo('minds_search:download'), 'class'=>'elgg-button elgg-button-action');
		$return[] = ElggMenuItem::factory($options);*/
		
		$options = array('name' => 'source_link', 'href' => $source_href, 'text'=>elgg_echo('minds_search:source_link', array($source)),'title' =>elgg_echo('minds_search:download'), 'class'=>'elgg-button elgg-button-action');
		$return[] = ElggMenuItem::factory($options);		
	}

	return $return;
}
