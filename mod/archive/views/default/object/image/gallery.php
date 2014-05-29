<?php
/**
 * Summary of an image for lists/galleries
 *
 * @uses $vars['entity'] TidypicsImage
 *
 * @author Cash Costello
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU General Public License v2
 */

$image = elgg_extract('entity', $vars);

$img = elgg_view_entity_icon($image, 'small');

$header = elgg_view('output/url', array(
	'text' => $image->getTitle(),
	'href' => $image->getURL(),
	'is_trusted' => true,
	'class' => 'tidypics-heading',
));

$body = elgg_view('output/url', array(
	'text' => $img,
	'href' => $image->getURL(),
	'encode_text' => false,
	'is_trusted' => true
));

/*
$footer = elgg_view('output/url', array(
	'text' => $image->getContainerEntity()->name,
	'href' => $image->getContainerEntity()->getURL(),
	'is_trusted' => true,
));
$footer .= '<div class="elgg-subtext">' . elgg_echo('album:num', array($album->getSize())) . '</div>';
*/

$params = array(
	'footer' => $footer,
);
echo elgg_view_module('tidypics-image', $header, $body, $params);

