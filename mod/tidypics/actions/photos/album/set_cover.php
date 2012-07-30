<?php
/**
 * Set album cover image
 */

// Get input data
$album_guid = get_input('album_guid');
$image_guid = get_input('image_guid');

$album = get_entity($album_guid);

if (!elgg_instanceof($album, 'object', 'album')) {
	register_error(elgg_echo('album:invalid_album'));
	forward(REFERER);
}

if ($album->setCoverImageGuid($image_guid)) {
	system_message(elgg_echo('album:save_cover_image'));
	forward(REFERER);
} else {
	register_error(elgg_echo('album:cannot_save_cover_image'));
	forward(REFERER);
}