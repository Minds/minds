<?php
/**
 * elasticsearch
 *
 * @package elasticsearch
 */


elgg_register_event_handler('init', 'system', 'elasticsearch_init');

/**
 * Init function
 */
function elasticsearch_init() {
	elgg_extend_view('css/elgg', 'elasticsearch/css');
	
	//create handlers
	elgg_register_event_handler('create', 'user', 'elasticsearch_add');
	elgg_register_event_handler('create', 'object', 'elasticsearch_add');
	
	//update handlers
	elgg_register_event_handler('update', 'user', 'elasticsearch_update'); 
	elgg_register_event_handler('update', 'object', 'elasticsearch_update'); 
	
	//delete handler
	elgg_register_event_handler('delete', 'user', 'elasticsearch_remove');
	elgg_register_event_handler('delete', 'object', 'elasticsearch_remove');

	// Page handler for the modal media embed
	elgg_register_page_handler('search', 'elasticsearch_page_handler');
	
	define('elasticsearch_server', 'http://107.23.117.9:9200');
}
/**
 * Search page handler
 *
 * @param array $page
 * @return bool
 */
function elasticsearch_page_handler($page) {

	if (!isset($page[0])) {
		$page[0] = 'all';
	}

	$file_dir = elgg_get_plugins_path() . 'elasticsearch/pages/elasticsearch';

	$page_type = $page[0];
	switch ($page_type) {
		
		case 'all':
			file_register_toggle();
			include "$file_dir/search.php";
			break;
		
		default:
			return false;
	}
	return true;
}

/**
 * Parse results from a search
 * 
 * @param string $query
 * @return array
 */
function elasticsearch_parse($query){
	
	$es = new elasticsearch();
	$es->index = 'minds';
	return $es->query(null, $query);
}
/**
 * Encode an object into json block
 *
 * @param array $object
 * @return string
 */
function elasticsearch_encode($object){
	
	$data = new stdClass();
	
	$data->guid = $object->getGUID();
	
	if($object instanceof ElggUser){
		
		$data->name = $object->name;
		$data->location = $object->location;
		$data->email = $object->email;
		
	} else {

		$data->title = $object->title;
		$data->tags = $object->tags;
		
		$owner = $object->getOwnerEntity();
		$data->owner["guid"] = $owner->guid;
		$data->owner["name"] = $owner->name;
		$data->owner["username"] = $owner->username;
	}
	
	$data->description = strip_tags($object->description);
		
	return json_encode($data);
}
/**
 * Add an entity to the elastic search
 *
 * @param string $event
 * @param string $object_type
 * @param array $object
 * @return array
 */
function elasticsearch_add($event, $object_type, $object){

	$es = new elasticsearch();
	$es->index = 'minds';
	$es->add($object_type, $object->getGUID(), elasticsearch_encode($object));
	
	return $es;
}

/**
 * Update to the elastic search
 *
 * @param string $event
 * @param string $object_type
 * @param array $object
 * @return array
 */
function elasticsearch_update($event, $object_type, $object){
		
	$es = new elasticsearch();
	$es->index = 'minds';
	$es->add($object_type, $object->getGUID(), elasticsearch_encode($object));
	
	return $es;
}

/**
 * Remove an entity to the elastic search
 *
 * @param string $event
 * @param string $object_type
 * @param array $object
 * @return array
 */
function elasticsearch_remove($event, $object_type, $object){

	$es = new elasticsearch();
	$es->index = 'minds';
	$es->remove($object_type, $object->getGUID());
	
	return $es;
}
