<?php
/**
 * Edit the images in a batch
 *
 * @author Cash Costello
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU General Public License v2
 */

$guids = get_input('guid');
$titles = get_input('title');
$captions = get_input('caption');
$tags = get_input('tags');

$not_updated = array();
foreach ($guids as $key => $guid) {
	$image = get_entity($guid);

	if ($image->canEdit()) {

		// set title appropriately
		if ($titles[$key]) {
			$image->title = $titles[$key];
		} else {
			$image->title = substr($image->originalfilename, 0, strrpos($image->originalfilename, '.'));
		}

		// set description appropriately
		$image->description = $captions[$key];
		$image->tags = string_to_tag_array($tags[$key]);

		if (!$image->save()) {
			array_push($not_updated, $image->getGUID());
		}
	}
}

if (count($not_updated) > 0) {
	register_error(elgg_echo("images:notedited"));
} else {
	system_message(elgg_echo("images:edited"));
}
forward($image->getContainerEntity()->getURL());
