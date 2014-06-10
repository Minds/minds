<?php
/**
 * Elastic Search 
 *
 * @package elasticsearch
 */

$item = $vars['item'];

$cover = $item->getCoverImage();

if($cover){
	$icon = elgg_view('output/img', array('src'=> $cover->getIconURL(), 'width'=> 100, 'class'=>'elasticsearch-round-corners'));
} else {
	$icon = elgg_view('output/img', array('src'=> "mod/tidypics/graphics/empty_album.png", 'width'=> 50, 'class'=>'elasticsearch-round-corners'));	
}

$title = "<a href=\"{$item->getURL()}\">{$item->getTitle()}</a>";

$body = "<p class=\"mbn\">$title</p>";
if ($extra_info) {
	$body .= "<p class=\"elgg-subtext\">$extra_info</p>";
}
$body .= "<p class=\"elgg-subtext\"" . elgg_view_friendly_time($item->time_created) . "</p>";

echo elgg_view_image_block($icon, $body);
