<?php

/**
 * Most recently uploaded images - individual or world
 *
 */

// Load Elgg engine
include_once dirname(dirname(dirname(dirname(dirname(__FILE__))))) . "/engine/start.php";

// start with assumption this is for all site photos
$title = elgg_echo('tidypics:mostrecent');
$user_id = 0;

// is this all site or an individuals images
$username = get_input('username');
if ($username) {
	$user = get_user_by_username($username);
	if ($user) {
		$user_id = $user->guid;

		if ($user_id == get_loggedin_userid()) {
			$title = elgg_echo('tidypics:yourmostrecent');
		} else {
			$title = sprintf(elgg_echo("tidypics:friendmostrecent"), $user->name);
		}
	}
} else {
	// world view - set page owner to logged in user
	if (isloggedin()) {
		set_page_owner(get_loggedin_userid());
	}
}

// allow other plugins to override the slideshow
$slideshow_link = trigger_plugin_hook('tp_slideshow', 'album', array(), null);
if ($slideshow_link) {
	add_submenu_item(elgg_echo('album:slideshow'),
			$slideshow_link,
			'photos' );
}

// how many do we display
$max = 12;

// grab the html to display the images
$images = elgg_list_entities(array(
	"type" => "object",
	"subtype" => "image",
	"owner_guid" => $user_id,
	"limit" => $max,
	"full_view" => false,
));


// this view takes care of the title on the main column and the content wrapper
$area2 = elgg_view('tidypics/content_wrapper', array('title' => $title, 'content' => $images,));
if (empty($area2)) {
	$area2 = $images;
}

$body = elgg_view_layout('two_column_left_sidebar', '', $area2);

page_draw($title, $body);
