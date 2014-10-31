<?php
/**
 * Thumbs plugin
 *
 */

elgg_register_event_handler('init', 'system', 'thumbs_init');

function thumbs_init() {

	elgg_extend_view('css/elgg', 'thumbs/css');

	$thumbs_js = elgg_get_simplecache_url('js', 'thumbs');
	elgg_register_simplecache_view('js/thumbs');
	elgg_register_js('elgg.thumbs', $thumbs_js, 'footer');

	//remove comments icons
	elgg_unregister_menu_item('river', 'comment');
	elgg_unregister_menu_item('menu:entity', 'comment');

	// registered with priority < 500 so other plugins can remove likes
	elgg_register_plugin_hook_handler('register', 'menu:river', 'thumbs_river_menu_setup');
	elgg_register_plugin_hook_handler('register', 'menu:entity', 'thumbs_entity_menu_setup');
	elgg_register_plugin_hook_handler('register', 'menu:thumbs', 'thumbs_entity_menu_setup');
	elgg_register_plugin_hook_handler('register', 'menu:comments', 'thumbs_entity_menu_setup');

	$actions_base = elgg_get_plugins_path() . 'thumbs/actions/thumbs';
	elgg_register_action('thumbs/up', "$actions_base/up.php");
	elgg_register_action('thumbs/down', "$actions_base/down.php");
}

/**
 * Add likes to entity menu at end of the menu
 */
function thumbs_entity_menu_setup($hook, $type, $return, $params) {
	if (elgg_in_context('widgets')) {
		return $return;
	}
	
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

		// likes count
		$count = elgg_view('thumbs/count', array('entity' => $entity));
		if ($count) {
			$options = array('name' => 'thumbs:count', 'text' => $count, 'href' => false, 'priority' => 50, );
			$return[] = ElggMenuItem::factory($options);
		}
	}

	return $return;
}

/**
 * Add a like button to river actions
 */
function thumbs_river_menu_setup($hook, $type, $return, $params) {
	if (elgg_is_logged_in()) {
		$item = $params['item'];

		// only like group creation #3958
		if ($item -> type == "group" && $item -> view != "river/group/create") {
			return $return;
		}

		// don't like users #4116
		if ($item -> type == "user") {
			return $return;
		}

		$object = $item -> getObjectEntity();
		if (!elgg_in_context('widgets') && $item -> annotation_id == 0) {
			if ($object -> canAnnotate(0, 'thumbs:up')) {
				// up button
				$options = array('name' => 'thumbs:up', 'text' => elgg_view('thumbs/button-up', array('entity' => $object)), 'href' => false, 'priority' => 98, );
				$return[] = ElggMenuItem::factory($options);

				// down button
				$options = array('name' => 'thumbs:down', 'text' => elgg_view('thumbs/button-down', array('entity' => $object)), 'href' => false, 'priority' => 99, );
				$return[] = ElggMenuItem::factory($options);

				// count
				$count = elgg_view('thumbs/count', array('entity' => $object));
				if ($count) {
					$options = array('name' => 'thumbs:count', 'text' => $count, 'href' => false, 'priority' => 90, );
					$return[] = ElggMenuItem::factory($options);
				}
			}
		}
	}
	//Remove comments link
	foreach ($return as $key => $item) {
		if ($item -> getName() == 'comment') {
			unset($return[$key]);
		}
	}

	return $return;
}


/**
 * Count how many people have voted up and entity
 *
 * @param  ElggEntity $entity
 */
function thumbs_up_count($entity) {
	$type = $entity -> getType();
	$params = array('entity' => $entity);

	return $entity -> countAnnotations('thumbs:up');

}

function thumbs_down_count($entity) {
	$type = $entity -> getType();
	$params = array('entity' => $entity);

	return $entity -> countAnnotations('thumbs:down');
}

/**
 * Returns trending entities guids based on how many thumbs up they recieve
 *
 */
function thumbs_trending($return_type = 'guids') {
	$options = array('annotation_names' => 'thumbs:up');
	$entities = elgg_get_entities_from_annotation_calculation($options);

	if ($return_type == 'guids') {
		foreach ($entities as $entity) {
			if ($entity -> countAnnotations('thumbs:up') > 3) {
				$guids[] = $entity -> guid;
			}
		}
		return $guids;
	} elseif ($return_type == 'up_count') {
		foreach ($entities as $entity) {
			$guids[$entity -> guid] = $entity -> countAnnotations('thumbs:up');
		}
		return $guids;
	}
}

/**
 * Returns entity guids for everything a user has every thumbed up
 *
 */
function thumbs_up_history() {
	$options = array('annotation_names' => 'thumbs:up', 'annotation_owner_guid' => elgg_get_logged_in_user_guid());
	$entities = elgg_get_entities_from_annotation_calculation($options);

	foreach ($entities as $entity) {
		$guids[] = $entity -> guid;
	}

	return $guids;
}

/**
 * Returns entity guids for everything a user has every thumbed down
 *
 */
function thumbs_down_history() {
	$options = array('annotation_names' => 'thumbs:down', 'annotation_owner_guid' => elgg_get_logged_in_user_guid());
	$entities = elgg_get_entities_from_annotation_calculation($options);

	foreach ($entities as $entity) {
		$guids[] = $entity -> guid;
	}

	return $guids;
}
