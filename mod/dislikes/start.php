<?php
/**
 * dislikes plugin
 *
 */

elgg_register_event_handler('init', 'system', 'dislikes_init');

function dislikes_init() {

	elgg_extend_view('css/elgg', 'dislikes/css');
	elgg_extend_view('js/elgg', 'dislikes/js');

	// registered with priority < 500 so other plugins can remove dislikes
	elgg_register_plugin_hook_handler('register', 'menu:river', 'dislikes_river_menu_setup', 400);
	elgg_register_plugin_hook_handler('register', 'menu:entity', 'dislikes_entity_menu_setup', 400);

	$actions_base = elgg_get_plugins_path() . 'dislikes/actions/dislikes';
	elgg_register_action('dislikes/add', "$actions_base/add.php");
	elgg_register_action('dislikes/delete', "$actions_base/delete.php");
}

/**
 * Add dislikes to entity menu at end of the menu
 */
function dislikes_entity_menu_setup($hook, $type, $return, $params) {
	if (elgg_in_context('widgets')) {
		return $return;
	}

	$entity = $params['entity'];

	// dislikes button
	$options = array(
		'name' => 'dislikes',
		'text' => elgg_view('dislikes/button', array('entity' => $entity)),
		'href' => false,
		'priority' => 1000,
	);
	$return[] = ElggMenuItem::factory($options);

	// dislikes count
	$count = elgg_view('dislikes/count', array('entity' => $entity));
	if ($count) {
		$options = array(
			'name' => 'dislikes_count',
			'text' => $count,
			'href' => false,
			'priority' => 1001,
		);
		$return[] = ElggMenuItem::factory($options);
	}

	return $return;
}

/**
 * Add a dislike button to river actions
 */
function dislikes_river_menu_setup($hook, $type, $return, $params) {
	if (elgg_is_logged_in()) {
		$item = $params['item'];

		// only dislike group creation #3958
		if ($item->type == "group" && $item->view != "river/group/create") {
			return $return;
		}

		// don't dislike users #4116
		if ($item->type == "user") {
			return $return;
		}
		
		$object = $item->getObjectEntity();
		if (!elgg_in_context('widgets') && $item->annotation_id == 0) {
			if ($object->canAnnotate(0, 'dislikes')) {
				// dislike button
				$options = array(
					'name' => 'dislikes',
					'href' => false,
					'text' => elgg_view('dislikes/button', array('entity' => $object)),
					'is_action' => true,
					'priority' => 100,
				);
				$return[] = ElggMenuItem::factory($options);

				// dislikes count
				$count = elgg_view('dislikes/count', array('entity' => $object));
				if ($count) {
					$options = array(
						'name' => 'dislikes_count',
						'text' => $count,
						'href' => false,
						'priority' => 101,
					);
					$return[] = ElggMenuItem::factory($options);
				}
			}
		}
	}

	return $return;
}

/**
 * Count how many people have disliked an entity.
 *
 * @param  ElggEntity $entity
 *
 * @return int Number of dislikes
 */
function dislikes_count($entity) {
	$type = $entity->getType();
	$params = array('entity' => $entity);
	$number = elgg_trigger_plugin_hook('dislikes:count', $type, $params, false);

	if ($number) {
		return $number;
	} else {
		return $entity->countAnnotations('dislikes');
	}
}

/**
 * Notify $user that $disliker disliked his $entity.
 *
 * @param type $user
 * @param type $disliker
 * @param type $entity 
 */
function dislikes_notify_user(ElggUser $user, ElggUser $disliker, ElggEntity $entity) {
	
	if (!$user instanceof ElggUser) {
		return false;
	}
	
	if (!$disliker instanceof ElggUser) {
		return false;
	}
	
	if (!$entity instanceof ElggEntity) {
		return false;
	}
	
	$title_str = $entity->title;
	if (!$title_str) {
		$title_str = elgg_get_excerpt($entity->description);
	}

	$site = get_config('site');

	$subject = elgg_echo('dislikes:notifications:subject', array(
					$disliker->name,
					$title_str
				));

	$body = elgg_echo('dislikes:notifications:body', array(
					$user->name,
					$disliker->name,
					$title_str,
					$site->name,
					$entity->getURL(),
					$disliker->getURL()
				));

	notify_user($user->guid,
				$disliker->guid,
				$subject,
				$body
			);
}