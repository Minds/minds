<?php
/**
 * View a list of items
 *
 * @package Elgg
 *
 * @uses $vars['items']       Array of ElggEntity or ElggAnnotation objects
 * @uses $vars['offset']      Index of the first list item in complete list
 * @uses $vars['limit']       Number of items per page
 * @uses $vars['count']       Number of items in the complete list
 * @uses $vars['base_url']    Base URL of list (optional)
 * @uses $vars['pagination']  Show pagination? (default: true)
 * @uses $vars['position']    Position of the pagination: before, after, or both
 * @uses $vars['full_view']   Show the full view of the items (default: false)
 * @uses $vars['list_class']  Additional CSS class for the <ul> element
 * @uses $vars['item_class']  Additional CSS class for the <li> elements
 * $uses $vars['data-options'] An array of options to be passed to elgg_view_entity_list()
 */

$items = $vars['items'];
$offset = elgg_extract('offset', $vars);
$limit = elgg_extract('limit', $vars);
$count = elgg_extract('count', $vars);
$base_url = elgg_extract('base_url', $vars, '');
$pagination = elgg_extract('pagination', $vars, true);
$offset_key = elgg_extract('offset_key', $vars, 'offset');
$position = elgg_extract('position', $vars, 'after');
$list_class = 'elgg-list';
$list_id = elgg_extract('list_id', $vars, null);
$data_options = elgg_extract('data-options', $vars, false);


if ($data_options) {
    $list_class = "$list_class hj-syncable";
}

if (isset($vars['list_class'])) {
    $list_class = "$list_class {$vars['list_class']}";
}
//if(get_input('mason')){
        $list_class .= ' mason';
//}

$item_class = 'elgg-item';
if (isset($vars['item_class'])) {
    $item_class = "$item_class {$vars['item_class']}";
}

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
	'list_id' => $list_id,
        'last_guid' => $last_guid,
	'load-next' => elgg_get_context() == 'main' ? end($items)->featured_id : end($items)->guid
	));
}

$before = elgg_view('page/components/list/prepend', $vars);
$after = elgg_view('page/components/list/append', $vars);

$list_params = array('items', 'offset', 'limit', 'count', 'base_url', 'pagination', 'offset_key', 'position', 'list_class', 'list_id', 'data-options');
foreach ($list_params as $list_param) {
    if (isset($vars[$list_param])) {
        unset($vars[$list_param]);
    }
}

$html .= $before;

if (is_array($items) && count($items) > 0) {
    foreach ($items as $item) {
        if (elgg_instanceof($item)) {
            $id = $item->getGUID();
            $time = $item->time_created;
        } else {
	    $id = $item->id;
            $time = $item->posted;
        }
	$featured = $item->featured_id ? "featured_id=$item->featured_id" : null;
        $html .= "<li id=\"$id\" class=\"$item_class\" data-timestamp=\"$time\" $featured>";
        $html .= elgg_view_list_item($item, $vars);
        $html .= '</li>';
    }
}

$html .= $after;

$html = "<ul id=\"$list_id\" class=\"$list_class\" data-options=\"$data_options\">$html</ul>";

if ($position == 'before' || $position == 'both' && !$ajaxify) {
    $html = $nav . $html;
}

if ($position == 'after' || $position == 'both') {
    $html .= $nav;
}

echo $html;
