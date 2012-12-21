<?php 
/**
 * One time run indexer for first use
 */
function elasticsearch_index_once(){
	$entities = elgg_get_entities(array('limit'=> 99999999999999));
	
	foreach($entities as $entity){
		if(elasticsearch_index_allowed($entity)){
			$es = new elasticsearch();
			$es->index = elasticsearch_index;
			echo $es->add($entity->getType(), $entity->getGUID(), elasticsearch_encode($entity));
		}
	}
	
	return 'All done';
}

/**
 * Parse results from a search
 * 
 * @param string $query
 * @return array
 */
function elasticsearch_parse($query, $object_type, $sort, $limit, $offset){
	
	$es = new elasticsearch();
	$es->index = elasticsearch_index;
	return $es->query($object_type, $query, $sort, $limit, $offset);
}
/**
 * Returns the view and fallbacks for search results
 *
 * @param array $params
 * @param string $view_type = list, entity or layout
 * @return string
 */
function elasticsearch_get_search_view($params) {

	$view_order = array();

	// check if there's a special search list view for this type:subtype
	if (isset($params['type']) && $params['type'] && isset($params['subtype']) && $params['subtype']) {
		$view = "minds_search/{$params['type']}/{$params['subtype']}";
		if(elgg_view_exists($view)){
			return $view;
		}
	}

	// also check for the default type
	if (isset($params['type']) && $params['type']) {
		$view = "minds_search/{$params['type']}";
		if(elgg_view_exists($view)){
			return $view;
		}
	}

	// check search types
	if (isset($params['search_type']) && $params['search_type']) {
		$view = "minds_search/{$params['search_type']}/$view_type";
		if(elgg_view_exists($view)){
			return $view;
		}
	}

	// finally default to a search list default
	 return "minds_search/view";

}
/*
 * Live search
 * 
 */
function elasticsearch_live(){
	if (!$q = get_input('term', get_input('q'))) {
		exit;
	}
	$sort = array('name:desc', 'title:desc');
	$call = elasticsearch_parse($q, null, $sort);
	$hits = $call['hits'];
	$items = $hits['hits'];
	
	if($hits['total'] > 0){
		foreach($items as $item){
				
			$entity = get_entity($item['_source']['guid']);
			$icon = elgg_view_entity_icon($entity, 'tiny', array(
							'use_hover' => false,
						));
			$view = elasticsearch_get_search_view(array('type'=>$entity->getType(), 'subtype'=> $entity->getSubtype()));
			$output = elgg_view($view, array('item'=>$entity));
			if($entity->getType() == 'user'){	
				$result = array(
									'type' => 'user',
									'name' => $entity->name,
									'desc' => $entity->username,
									'guid' => $entity->guid,
									'label' => $output,
									'value' => $value,
									'icon' => $icon,
									'url' => $entity->getURL(),
								);
								
				$results[$entity->name . rand(1, 100)] = $result;
			} elseif($entity->getType() == 'group'){
				$result = array(
								'type' => 'group',
								'name' => $entity->name,
								'desc' => strip_tags($entity->description),
								'guid' => $entity->guid,
								'label' => $output,
								'value' => $entity->guid,
								'icon' => $icon,
								'url' => $entity->getURL(),
							);
	
				$results[$entity->name . rand(1, 100)] = $result;
			} elseif($entity->getType() == 'object'){
				$icon = elgg_view_entity_icon($entity->getOwnerEntity(), 'tiny', array(
							'use_hover' => false,
						));
				$result = array(
								'type' => 'object',
								'name' => $entity->title,
								'desc' => strip_tags($entity->description),
								'guid' => $entity->guid,
								'label' => $output,
								'value' => $entity->guid,
								'icon' => $icon,
								'url' => $entity->getURL(),
							);
	
				$results[$entity->title . rand(1, 100)] = $result;
			}
		}
			ksort($results);
			header("Content-Type: application/json");
			echo json_encode(array_values($results));
			exit;
	}
	
	return false;
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
	
	$data->type = $object->getType();
	$data->subtype = $object->getSubtype();
	
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
 * Provides a list of plugins and objects that are allowed to be indexed
 * 
 * @return array
 */
function elasticsearch_index_allowed($object){
	if($object->getType() == 'user' || $object->getType() == 'group'){
		return true;
	}
	
	//allowed subtypes @todo make some sort of hook
	$subtypes = array( 'kaltura_video', 'image', 'album', 'file', 'event_calendar', 'blog', 'bookmark', 'webinar', 'livestream', 'market', 'page', 'poll');

	if(in_array($object->getSubtype(), $subtypes)){
		return true;
	}
		
	return false;
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
	if(elasticsearch_index_allowed($object)){
		$es = new elasticsearch();
		$es->index = elasticsearch_index;
		return $es->add($object_type, $object->getGUID(), elasticsearch_encode($object));
	}
	return;
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
	if(elasticsearch_index_allowed($object)){	
		$es = new elasticsearch();
		$es->index = elasticsearch_index;
		$es->add($object_type, $object->getGUID(), elasticsearch_encode($object));
	}
	
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
	$es->index = elasticsearch_index;
	$es->remove($object_type, $object->getGUID());
	
	return $es;
}