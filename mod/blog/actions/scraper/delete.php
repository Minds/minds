<?php
/**
 * Delete scraper
 */

$scraper_guid = get_input('guid');
$scraper = get_entity($scraper_guid,'object');

if (elgg_instanceof($scraper, 'object', 'scraper') && $scraper->canEdit()) {
	$scraper->delete();
} else {
	register_error(elgg_echo('you are not allowed to delete this'));
}

forward(REFERER);
