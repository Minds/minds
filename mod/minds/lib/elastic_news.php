<?php
/**
 * Elastic News
 * A news feed for Minds running from elastic search
 * 
 * @author Mark Harding (mark@minds.com)
 * 
 * @todo Write Unit tests for this
 */

//rename_function('add_to_river', 'minds_elastic_add_to_news');
function add_to_river($view, $action_type, $subject_guid, $object_guid, $access_id = "", $posted = 0, $annotation_id = 0) {
	global $CONFIG;
	// use default viewtype for when called from web services api
	if (!elgg_view_exists($view, 'default')) {
		return false;
	}
		if (!($subject = get_entity($subject_guid, 'user'))) {
		return false;
	}
	if (!($object = get_entity($object_guid, 'object'))) {
		return false;
	}
	if (empty($action_type)) {
		return false;
	}
	if ($posted == 0) {
		$posted = time();
	}
	if ($access_id === "") {
		$access_id = $object->access_id;
	}
	$type = $object->getType();
	$subtype = $object->getSubtype();
	
	$es = new elasticsearch();
	$es->index = $CONFIG->elasticsearch_prefix . 'news';
	
	$view = $view;
	$action_type = $action_type;
	$subject_guid = $subject_guid;
	$object_guid = $object_guid;
	$access_id = $access_id;
	$posted = $posted;
	$annotation_id = $annotation_id;
	
	$id = md5($subject_guid . time()); //id is an md5 hash of subject id + time
	
	$data = new stdClass();
	$data->view = $view;
	$data->object_guid = $object_guid; //should rive posts have to be linked to an object id?? 
	$data->type = $type;
	$data->subtype = $subtype;
	$data->subject_guid = $subject_guid;
	$data->posted = $posted;
	$data->access_id = $access_id; //@MH - I think that this should be defined from the object or subject, but lets leave for now.
	
	$save = $es->add($action_type, $id, json_encode($data)); 
	
	if($save['ok']){
		return $id;
	} else{
		return false;
	}
}

function elgg_delete_river(array $options = array()){
	return minds_elastic_delete_news($options);
}
function minds_elastic_delete_news(array $options = array()) {
	global $CONFIG;

	$defaults = array(
		'ids'                  => ELGG_ENTITIES_ANY_VALUE,

		'subject_guids'	       => ELGG_ENTITIES_ANY_VALUE,
		'object_guids'         => ELGG_ENTITIES_ANY_VALUE,
		'annotation_ids'       => ELGG_ENTITIES_ANY_VALUE,

		'views'                => ELGG_ENTITIES_ANY_VALUE,
		'action_types'         => ELGG_ENTITIES_ANY_VALUE,

		'types'	               => ELGG_ENTITIES_ANY_VALUE,
		'subtypes'             => ELGG_ENTITIES_ANY_VALUE,
		'type_subtype_pairs'   => ELGG_ENTITIES_ANY_VALUE,

		'posted_time_lower'	   => ELGG_ENTITIES_ANY_VALUE,
		'posted_time_upper'	   => ELGG_ENTITIES_ANY_VALUE,

		'wheres'               => array(),
		'joins'                => array(),

	);

	$options = array_merge($defaults, $options);

	$singulars = array('id', 'subject_guid', 'object_guid', 'annotation_id', 'action_type', 'view', 'type', 'subtype');
	$options = elgg_normalise_plural_options_array($options, $singulars);
	
	//delete by id
	foreach($options['ids'] as $id){
		$q .= "_id:$id AND ";
	}
	//delete by subject_guids
	foreach($options['subject_guids'] as $subject_guid){
		$q .= "subject_guid:$subject_guid AND ";
	}
	//delete by object_guids
	foreach($options['object_guids'] as $object_guid){
		$q .= "object_guid:$object_guid AND ";
	}
	//delete by view
	foreach($options['views'] as $view){
		$q .= "view:$view AND ";
	}
	//delete by action_type
	foreach($options['action_types'] as $action_type){
		$q .= "_type:$action_type AND ";
	}
	
	$q = substr($q, 0, -5);
	
	if(!$q){
		return false;
	}
	$es = new elasticsearch();
	$es->index = $CONFIG->elasticsearch_prefix . 'news';
	$query = $es->query($options['action_types'], $q);
	$items = $query['hits']['hits'];
	
	foreach($items as $item){
		$es->remove($item['_type'], $item['_id']);
	}

	return;
}

function minds_elastic_get_news(array $options = array()) {
	global $CONFIG;

	$defaults = array(
		'ids'                  => ELGG_ENTITIES_ANY_VALUE,

		'subject_guids'	       => ELGG_ENTITIES_ANY_VALUE,
		'object_guids'         => ELGG_ENTITIES_ANY_VALUE,
		'annotation_ids'       => ELGG_ENTITIES_ANY_VALUE,
		'action_types'         => ELGG_ENTITIES_ANY_VALUE,

		'relationship'         => NULL,
		'relationship_guid'    => NULL,
		'inverse_relationship' => FALSE,

		'types'	               => ELGG_ENTITIES_ANY_VALUE,
		'subtypes'             => ELGG_ENTITIES_ANY_VALUE,
		'type_subtype_pairs'   => ELGG_ENTITIES_ANY_VALUE,

		'posted_time_lower'	   => ELGG_ENTITIES_ANY_VALUE,
		'posted_time_upper'	   => ELGG_ENTITIES_ANY_VALUE,

		'limit'                => 20,
		'offset'               => 0,
		'count'                => FALSE,

		'order_by'             => 'rv.posted desc',
		'group_by'             => ELGG_ENTITIES_ANY_VALUE,

		'wheres'               => array(),
		'joins'                => array(),
	);

	$options = array_merge($defaults, $options);

	$singulars = array('id', 'subject_guid', 'object_guid', 'annotation_id', 'action_type', 'type', 'subtype');
	$options = elgg_normalise_plural_options_array($options, $singulars);
	//get by view
	foreach($options['views'] as $view){
		$q .= "view:$view AND ";
	}
	//get by action_type
	foreach($options['action_types'] as $action_type){
		$q .= "_type:$action_type AND ";
	}
	
	if($options['object_guids'] ){
		$q .= substr($object_guid_q,0,-4) . ' AND ';
	}
	
	if($options['subject_guids']){
			$bool['must']['terms']['subject_guid'] = '"' . $options['subject_guids'][0] . '"';
			$bool['must']['terms']['minimum_match'] = 1;
	}
	if($options['object_guids']){
			$bool['must']['terms']['object_guid'] = $options['object_guids'];
			$bool['must']['terms']['minimum_match'] = 1;
	}
	
	if($options['ids']){
			$bool['must']['terms']['_id'] = $options['ids'];
			$bool['must']['terms']['minimum_match'] = 1;
	}
	
	if($options['types']){
			$bool['should']['terms']['type'] = $options['types'];
			$data['query']['bool']['minimum_number_should_match'] =  +1;
	}
	if($options['subtypes']){
			$bool['should']['terms']['subtypes'] = $options['subtypes'];
			$data['query']['bool']['minimum_number_should_match'] =  +1;
	}
	
	if (class_exists('elasticsearch')) {
		$es = new elasticsearch();
		$es->index = $CONFIG->elasticsearch_prefix . 'news';
		
		if($bool){
			$data['query']['bool'] = $bool;
		}
		$data['size'] = $options['limit'];
		$data['from'] = $options['offset'];
		$data['sort'] = array('posted'=>'desc');
		
		$query = $es->terms($options['action_types'], json_encode($data));
	var_dump(json_encode($data));	
		if (!$options['count']) {
			return minds_elastic_parse_news($query); 
		} else {
			return $query['hits']['total'];
		}
	}
	return false;
}

function minds_elastic_list_news(array $options = array()) {
	global $autofeed;
	$autofeed = true;

	$defaults = array(
		'offset'     => (int) max(get_input('offset', 0), 0),
		'limit'      => (int) max(get_input('limit', 20), 0),
		'pagination' => TRUE,
		'list_class' => 'elgg-list-river elgg-river', // @todo remove elgg-river in Elgg 1.9
	);
var_dump($options);
	$options = array_merge($defaults, $options);

	$options['count'] = TRUE;
	$count = minds_elastic_get_news($options);
	
	$options['count'] = FALSE;
	$items = minds_elastic_get_news($options);

	$options['count'] = $count;
	$options['items'] = $items;
	return elgg_view('page/components/list', $options);
}

function minds_elastic_parse_news($data) {
	
	foreach($data['hits']['hits'] as $item){
		$object = (object) $item['_source'];
		$object->id = $item['_id'];
		$object->action_type = $item['_type'];
		$post[] = new MindsNewsItem($object);
	}
	
	return $post;
}

/**
 * Convert the old DB news over to elastic
 */
//minds_elastic_convert_news();
function minds_elastic_convert_news(){
	$river = elgg_get_river(array('limit'=>100000));
	$converted = array();//a list of already converted rows to avoid duplicate
	foreach($river as $row){
		if(!in_array($row->id, $converted)){
			add_to_river($row->view, $row->action_type, $row->subject_guid, $row->object_guid, $row->access_id, $row->posted, $row->annotation_id);
		}
		array_push($converted, $row->id);
	}
	
}
