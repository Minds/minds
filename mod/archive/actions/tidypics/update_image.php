<?php
/**
 * Update image field action
 */

$title = get_input('title', NULL);
$description = get_input('description', NULL);
$tags = get_input('tags', NULL);
$entity_guid = get_input('entity_guid', NULL);

$image = get_entity($entity_guid);

if (!elgg_instanceof($image, 'object', 'image') || !$image->canEdit()) {
	register_error(elgg_echo('archive:invalid_image'));
	forward(REFERER);
}

if (!$title && !$description && !$tags) {
	register_error(elgg_echo('image:error'));
}

$updated = FALSE;

if ($title && !empty($title)) {
	$image->title = $title;
	$updated = $title;
}

if ($description) {
	$image->description = $description;
	$updated = $description;
}

if ($tags) {
	$image->tags = $tags;
	$updated = elgg_view('output/tags', array('tags' => $image->tags));
}

// If we updated a field, save and return the value
if ($updated) {
	if ($image->save()) {
		echo $updated;
	} else {
		// Failed to save
		register_error(elgg_echo('image:error'));
	}
} else {
	register_error(elgg_echo('image:no_update'));
}

forward(REFERER);