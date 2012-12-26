<?php
/**
 * Elastic Search 
 *
 * @package elasticsearch
 */

$item = $vars['item'];

$icon = elgg_view_entity_icon($item, 'medium');

$title = "<a href=\"{$item->getURL()}\">$item->name</a>";

$body = "<p class=\"mbn\">$title</p>";
$body .= "<p class=\"elgg-subtext\">" . elgg_echo('group') . "</p>";

echo elgg_view_image_block($icon, $body);
