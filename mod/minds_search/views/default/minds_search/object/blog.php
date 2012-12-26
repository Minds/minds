<?php
/**
 * Elastic Search 
 *
 * @package elasticsearch
 */

$item = $vars['item'];

$icon = elgg_view_entity_icon($item->getOwnerEntity(), 'medium');

$title = "<a href=\"{$item->getURL()}\">$item->title</a>";

$body = "<p class=\"mbn\">$title</p>";

//$description = $item->description;

if (strlen($description) > 75) {

    // truncate string
    $stringCut = substr($description, 0, 75);

    // make sure it ends in a word so assassinate doesn't become ass...
    $description = substr($stringCut, 0, strrpos($stringCut, ' ')).'...'; 
}
$body .= $description;
if ($extra_info) {
	$body .= "<p class=\"elgg-subtext\">$extra_info</p>";
}
$body .= "<p class=\"elgg-subtext\">" . elgg_view_friendly_time($item->time_created) . "</p>";

echo elgg_view_image_block($icon, $body);
