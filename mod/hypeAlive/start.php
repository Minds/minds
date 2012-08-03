<?php

/* hypeAlive
 *
 * Comments
 * Likes
 * Notifications
 * River
 *
 * @package hypeJunction
 * @subpackage hypeAlive
 *
 * @author Ismayil Khayredinov <ismayil.khayredinov@gmail.com>
 * @copyright Copyrigh (c) 2011, Ismayil Khayredinov
 */

elgg_register_event_handler('init', 'system', 'hj_alive_init');

/**
 * Initialize hypeAlive
 */
function hj_alive_init() {

	$plugin = 'hypeAlive';

	if (!elgg_is_active_plugin('hypeFramework')) {
		register_error(elgg_echo('hj:framework:disabled', array($plugin, $plugin)));
		disable_plugin($plugin);
	}

	$shortcuts = hj_framework_path_shortcuts($plugin);

	// Helper Classes
	elgg_register_classes($shortcuts['classes']);

	// Register Libraries
	elgg_register_library('hj:alive:comments:base', $shortcuts['lib'] . 'comments/base.php');
	elgg_load_library('hj:alive:comments:base');

	elgg_register_library('hj:alive:setup', $shortcuts['lib'] . 'alive/setup.php');

	//Check if the initial setup has been performed, if not porform it
	if (!elgg_get_plugin_setting('hj:alive:setup')) {
		elgg_load_library('hj:alive:setup');
		if (hj_alive_setup())
			system_message('hypeAlive was successfully configured');
	}

	elgg_register_action('alive/reset', $shortcuts['actions'] . 'hj/alive/reset.php', 'admin');
	elgg_register_action('comment/import', $shortcuts['actions'] . 'hj/comment/import.php', 'admin');

	hj_alive_river_init();
	hj_alive_comments_init();
	hj_alive_likes_init();

	if (elgg_get_plugin_setting('livesearch') != 'off') {
		hj_alive_search_init();
	}

	elgg_register_plugin_hook_handler('hj:notification:setting', 'annotation', 'hj_alive_notification_settings');
}

/* =========================================
 *  RIVER
 * ========================================= */

function hj_alive_river_init() {
	$plugin = 'hypeAlive';

	$shortcuts = hj_framework_path_shortcuts($plugin);

	// Register JS and CSS libraries
	$alive_js = elgg_get_simplecache_url('js', 'hj/river/base');
	elgg_register_js('hj.river.base', $alive_js);

	elgg_unregister_page_handler('activity', 'elgg_river_page_handler');
	elgg_register_page_handler('activity', 'hj_alive_river_page_handler');
}

function hj_alive_river_page_handler($page) {
	$plugin = 'hypeAlive';
	$shortcuts = hj_framework_path_shortcuts($plugin);

	elgg_set_page_owner_guid(elgg_get_logged_in_user_guid());

	// make a URL segment available in page handler script
	$page_type = elgg_extract(0, $page, 'all');
	$page_type = preg_replace('[\W]', '', $page_type);
	if ($page_type == 'owner') {
		$page_type = 'mine';
	}
	set_input('page_type', $page_type);

	// content filter code here
	$entity_type = '';
	$entity_subtype = '';

	require_once("{$shortcuts['pages']}/river.php");
	return true;
}

/* =========================================
 * COMMENTS & LIKES
 * ========================================= */

function hj_alive_comments_init() {
	$plugin = 'hypeAlive';

	$shortcuts = hj_framework_path_shortcuts($plugin);

	// Actions
	elgg_register_action('comment/get', $shortcuts['actions'] . 'hj/comment/get.php');
	elgg_register_action('comment/save', $shortcuts['actions'] . 'hj/comment/save.php');

	elgg_register_action('like/get', $shortcuts['actions'] . 'hj/like/get.php');
	elgg_register_action('like/save', $shortcuts['actions'] . 'hj/like/save.php');
	elgg_register_action('like/save-dislike', $shortcuts['actions'] . 'hj/like/save-dislike.php');

	// Register JS and CSS libraries
	$css_url = elgg_get_simplecache_url('css', 'hj/comments/base');
	elgg_register_css('hj.comments.base', $css_url);

	$js_generic_url = elgg_get_simplecache_url('js', 'hj/comments/base');
	elgg_register_js('hj.comments.base', $js_generic_url);

	$js_likes_url = elgg_get_simplecache_url('js', 'hj/likes/base');
	elgg_register_js('hj.likes.base', $js_likes_url);

	elgg_load_css('hj.comments.base');
	if (elgg_is_logged_in()) {
		elgg_load_js('hj.comments.base');
		elgg_load_js('hj.likes.base');
	}

	// Register a hook to replace Elgg comments with hypeAlive
	if (elgg_get_plugin_setting('entity_comments', 'hypeAlive') !== 'off') {
		elgg_register_plugin_hook_handler('comments', 'all', 'hj_alive_comments_replacement');
		// Search comments
		elgg_unregister_plugin_hook_handler('search', 'comments', 'search_comments_hook');
		elgg_register_plugin_hook_handler('search', 'comments', 'hj_alive_search_comments_hook');
		if (elgg_get_context() !== 'activity') {
			//elgg_unregister_plugin_hook_handler('register', 'menu:entity', 'likes_entity_menu_setup');
		}
	}

	if (elgg_get_plugin_setting('river_comments', 'hypeAlive') !== 'off') {
		elgg_unregister_plugin_hook_handler('register', 'menu:river', 'elgg_river_menu_setup');
		//elgg_unregister_plugin_hook_handler('register', 'menu:river', 'likes_river_menu_setup');
		elgg_unregister_plugin_hook_handler('register', 'menu:river', 'discussion_add_to_river_menu');
		if (elgg_get_context() == 'activity') {
			//elgg_unregister_plugin_hook_handler('register', 'menu:entity', 'likes_entity_menu_setup');
		}
	}

	elgg_register_plugin_hook_handler('register', 'menu:comments', 'hj_alive_comments_menu');
	elgg_register_plugin_hook_handler('register', 'menu:commentshead', 'hj_alive_commentshead_menu');
}

function hj_alive_likes_init() {
	$plugin = 'hypeAlive';

	$shortcuts = hj_framework_path_shortcuts($plugin);

	// Register Libraries
	elgg_register_library('hj:alive:likes:base', $shortcuts['lib'] . 'likes/base.php');
	elgg_load_library('hj:alive:likes:base');

	$js_generic_url = elgg_get_simplecache_url('js', 'hj/likes/base');
	elgg_register_js('hj.likes.base', $js_generic_url);
}

/**
 *  Replaces native Elgg comments with hypeAlive Comments
 */
function hj_alive_comments_replacement($hook, $entity_type, $returnvalue, $params) {
	return elgg_view('hj/comments/bar', $params);
}

function hj_alive_comments_menu($hook, $type, $return, $params) {
	$entity = elgg_extract('entity', $params, false);
	$container_guid = elgg_extract('container_guid', $params['params'], null);
	$river_id = elgg_extract('river_id', $params['params'], null);

	if (!$guid = $container_guid) {
		$guid = $river_id;
	}

	if (!$entity) {
		return $return;
	}
	unset($return);

	/**
	 * TimeStamp
	 */
	if (elgg_instanceof($entity, 'object', 'hjannotation') && $timestamp = $entity->time_created) {
		$time = array(
			'handler' => $handler,
			'name' => 'time',
			'entity' => $entity,
			'text' => elgg_view_friendly_time($timestamp),
			'href' => false,
			'priority' => 500
		);
		$return[] = ElggMenuItem::factory($time);
	}

	/**
	 * Like / Unlike
	 */
	/*if ($entity->getType() == 'river') {
		$show_like = true;
	} else if (elgg_instanceof($entity, 'object', 'groupforumtopic')) {
		$container = get_entity($entity->container_guid);
		$show_like = $container->canWriteToContainer();
	} else if ($entity->canAnnotate()) {
		$show_like = true;
	}*/
	if ($show_like) {
		unset($params['entity']);
		$likes_owner = hj_alive_does_user_like($params['params']);
		$likes_owner = $likes_owner['self'];

		if ($likes_owner) {
			$likes_class = "hidden";
			$unlikes_class = "visible";
		} else {
			$unlikes_class = "hidden";
			$likes_class = "visible";
		}
		$likes = array(
			'name' => 'like',
			'text' => elgg_view_icon('thumbs-up'),
			'entity' => $entity,
			'title' => elgg_echo('hj:alive:comments:likebutton'),
			'class' => $likes_class,
			'rel' => 'like',
			'priority' => 100
		);
		$unlikes = array(
			'name' => 'unlike',
			'text' => elgg_view_icon('thumbs-down'),
			'entity' => $entity,
			'title' => elgg_echo('hj:alive:comments:unlikebutton'),
			'class' => $unlikes_class,
			'rel' => 'unlike',
			'priority' => 105
		);

		$return[] = ElggMenuItem::factory($likes);
		$return[] = ElggMenuItem::factory($unlikes);
		
		$dislikes_owner = hj_alive_does_user_dislike($params['params']);
		$dislikes_owner = $dislikes_owner['self'];
		
		if ($dislikes_owner) {
			$dislikes_class = "hidden";
			$undislikes_class = "visible";
		} else {
			$undislikes_class = "hidden";
			$dislikes_class = "visible";
		}
		$dislikes = array(
			'name' => 'dislike',
			'text' => elgg_view_icon('thumbs-down'),
			'entity' => $entity,
			'title' => elgg_echo('hj:alive:comments:dislikebutton'),
			'class' => $dislikes_class,
			'rel' => 'dislike',
			'priority' => 110
		);
		$undislikes = array(
			'name' => 'undislike',
			'text' => elgg_view_icon('thumbs-up'),
			'entity' => $entity,
			'title' => elgg_echo('hj:alive:comments:undislikebutton'),
			'class' => $undislikes_class,
			'rel' => 'undislike',
			'priority' => 115
		);

		$return[] = ElggMenuItem::factory($dislikes);
		$return[] = ElggMenuItem::factory($undislikes);
	}

	/**
	 * Comment
	 */
	if ($entity->getType() == 'river') {
		$show_comment = true;
	} else if (elgg_instanceof($entity, 'object', 'groupforumtopic')) {
		$container = get_entity($entity->container_guid);
		$show_comment = $container->canWriteToContainer();
	} else if ($entity->canComment() || $entity->canAnnotate()) {
		$show_comment = true;
	}

	if ($show_comment) {
		$comment = array(
			'name' => 'comment',
			'text' => elgg_echo('hj:alive:comments:commentsbutton'),
			'entity' => $entity,
			'priority' => 200
		);
		$return[] = ElggMenuItem::factory($comment);
	}

	return $return;
}

function hj_alive_commentshead_menu($hook, $type, $return, $params) {
	$entity = elgg_extract('entity', $params, false);

	if (!$entity && !elgg_instanceof($entity, 'object', 'hjannotation')) {
		return $return;
	}
	unset($return);

	/**
	 * Delete
	 */
	if (($entity->canEdit())) {
		$delete = array(
			'name' => 'delete',
			'text' => elgg_view_icon('delete'),
			'entity' => $entity,
			'class' => 'hj-ajaxed-remove hidden',
			'id' => "hj-ajaxed-remove-$entity->guid",
			'href' => "action/framework/entities/delete?e=$entity->guid",
			'is_action' => true,
			'priority' => 1000
		);
		$return[] = ElggMenuItem::factory($delete);
	}

	return $return;
}

/* ================================
 * LIVESEARCH
  ================================ */

function hj_alive_search_init() {
	$plugin = 'hypeAlive';
	$shortcuts = hj_framework_path_shortcuts($plugin);

	elgg_register_action('livesearch/parse', $shortcuts['actions'] . 'hj/livesearch/parse.php', 'public');

	elgg_extend_view('css/elements/modules', 'css/hj/livesearch/base');

	$js = elgg_get_simplecache_url('js', 'hj/livesearch/autocomplete');
	elgg_register_js('hj.livesearch.autocomplete', $js, 'footer');

	elgg_load_css('hj.framework.jquitheme');

//    if (elgg_get_context() != 'admin') {
//        elgg_load_js('hj.livesearch.autocomplete');
//        elgg_load_css('hj.framework.jquitheme');
//    }
}

function hj_alive_search_comments_hook($hook, $type, $value, $params) {
	$db_prefix = elgg_get_config('dbprefix');

	$query = sanitise_string($params['query']);
	$limit = sanitise_int($params['limit']);
	$offset = sanitise_int($params['offset']);

	$params['type_subtype_pairs'] = array('object' => 'hjannotation');
	$params['metadata_name_value_pairs'] = array(
		'name' => 'annotation_name', 'value' => array('generic_comment', 'group_topic_post'), 'operand' => '='
	);

	$params['joins'] = array(
		"JOIN {$db_prefix}metadata md on e.guid = md.entity_guid",
		"JOIN {$db_prefix}metastrings msn_n on md.name_id = msn_n.id",
		"JOIN {$db_prefix}metastrings msv_n on md.value_id = msv_n.id"
	);

	$fields = array('string');
	$params['wheres'] = array(
		"(msn_n.string = 'annotation_value')",
		search_get_where_sql('msv_n', $fields, $params, FALSE)
	);

	$params['count'] = TRUE;
	$count = elgg_get_entities_from_metadata($params);

	// no need to continue if nothing here.
	if (!$count) {
		return array('entities' => array(), 'count' => $count);
	}

	$params['count'] = FALSE;
	$entities = elgg_get_entities_from_metadata($params);

	// add the volatile data for why these entities have been returned.
	foreach ($entities as $key => $entity) {
		$desc = search_get_highlighted_relevant_substrings($entity->annotation_value, $params['query']);
		$entity->setVolatileData('search_annotation_value', $desc);
	}

	return array(
		'entities' => $entities,
		'count' => $count,
	);
}

function hj_alive_notification_settings($hook, $type, $return, $params) {

	$notify_settings = elgg_get_plugin_setting('notifications', 'hypeAlive');
	$notify_settings = explode(',', $notify_settings);
	foreach ($notify_settings as $key => $setting) {
		$return[] = trim($setting);
	}

	return $return;
}