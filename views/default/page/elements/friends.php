<?php

//$owner = elgg_set_page_owner_guid(elgg_get_logged_in_user_guid());
$owner = elgg_get_page_owner_entity();

$title = elgg_echo('friends');
	
// number of friends to display
$num = 25;

// icon size
$size = 'tiny';

if (elgg_instanceof($owner, 'user')) {
	$html = $owner->listFriends('', $num, array(
		'size' => $size,
		'pagination' => FALSE,
		'list_type' => 'gallery',
	));
	if ($html) {	
		$content = $html;
	}
}
echo elgg_view_module('featured', $title, $content);
