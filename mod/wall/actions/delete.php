<?php
/**
 * Action for deleting a wall post
 * 
 */

// Get input data
$guid = (int) get_input('guid');

// Make sure we actually have permission to edit
$post = get_entity($guid);
if ($post->getSubtype() == "wallpost" && $post->canEdit()) {

	// Get owning user
	$owner = get_entity($post->getOwnerGUID());

	// Delete it
	$rowsaffected = $post->delete();
	if ($rowsaffected > 0) {
		// Success message
		system_message(elgg_echo("wall:deleted"));
	} else {
		register_error(elgg_echo("wall:notdeleted"));
	}

	forward(REFERER);
}
