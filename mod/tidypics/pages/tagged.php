<?php
/**
 * Tidypics Tagged Listing
 *
 * List all photos tagged with a user
 */

include_once dirname(dirname(dirname(dirname(__FILE__)))) . "/engine/start.php";


// Get user guid
$guid = get_input('guid');

$user = get_entity($guid);

if ($user) {
	$title = sprintf(elgg_echo('tidypics:usertag'), $user->name);
} else {
	$title = "User does not exist";
}


// create main column
$body = elgg_view_title($title);

set_context('search');
set_input('search_viewtype', 'gallery'); // need to force gallery view
$body .= list_entities_from_relationship('phototag', $guid, false, 'object', 'image', 0, 10, false); 

// Set up submenus
if (isloggedin()) {
	add_submenu_item(	elgg_echo("album:yours"),
			$CONFIG->wwwroot . "pg/photos/owned/" . $_SESSION['user']->username,
			'tidypics-b' );
}
add_submenu_item(	elgg_echo('album:all'), 
		$CONFIG->wwwroot . "pg/photos/world/",
		'tidypics-z');
add_submenu_item(	elgg_echo('tidypics:mostrecent'),
		$CONFIG->wwwroot . 'pg/photos/mostrecent',
		'tidypics-z');



$body = elgg_view_layout('two_column_left_sidebar', '', $body);


page_draw($title, $body);
