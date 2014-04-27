<?php
/**
 * Elgg river.
 * Activity stream functions.
 *
 * @package Elgg.Core
 * @subpackage SocialModel.River
 */

/**
 * Adds an item to the river.
 *
 * @param string $view          The view that will handle the river item (must exist)
 * @param string $action_type   An arbitrary string to define the action (eg 'comment', 'create')
 * @param int    $subject_guid  The GUID of the entity doing the action
 * @param int    $object_guid   The GUID of the entity being acted upon
 * @param int    $access_id     The access ID of the river item (default: same as the object)
 * @param int    $posted        The UNIX epoch timestamp of the river item (default: now)
 * @param int    $annotation_id The annotation ID associated with this river entry
 *
 * @return int/bool River ID or false on failure
 */
function add_to_river($view, $action_type, $subject_guid, $object_guid, $access_id = "",
$posted = 0, $annotation_id = 0) {

	global $CONFIG;
	
	// use default viewtype for when called from web services api
	if (!elgg_view_exists($view, 'default')) {
		return false;
	}
	if (!($subject = get_entity($subject_guid,'user'))) {
		return false;
	}
	if (!($object = get_entity($object_guid,'object'))) {
	//	return false;
	}
	if (empty($action_type)) {
		return false;
	}
	if ($posted == 0 || !$posted) {
		$posted = time();
	}
	if ($access_id === "") {
		$access_id = $object->access_id;
	}

	$serialized_subject = serialize($subject);
	$serialized_object = serialize($object);

	// Attempt to save river item; return success status
	$db = new DatabaseCall('newsfeed');
	$id = $db->insert(0, array(
					'subject_guid'=>$subject_guid,
					'object_guid'=>$object_guid,
					'access_id'=>$access_id,
					'view'=>$view,
					'posted'=>$posted,
					'action_type'=>$action_type,
					'subject' => $serialized_subject,
					'object' => $serialized_object
			));

	//get the followers of the subject guid
        $followers = $subject->getFriendsOf(null, 10000, "", 'guids');
	if(!$followers) { $followers = array(); }
	$followers = array_keys($followers);
	array_push($followers, 'site');//add to public timeline
	array_push($followers, $action_type);//timelines for actions too
	array_push($followers, $subject_guid);//add to their own timeline
	array_push($followers, $object->container_guid); //add to containers timeline
	foreach($followers as $follower_guid){
		$db = new DatabaseCall('timeline');
		$db->insert($follower_guid, array($id => time()));
	}

	//place on users own personal line
	$db = new DatabaseCall('timeline');
	$db->insert('personal:'.$subject_guid, array($id => time()));

	if ($id) {
//		update_entity_last_action($object_guid, $posted);
//		
//		$river_items = elgg_get_river(array('id' => $id));
//		if ($river_items) {
//			elgg_trigger_event('created', 'river', $river_items[0]);
//		}
		return $id;
	} else {
		return false;
	}
}

/**
 * Delete river items
 *
 * @warning not checking access (should we?)
 *
 * @param array $options Parameters:
 *   ids                  => INT|ARR River item id(s)
 *   subject_guids        => INT|ARR Subject guid(s)
 *   object_guids         => INT|ARR Object guid(s)
 *   annotation_ids       => INT|ARR The identifier of the annotation(s)
 *   action_types         => STR|ARR The river action type(s) identifier
 *   views                => STR|ARR River view(s)
 *
 *   types                => STR|ARR Entity type string(s)
 *   subtypes             => STR|ARR Entity subtype string(s)
 *   type_subtype_pairs   => ARR     Array of type => subtype pairs where subtype
 *                                   can be an array of subtype strings
 * 
 *   posted_time_lower    => INT     The lower bound on the time posted
 *   posted_time_upper    => INT     The upper bound on the time posted
 *
 * @return bool
 * @since 1.8.0
 */
function elgg_delete_river(array $options = array()) {
	global $CONFIG, $DB;

	$defaults = array( 'ids' => ELGG_ENTITIES_ANY_VALUE);
	$options = array_merge($defaults, $options);

	$singulars = array('id');
	$options = elgg_normalise_plural_options_array($options, $singulars);

	$ids = $options['ids'];
	$items = elgg_get_river(array('type'=>'newsfeed', 'ids'=>$ids));

	foreach($items as $item){

		//get the subjects friends
		$owner = $item->getSubjectEntity();
		$object = $item->getObjectEntity();
		$followers = $owner->getFriendsOf();
		if(!is_array($followers)){ $followers = array(); }
		$followers = array_keys($followers);

		//remove from personal line
		array_push($followers, 'personal:'.$owner->guid);
		//remove from public line
		array_push($followers, 'site');
		//action types
		array_push($followers, $item->action_type);
		//own
		array_push($followers, $owner->guid);
		//container_guid
		array_push($followers, $object->container_guid);

		$db = new DatabaseCall('timeline');
		foreach($followers as $follower){
			$db->removeAttributes($follower, array($item->id));
		}

		//remove from the main newsfeed now
		$db = new DatabaseCall('newsfeed');
		$db->removeRow($item->id);

	}

	return true;
}
/**
 * Get river items
 *
 * @note If using types and subtypes in a query, they are joined with an AND.
 *
 * @param array $options Parameters:
 *   ids                  => INT|ARR River item id(s)
 *
 * @return array|int
 * @since 1.8.0
 */
function elgg_get_river(array $options = array()) {
	global $CONFIG;

	$defaults = array(

		'limit'	=> 10,
		'offset' 	=> 0,
		
		'owner_guid'	=> 'site',

		'type'	=> 'timeline'
	);

	$options = array_merge($defaults, $options);
	
	$singulars = array('id');
	$options = elgg_normalise_plural_options_array($options, $singulars);
	
	//no params, then get the public line
	if($options['type'] == 'timeline'){
		
		$timeline = new DatabaseCall('timeline');
		$row = $timeline->getRow($options['owner_guid'], array('offset'=>$options['offset'], 'limit'=>$options['limit']));
		if(!$row)
			return false;

		foreach($row as $k => $v){
        		if($k != 'type' || $k != 0){
        	    	$ids[] = $k;
        	    }
        	}

		if($ids){
			$newsfeed = new DatabaseCall('newsfeed');
			$rows = $newsfeed->getRows($ids);
		}

	} elseif($options['type'] == 'newsfeed'){
		
		$db = new DatabaseCall('newsfeed');
		
		if($ids = $options['ids']){

			$rows = $db->getRows($ids);
		
		} else {

			$rows = $db->getRow($options['guid'], array('offset'=>$options['offset'], 'limit'=>$options['limit']));
	
		}

	} else {
		//not supported
		return;
	}

	if(!$rows){
		return false; 
	}

	foreach($rows as $id => $row){
		$row['id'] = $id;
		$items[] = new ElggRiverItem($row);
	}

	return $items;
}

/**
 * Prefetch entities that will be displayed in the river.
 *
 * @param ElggRiverItem[] $river_items
 * @access private
 */
function _elgg_prefetch_river_entities(array $river_items) {
	// prefetch objects and subjects
	$guids = array();
	foreach ($river_items as $item) {
		if ($item->subject_guid && !retrieve_cached_entity($item->subject_guid)) {
			$guids[$item->subject_guid] = true;
		}
		if ($item->object_guid && !retrieve_cached_entity($item->object_guid)) {
			$guids[$item->object_guid] = true;
		}
	}
	if ($guids) {
		// avoid creating oversized query
		// @todo how to better handle this?
		$guids = array_slice($guids, 0, 300, true);
		// return value unneeded, just priming cache
		elgg_get_entities(array(
			'guids' => array_keys($guids),
			'limit' => 0,
		));
	}

	// prefetch object containers
	$guids = array();
	foreach ($river_items as $item) {
		$object = $item->getObjectEntity();
		if ($object->container_guid && !retrieve_cached_entity($object->container_guid)) {
			$guids[$object->container_guid] = true;
		}
	}
	if ($guids) {
		$guids = array_slice($guids, 0, 300, true);
		elgg_get_entities(array(
			'guids' => array_keys($guids),
			'limit' => 0,
		));
	}
}

/**
 * List river items
 *
 * @param array $options Any options from elgg_get_river() plus:
 * 	 pagination => BOOL Display pagination links (true)
 *
 * @return string
 * @since 1.8.0
 */
function elgg_list_river(array $options = array()) {
	global $autofeed;
	$autofeed = true;

	$defaults = array(
		'offset'     => (int) max(get_input('offset', 0), 0),
		'limit'      => (int) max(get_input('limit', 20), 0),
		'pagination' => TRUE,
		'list_class' => 'minds-list-river elgg-river', // @todo remove elgg-river in Elgg 1.9
	);

	$options = array_merge($defaults, $options);

	$count = $options['limit'] +1;

	$items = elgg_get_river($options);
	
	$options['count'] = $count;
	$options['items'] = $items;
	return elgg_view('page/components/list', $options);
}

/**
 * Convert a database row to a new ElggRiverItem
 *
 * @param stdClass $row Database row from the river table
 *
 * @return ElggRiverItem
 * @since 1.8.0
 * @access private
 */
function elgg_row_to_elgg_river_item($row) {
	if (!($row instanceof stdClass)) {
		return NULL;
	}

	return new ElggRiverItem($row);
}

/**
 * Get the river's access where clause
 *
 * @return string
 * @since 1.8.0
 * @access private
 */
function elgg_river_get_access_sql() {
	// rewrite default access where clause to work with river table
	return str_replace("and enabled='yes'", '',
		str_replace('owner_guid', 'rv.subject_guid',
		str_replace('access_id', 'rv.access_id', get_access_sql_suffix())));
}

/**
 * Returns SQL where clause for type and subtype on river table
 *
 * @internal This is a simplified version of elgg_get_entity_type_subtype_where_sql()
 * which could be used for all queries once the subtypes have been denormalized.
 *
 * @param string     $table    'rv'
 * @param NULL|array $types    Array of types or NULL if none.
 * @param NULL|array $subtypes Array of subtypes or NULL if none
 * @param NULL|array $pairs    Array of pairs of types and subtypes
 *
 * @return string
 * @since 1.8.0
 * @access private
 */
function elgg_get_river_type_subtype_where_sql($table, $types, $subtypes, $pairs) {
	// short circuit if nothing is requested
	if (!$types && !$subtypes && !$pairs) {
		return '';
	}

	$wheres = array();
	$types_wheres = array();
	$subtypes_wheres = array();

	// if no pairs, use types and subtypes
	if (!is_array($pairs)) {
		if ($types) {
			if (!is_array($types)) {
				$types = array($types);
			}
			foreach ($types as $type) {
				$type = sanitise_string($type);
				$types_wheres[] = "({$table}.type = '$type')";
			}
		}

		if ($subtypes) {
			if (!is_array($subtypes)) {
				$subtypes = array($subtypes);
			}
			foreach ($subtypes as $subtype) {
				$subtype = sanitise_string($subtype);
				$subtypes_wheres[] = "({$table}.subtype = '$subtype')";
			}
		}

		if (is_array($types_wheres) && count($types_wheres)) {
			$types_wheres = array(implode(' OR ', $types_wheres));
		}

		if (is_array($subtypes_wheres) && count($subtypes_wheres)) {
			$subtypes_wheres = array('(' . implode(' OR ', $subtypes_wheres) . ')');
		}

		$wheres = array(implode(' AND ', array_merge($types_wheres, $subtypes_wheres)));

	} else {
		// using type/subtype pairs
		foreach ($pairs as $paired_type => $paired_subtypes) {
			$paired_type = sanitise_string($paired_type);
			if (is_array($paired_subtypes)) {
				$paired_subtypes = array_map('sanitise_string', $paired_subtypes);
				$paired_subtype_str = implode("','", $paired_subtypes);
				if ($paired_subtype_str) {
					$wheres[] = "({$table}.type = '$paired_type'"
						. " AND {$table}.subtype IN ('$paired_subtype_str'))";
				}
			} else {
				$paired_subtype = sanitise_string($paired_subtypes);
				$wheres[] = "({$table}.type = '$paired_type'"
					. " AND {$table}.subtype = '$paired_subtype')";
			}
		}
	}

	if (is_array($wheres) && count($wheres)) {
		$where = implode(' OR ', $wheres);
		return "($where)";
	}

	return '';
}

/**
 * Get the where clause based on river action type strings
 *
 * @param array $types Array of action type strings
 *
 * @return string
 * @since 1.8.0
 * @access private
 */
function elgg_river_get_action_where_sql($types) {
	if (!$types) {
		return '';
	}

	if (!is_array($types)) {
		$types = sanitise_string($types);
		return "(rv.action_type = '$types')";
	}

	// sanitize types array
	$types_sanitized = array();
	foreach ($types as $type) {
		$types_sanitized[] = sanitise_string($type);
	}

	$type_str = implode("','", $types_sanitized);
	return "(rv.action_type IN ('$type_str'))";
}

/**
 * Get the where clause based on river view strings
 *
 * @param array $views Array of view strings
 *
 * @return string
 * @since 1.8.0
 * @access private
 */
function elgg_river_get_view_where_sql($views) {
	if (!$views) {
		return '';
	}

	if (!is_array($views)) {
		$views = sanitise_string($views);
		return "(rv.view = '$views')";
	}

	// sanitize views array
	$views_sanitized = array();
	foreach ($views as $view) {
		$views_sanitized[] = sanitise_string($view);
	}

	$view_str = implode("','", $views_sanitized);
	return "(rv.view IN ('$view_str'))";
}

/**
 * Sets the access ID on river items for a particular object
 *
 * @param int $object_guid The GUID of the entity
 * @param int $access_id   The access ID
 *
 * @return bool Depending on success
 */
function update_river_access_by_object($object_guid, $access_id) {
	// Sanitise
	$object_guid = (int) $object_guid;
	$access_id = (int) $access_id;

	// Load config
	global $CONFIG;

	// Remove
	$query = "update {$CONFIG->dbprefix}river
		set access_id = {$access_id}
		where object_guid = {$object_guid}";
	return update_data($query);
}

/**
 * Page handler for activity
 *
 * @param array $page
 * @return bool
 * @access private
 */
function elgg_river_page_handler($page) {
	global $CONFIG;

	elgg_set_page_owner_guid(elgg_get_logged_in_user_guid());

	// make a URL segment available in page handler script
	$page_type = elgg_extract(0, $page, 'all');
	$page_type = preg_replace('[\W]', '', $page_type);
	if ($page_type == 'owner') {
		$page_type = 'mine';
	}
	set_input('page_type', $page_type);

	require_once("{$CONFIG->path}pages/river.php");
	return true;
}

/**
 * This function is triggered when an object is deleted
 */
function river_delete_object_hook($event, $object_type, $object){
	
}

/**
 * Register river unit tests
 * @access private
 */
function elgg_river_test($hook, $type, $value) {
	global $CONFIG;
	$value[] = $CONFIG->path . 'engine/tests/api/river.php';
	return $value;
}

/**
 * Initialize river library
 * @access private
 */
function elgg_river_init() {
	elgg_register_page_handler('activity', 'elgg_river_page_handler');
	$item = new ElggMenuItem('activity', elgg_echo('activity'), 'activity');
	elgg_register_menu_item('site', $item);
	
	elgg_register_widget_type('river_widget', elgg_echo('river:widget:title'), elgg_echo('river:widget:description'));

	elgg_register_action('river/delete', '');
	
	elgg_register_event_handler('delete', 'object', 'river_delete_object_hook');

	elgg_register_plugin_hook_handler('unit_test', 'system', 'elgg_river_test');
}

elgg_register_event_handler('init', 'system', 'elgg_river_init');
