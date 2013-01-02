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
	elgg_extend_view('page/elements/header', 'minds_search/header');

	elgg_register_library('elasticsearch', elgg_get_plugins_path() . 'minds_search/lib/elasticsearch.php');
	elgg_register_library('minds_search', elgg_get_plugins_path() . 'minds_search/lib/minds_search.php');

	elgg_load_library('minds_search');
	elgg_load_library('elasticsearch');
	
	//create handlers
	elgg_register_event_handler('create', 'user', 'elasticsearch_add');
	elgg_register_event_handler('create', 'group', 'elasticsearch_add');
	elgg_register_event_handler('create', 'object', 'elasticsearch_add');

	//update handlers
	elgg_register_event_handler('update', 'user', 'elasticsearch_update');
	elgg_register_event_handler('update', 'group', 'elasticsearch_update');
	elgg_register_event_handler('update', 'object', 'elasticsearch_update');

	//delete handler
	elgg_register_event_handler('delete', 'user', 'elasticsearch_remove');
	elgg_register_event_handler('delete', 'group', 'elasticsearch_remove');
	elgg_register_event_handler('delete', 'object', 'elasticsearch_remove');

	define('elasticsearch_server', elgg_get_plugin_setting('server'));
	define('elasticsearch_index', elgg_get_plugin_setting('index'));

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

		case 'all' :
			include "$file_dir/search.php";
			break;
		case 'live' :
			elasticsearch_live();
			break;
		case 'result':
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

		default :
			return false;
	}
	return true;
}
