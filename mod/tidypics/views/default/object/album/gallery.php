<?php
/**
 * Display an album in a gallery
 *
 * @uses $vars['entity'] TidypicsAlbum
 *
 * @author Cash Costello
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU General Public License v2
 */

$album = elgg_extract('entity', $vars);

$album_cover = elgg_view_entity_icon($album, 'small');

$header = elgg_view('output/url', array(
	'text' => $album->getTitle(),
	'href' => $album->getURL(),
	'is_trusted' => true,
	'class' => 'tidypics-heading',
));

$footer = elgg_view('output/url', array(
	'text' => $album->getContainerEntity()->name,
	'href' => $album->getContainerEntity()->getURL(),
	'is_trusted' => true,
));
$footer .= '<div class="elgg-subtext">' . elgg_echo('album:num', array($album->getSize())) . '</div>';

$params = array(
	'footer' => $footer,
);
echo elgg_view_module('tidypics-album', $header, $album_cover, $params);
