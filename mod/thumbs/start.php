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

	// registered with priority < 500 so other plugins can remove likes
	elgg_register_plugin_hook_handler('register', 'menu:river', 'thumbs_river_menu_setup', 400);
	elgg_register_plugin_hook_handler('register', 'menu:entity', 'thumbs_entity_menu_setup', 400);

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
	
	if($entity->type != "group"){

		// likes button
		$options = array(
			'name' => 'thumbs:up',
			'text' => elgg_view('thumbs/button-up', array('entity' => $entity)),
			'href' => false,
			'priority' => 1000,
		);
		$return[] = ElggMenuItem::factory($options);
	
		// likes count
		$count = elgg_view('likes/count', array('entity' => $entity));
		if ($count) {
			$options = array(
				'name' => 'thumbs:count',
				'text' => $count,
				'href' => false,
				'priority' => 1001,
			);
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
		if ($item->type == "group" && $item->view != "river/group/create") {
			return $return;
		}

		// don't like users #4116
		if ($item->type == "user") {
			return $return;
		}
		
		$object = $item->getObjectEntity();
		if (!elgg_in_context('widgets') && $item->annotation_id == 0) {
			if ($object->canAnnotate(0, 'thumbs:up')) {
				// up button
				$options = array(
					'name' => 'thumbs:up',
					'text' => elgg_view('thumbs/button-up', array('entity' => $object)),
					'href' => false,
					'priority' => 100,
				);
				$return[] = ElggMenuItem::factory($options);
				
				// down button
				$options = array(
					'name' => 'thumbs:down',
					'text' => elgg_view('thumbs/button-down', array('entity' => $object)),
					'href' => false,
					'priority' => 150,
				);
				$return[] = ElggMenuItem::factory($options);

				// count
				$count = elgg_view('thumbs/count', array('entity' => $object));
				if ($count) {
					$options = array(
						'name' => 'thumbs:count',
						'text' => $count,
						'href' => false,
						'priority' => 90,
					);
				$return[] = ElggMenuItem::factory($options);
				}
			}
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
	$type = $entity->getType();
	$params = array('entity' => $entity);

	
	return $entity->countAnnotations('thumbs:up');

}
function thumbs_down_count($entity) {
	$type = $entity->getType();
	$params = array('entity' => $entity);
	
	return $entity->countAnnotations('thumbs:down');
}