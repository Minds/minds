<?php

$notification = elgg_extract('entity', $vars);
$params = unserialize($notification->params);
$type = $params['type'] ? $params['type'] : 'entity';
$actor = get_entity($notification -> from_guid, 'user');

if ($type == 'entity') {
	$object = get_entity($notification -> object_guid, 'object');
	if ($object) {
		//$objectOwner = get_entity($object->getOwnerGUID());
		$subtype = $object -> getSubtype();
		if ($subtype == 'wallpost' && $notification -> to_guid == $object -> getOwnerGUID()) {
			$object_title = 'your post';
		} elseif ($subtype == 'wallpost') {
			if ($entity -> from_guid == $object -> getOwnerGUID()) {
				$object_title = 'their own post';
			} else {
				$object_title = $objectOwner -> name . '\'s post';
			}
		} elseif ($subtype == 'wallpost') {
			$object_title = 'a wall post';
		} elseif ($object -> river_id) {
			$object_title = 'a post';
		} else {
			$object_title = $object -> title;
		}
		$object_url = $object -> getURL();
	}
} elseif ($type == 'river') {
	//elgg_view('output/url', array('href' => elgg_get_site_url() . 'news/single?id=' . $entity -> object_guid, 'text' => ' commented'))
	$object_url = elgg_get_site_url() . 'news/single?id=' . $notification -> object_guid;
	$object_title = 'a post';
}

$description = $notification -> description;
if (strlen($description) > 60) {
	$description = substr($notification -> description, 0, 75) . '...';
}

$body .= elgg_view('output/url', array('href' => $actor -> getURL(), 'text' => $actor -> name));
$body .= ' commented on ';
$body .= elgg_view('output/url', array('href' => $object_url, 'text' => $object_title));

$body .= "<br/>";

$body .= "<div class='notify_description'>" . $description . "</div>";

$body .= "<span class='notify_time'>" . elgg_view_friendly_time($notification-> time_created) . "</span>";

echo $body;
