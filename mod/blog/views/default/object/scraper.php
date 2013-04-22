<?php
/**
 * View for scraper objects
 */

$scraper = elgg_extract('entity', $vars, FALSE);

$subtitle = $scraper->feed_url;

if($scraper->timestamp){
	$content = 'last scraped: ' . friendly_time($scraper->timestamp);
}

$metadata = elgg_view_menu('entity', array(
	'entity' => $scraper,
	'handler' => 'scraper',
	'sort_by' => 'priority',
	'class' => 'elgg-menu-hz',
));

$params = array(
	'entity' => $scraper,
	'title' => $scraper->title,
	'metadata' => $metadata,
	'subtitle' => $subtitle,
	'content' => $content,
);
$params = $params + $vars;
$list_body = elgg_view('object/elements/summary', $params);

echo elgg_view_image_block($owner_icon, $list_body);

