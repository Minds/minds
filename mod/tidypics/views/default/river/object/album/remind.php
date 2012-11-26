<?php
/**
 * Remind views
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
			$attachments .= elgg_view_entity_icon($image, 'medium');
			$attachments .= '</li>';
		}
		$attachments .= '</ul>';
	}
}

$subject = $vars['item']->getSubjectEntity();
$subject_link = elgg_view('output/url', array(
	'href' => $subject->getURL(),
	'text' => $subject->name,
	'class' => 'elgg-river-subject',
	'is_trusted' => true,
));

$owner = $album->getOwnerEntity();
$owner_link = elgg_view('output/url', array(
	'href' => $owner->getURL(),
	'text' => $owner->name,
	'class' => 'elgg-river-owner',
	'is_trusted' => true,
));


$summary = elgg_echo("river:remind:object:album", array($subject_link, $owner_link));


echo elgg_view('river/elements/layout', array(
	'item' => $vars['item'],
	'attachments' => $attachments,
	'summary' => $summary,
));