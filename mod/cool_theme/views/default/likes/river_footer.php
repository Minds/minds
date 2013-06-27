<?php
/**
 * Elgg likes display
 *
 * @uses $vars['entity']
 */

if (!$vars['item'] instanceof ElggRiverItem || $vars['item']->annotation_id) {
	return true;
}

$object = $vars['item']->getObjectEntity();

$num_of_likes = $object->countAnnotations('likes');

if ($num_of_likes == 0) {
	return true;
}

$guid = $object->guid;

$likes_button = elgg_view_icon('thumbs-up');

// check to see if the user has already liked this
if (elgg_is_logged_in() && $object->canAnnotate(0, 'likes')) {
	if (!elgg_annotation_exists($guid, 'likes')) {
		$likes_button = elgg_view('output/url', array(
			'href' => "action/likes/add?guid={$guid}",
			'text' => elgg_view_icon('thumbs-up'),
			'title' => elgg_echo('likes:likethis'),
			'is_action' => true,
		));
	} else {
		$likes = elgg_get_annotations(array(
			'guid' => $guid,
			'annotation_name' => 'likes',
			'owner_guid' => elgg_get_logged_in_user_guid()
		));

		$likes_button = elgg_view('output/url', array(
			'href' => "action/likes/delete?annotation_id={$likes[0]->id}",
			'text' => elgg_view_icon('thumbs-up-alt'),
			'title' => elgg_echo('likes:remove'),
			'is_action' => true,
		));
	}
}

// display the number of likes
if ($num_of_likes == 1) {
	$likes_string = elgg_echo('likes:userlikedthis', array($num_of_likes));
} else {
	$likes_string = elgg_echo('likes:userslikedthis', array($num_of_likes));
}

$likes_string = elgg_view('output/url', array(
	'text' => $likes_string,
	'title' => elgg_echo('likes:see'),
	'rel' => 'popup',
	'href' => "#likes-$guid"
));

$likes_list = elgg_list_annotations(array('guid' => $guid, 'annotation_name' => 'likes', 'limit' => 99));

$likes_module = elgg_view_module('popup', 'Likes', $likes_list, array('class' => 'hidden elgg-likes-list', 'id' => "likes-$guid"));

$vars['image'] = $likes_button;
$vars['body'] = $likes_string . $likes_module;
$vars['class'] = 'elgg-river-participation';

echo elgg_view('page/components/image_block', $vars);