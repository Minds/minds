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
	
	$path = elgg_get_plugins_path() . 'hypeAlive/';
	
	$hj_js_ajax = elgg_get_simplecache_url('js', 'hj/framework/ajax');
    elgg_register_js('hj.framework.ajax', $hj_js_ajax);
    elgg_load_js('hj.framework.ajax');

	elgg_register_plugin_hook_handler('comments', 'all', 'hj_alive_comments_replacement');

	/* Comments
	 */
	// Register Libraries
	elgg_register_library('hj:alive:comments:base', $path . 'lib/comments/base.php');
	elgg_load_library('hj:alive:comments:base');
	// Register actions
	elgg_register_action('comment/get', $path. 'actions/hj/comment/get.php');
	elgg_register_action('comment/save', $path . 'actions/hj/comment/save.php');
	
	// Register JS and CSS libraries
	$css_url = elgg_get_simplecache_url('css', 'hj/comments/base');
	elgg_register_css('hj.comments.base', $css_url);
	
	$js_generic_url = elgg_get_simplecache_url('js', 'hj/comments/base');
	elgg_register_js('hj.comments.base', $js_generic_url);
	
	elgg_register_plugin_hook_handler('register', 'menu:comments', 'minds_comments_menu');
	elgg_register_plugin_hook_handler('register', 'menu:commentshead', 'minds_commentshead_menu');

	/* Likes
	 */
	// Register Libraries
	elgg_register_library('hj:alive:likes:base', $path . '/lib/likes/base.php');
	elgg_load_library('hj:alive:likes:base');
	// Register actions
	elgg_register_action('like/get', $path . 'actions/hj/like/get.php');
	elgg_register_action('like/save', $path . 'actions/hj/like/save.php');
	elgg_register_action('like/save-dislike', $path . 'actions/hj/like/save-dislike.php');
	
	// Register JS and CSS libraries
	$js_likes_url = elgg_get_simplecache_url('js', 'hj/likes/base');
	elgg_register_js('hj.likes.base', $js_likes_url);
	
	
	elgg_load_css('hj.comments.base');
	if (elgg_is_logged_in()) {
		elgg_load_js('hj.likes.base');
	}
}





function minds_comments_menu($hook, $type, $return, $params) {
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

function minds_commentshead_menu($hook, $type, $return, $params) {
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

/**
 *  Replaces native Elgg comments with hypeAlive Comments
 */
function hj_alive_comments_replacement($hook, $entity_type, $returnvalue, $params) {
	
    $comments =  elgg_view('hj/comments/bar', array(
	    'entity' => $params['entity'],
	));
	
	$comments .= elgg_view('hj/comments/input', array(
	    'entity' => $params['entity'],
		'container_guid' => elgg_get_logged_in_user_guid(),
		'aname' => elgg_extract('aname', $vars, 'generic_comment')
	));
	
	return $comments;
}