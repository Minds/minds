<?php
/**
 * Album river view
 *
 * @author Cash Costello
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU General Public License v2
 */

$album = $vars['item']->getObjectEntity();

$album_river_view = elgg_get_plugin_setting('album_river_view', 'tidypics');
if ($album_river_view == "cover") {
	$image = $album->getCoverImage();
	if ($image) {
		$attachments = elgg_view_entity_icon($image, 'tiny');
	}
} else {
	$images = $album->getImages(7);

	if (count($images)) {
		$attachments = '<ul class="tidypics-river-list">';
		foreach($images as $image) {
			$attachments .= '<li class="tidypics-photo-item">';
			$attachments .= elgg_view_entity_icon($image, 'tiny');
			$attachments .= '</li>';
		}
		$attachments .= '</ul>';
	}
}

echo elgg_view('river/elements/layout', array(
	'item' => $vars['item'],
	'attachments' => $attachments,
));
