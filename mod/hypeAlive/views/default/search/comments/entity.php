<?php

/**
 * Default search view for a comment
 *
 * @uses $vars['entity']
 */
$entity = $vars['entity'];
if (!elgg_instanceof($entity, 'object', 'hjannotation')) {
	$owner = get_entity($entity->getVolatileData('search_matched_comment_owner_guid'));

	if ($owner instanceof ElggUser) {
		$icon = elgg_view_entity_icon($owner, 'tiny');
	} else {
		$icon = '';
	}

// @todo Sometimes we find comments on entities we can't display...
	if ($entity->getVolatileData('search_unavailable_entity')) {
		$title = elgg_echo('search:comment_on', array(elgg_echo('search:unavailable_entity')));
		// keep anchor for formatting.
		$title = "<a>$title</a>";
	} else {
		if ($entity->getType() == 'object') {
			$title = $entity->title;
		} else {
			$title = $entity->name;
		}

		if (!$title) {
			$title = elgg_echo('item:' . $entity->getType() . ':' . $entity->getSubtype());
		}

		if (!$title) {
			$title = elgg_echo('item:' . $entity->getType());
		}

		$title = elgg_echo('search:comment_on', array($title));

		// @todo this should use something like $comment->getURL()
		$url = $entity->getURL() . '#comment_' . $entity->getVolatileData('search_match_annotation_id');
		$title = "<a href=\"$url\">$title</a>";
	}

	$description = $entity->getVolatileData('search_matched_comment');
	$tc = $entity->getVolatileData('search_matched_comment_time_created');
	;
	$time = elgg_view_friendly_time($tc);

	$body = "<p class=\"mbn\">$title</p>$description";
	$body .= "<p class=\"elgg-subtext\">$time</p>";

	echo elgg_view_image_block($icon, $body);
} else {
	switch ($entity->annotation_name) {

		case 'generic_comment' :
			$container = $entity->findOriginalContainer();

			if (elgg_instanceof($container, 'object', 'groupforumtopic')) {
				$title = $container->title;
				$group = $container->getContainerEntity();
				$title = elgg_echo('hj:alive:reply_to', array($title, $group->name));
				$url = $container->getURL();
				$title = "<a href=\"$url\">$title</a>";
			} else if (elgg_instanceof($container)) {
				$title = $container->title;
				$title = elgg_echo('search:comment_on', array($title));
				$url = $container->getURL();
				$title = "<a href=\"$url\">$title</a>";
			} else {
				$title = elgg_echo('hj:alive:comment_on:river', array(elgg_view('river/elements/summary', array('item' => $container))));
			}

			$owner = get_entity($entity->owner_guid);
			$icon = elgg_view_entity_icon($owner, 'tiny');

			$description = $entity->getVolatileData('search_annotation_value');
			$tc = $entity->time_created;
			$time = elgg_view_friendly_time($tc);

			$body = "<p class=\"mbn\">$title</p>";
			$body .= "$river_item";
			$body .= "<p>$description</p>";
			$body .= "<p class=\"elgg-subtext\">$time</p>";

			echo elgg_view_image_block($icon, $body);

			break;

		case 'group_topic_post' :
			/*$container = $entity->findOriginalContainer();

			if (elgg_instanceof($container)) {
				$title = $container->title;
				$group = $container->getContainerEntity();
				$title = elgg_echo('hj:alive:reply_to', array($title, $group->name));
				$url = $container->getURL();
				$title = "<a href=\"$url\">$title</a>";
			}

			$owner = get_entity($entity->owner_guid);
			$icon = elgg_view_entity_icon($owner, 'tiny');

			$description = $entity->getVolatileData('search_annotation_value');
			$tc = $entity->time_created;
			$time = elgg_view_friendly_time($tc);

			$body = "<p class=\"mbn\">$title</p>";
			$body .= "<p>$description</p>";
			$body .= "<p class=\"elgg-subtext\">$time</p>";

			echo elgg_view_image_block($icon, $body);*/

			break;

		default :
			echo $entity->annotation_name;
			return true;
			break;
	}
}