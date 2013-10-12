<?php
/**
 * Photo navigation
 *
 * @uses $vars['entity']
 */

$photo = $vars['entity'];
if($photo->legacy_guid){
	$photo_guid = $photo->legacy_guid;
} else {
	$photo_guid = $photo->getGUID();
}

$album = $photo->getContainerEntity('object');
$previous_photo = $album->getPreviousImage($photo_guid);
$next_photo = $album->getNextImage($photo_guid);
$size = $album->getSize();
$index = $album->getIndex($photo_guid);

if($previous_photo){
echo '<ul class="elgg-menu elgg-menu-hz tidypics-album-nav">';
echo '<li>';
echo elgg_view('output/url', array(
	'text' => elgg_view_icon('arrow-left'),
	'href' => $previous_photo->getURL(),
));
echo '</li>';
}

echo '<li>';
echo '<span>' . elgg_echo('image:index', array($index, $size)) . '</span>';
echo '</li>';

if($next_photo){
echo '<li>';
echo elgg_view('output/url', array(
	'text' => elgg_view_icon('arrow-right'),
	'href' => $next_photo->getURL(),
));
echo '</li>';
}
echo '</ul>';
