<?php

require(dirname(dirname(__FILE__)).'/engine/start.php');

if (!elgg_is_admin_logged_in()) {
	forward();
}

set_time_limit(0);

$offset = "";

while(1) {
	$albums = elgg_get_entities(array(
		'type' => 'object', 
		'subtype' => 'album',
		'offset' => $offset,
		'limit' => 100
	));

	$new_offset = end($albums)->guid;

	if ($new_offset != $offset) {
		$offset = $new_offset;
	} else {
		break;
	}

	// Process all site albums
	foreach($albums as $album) {

		// Clear image list
		$album->orderedImages = serialize(array());

		$img_offset = "";
		while (1) {
			$images = elgg_get_entities(array(
				'type' => 'object',
				'subtype' => 'image',
				'offset' => $img_offset,
				'container_guid' => $album->guid,
				'limit' => 100,
				'newest_first' => false
			));

			$new_img_offset = end($images)->guid;

			if ($new_img_offset != $img_offset) {
				$img_offset = $new_img_offset;
			} else {
				break;
			}

			// Process all album images
			foreach($images as $image) {
				// Add image back to the image list
				$album->prependImageList(array($image->guid));
			}
		}
	}	
}