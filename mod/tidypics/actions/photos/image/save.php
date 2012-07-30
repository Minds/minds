<?php
/**
 * Save image action
 *
 * @author Cash Costello
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU General Public License v2
 */

// Get input data
$title = get_input('title');
$description = get_input('description');
$tags = get_input('tags');
$access_id = get_input('access_id');
$guid = get_input('guid');

elgg_make_sticky_form('tidypics');

if (empty($title)) {
	register_error(elgg_echo("image:blank"));
	forward(REFERER);
}

$image = get_entity($guid);

$image->access_id = $access_id;
$image->title = $title;
$image->description = $description;
if ($tags) {
	$image->tags = string_to_tag_array($tags);
}

if (!$image->save()) {
	register_error(elgg_echo("image:error"));
	forward(REFERER);
}

elgg_clear_sticky_form('tidypics');

system_message(elgg_echo("image:saved"));
forward($image->getURL());
