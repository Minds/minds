<?php

/**
 * Gallery view
 *
 * Implemented as an unorder list
 *
 * @uses $vars['items']         Array of ElggEntity or ElggAnnotation objects
 * @uses $vars['offset']        Index of the first list item in complete list
 * @uses $vars['limit']         Number of items per page
 * @uses $vars['count']         Number of items in the complete list
 * @uses $vars['pagination']    Show pagination? (default: true)
 * @uses $vars['position']      Position of the pagination: before, after, or both
 * @uses $vars['full_view']     Show the full view of the items (default: false)
 * @uses $vars['gallery_class'] Additional CSS class for the <ul> element
 * @uses $vars['item_class']    Additional CSS class for the <li> elements
 */
$items = $vars['items'];

elgg_push_context('gallery');
$items = $vars['items'];
$offset = elgg_extract('offset', $vars);
$limit = elgg_extract('limit', $vars);
$count = elgg_extract('count', $vars);
$base_url = elgg_extract('base_url', $vars, '');
$pagination = elgg_extract('pagination', $vars, true);
$offset_key = elgg_extract('offset_key', $vars, 'offset');
$position = elgg_extract('position', $vars, 'after');
$gallery_class = 'elgg-gallery';
$list_id = elgg_extract('list_id', $vars, null);
$data_options = elgg_extract('data-options', $vars, false);

if ($data_options) {
	$gallery_class = "$gallery_class hj-syncable clearfix";
}

if (isset($vars['gallery_class'])) {
	$gallery_class = "$gallery_class {$vars['gallery_class']}";
}

$item_class = 'elgg-item';
if (isset($vars['item_class'])) {
	$item_class = "$item_class {$vars['item_class']}";
}

$before = elgg_view('page/components/gallery/prepend', $vars);
$after = elgg_view('page/components/gallery/append', $vars);

$html = "";
$nav = "";

if ($pagination && $count) {
	$ajaxify = false;
	if ($data_options) {
		$ajaxify = true;
	}
	$nav .= elgg_view('navigation/pagination', array(
		'baseurl' => $base_url,
		'offset' => $offset,
		'count' => $count,
		'limit' => $limit,
		'offset_key' => $offset_key,
		'ajaxify' => $ajaxify,
		'list_id' => $list_id
			));
}

$gallery_params = array('items', 'offset', 'limit', 'count', 'base_url', 'pagination', 'offset_key', 'position', 'gallery_class', 'gallery_id', 'data-options');
foreach ($gallery_params as $gallery_param) {
	if (isset($vars[$gallery_param])) {
		unset($vars[$gallery_param]);
	}
}

$html .= $before;
if (is_array($items) && count($items) > 0) {
	foreach ($items as $item) {
		if (elgg_instanceof($item)) {
			$id = "elgg-{$item->getType()}-{$item->getGUID()}";
			$time = $item->time_created;
		} else {
			$id = "item-{$item->getType()}-{$item->id}";
			$time = $item->posted;
		}
		$html .= "<li id=\"$id\" class=\"$item_class\" data-timestamp=\"$time\">";
		$html .= elgg_view_list_item($item, $vars);
		$html .= '</li>';
	}
}

$html .= $after;

$html = "<ul id=\"$list_id\" class=\"$gallery_class\" data-options=\"$data_options\">$html</ul>";

if ($position == 'before' || $position == 'both') {
	$html = $nav . $html;
}

if ($position == 'after' || $position == 'both') {
	$html .= $nav;
}

echo $html;

elgg_pop_context();
