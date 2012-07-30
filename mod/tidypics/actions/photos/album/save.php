<?php
/**
 * Save album action
 *
 * @author Cash Costello
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU General Public License v2
 */


// Get input data
$title = get_input('title');
$description = get_input('description');
$tags = get_input('tags');
$access_id = get_input('access_id');
$container_guid = get_input('container_guid', elgg_get_logged_in_user_guid());
$guid = get_input('guid');

elgg_make_sticky_form('tidypics');

if (empty($title)) {
	register_error(elgg_echo("album:blank"));
	forward(REFERER);
}

if ($guid) {
	$album = get_entity($guid);
} else {
	$album = new TidypicsAlbum();
}

$album->container_guid = $container_guid;
$album->owner_guid = elgg_get_logged_in_user_guid();
$album->access_id = $access_id;
$album->title = $title;
$album->description = $description;
if ($tags) {
	$album->tags = string_to_tag_array($tags);
}

if (!$album->save()) {
	register_error(elgg_echo("album:error"));
	forward(REFERER);
}

elgg_clear_sticky_form('tidypics');

system_message(elgg_echo("album:created"));
forward($album->getURL());
