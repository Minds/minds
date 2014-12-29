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

if (isset($vars['list_class'])) {
    $list_class = "$list_class {$vars['list_class']}";
}

if($vars['masonry'] !== false && get_input('masonry') != 'off' && strpos($list_class, 'vertical-list') === FALSE){
        $list_class .= ' mason';
} else {
	set_input('show_loading', 'false'); // a bit of a hack!!
}

if($vars['linear'] || get_input('linear', 'off') == 'on'){
	$list_class .= ' x1';
}

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
	$nav .= elgg_view('navigation/pagination', array_merge(array(
        	'baseurl' => $base_url,
		//'offset' => $offset,
		//'count' => $count,
		//'limit' => $limit,
		'offset_key' => $offset_key,
		'ajaxify' => $ajaxify,
		'list_id' => $list_id,
		'last_guid' => $last_guid,
		'load-next' => elgg_get_context() == 'main' ? end($items)->featured_id : end($items)->guid ?: end($items)->id
	), $vars));
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
            $id = $item->guid;
            $time = $item->time_created;
	    $contents = elgg_view_list_item($item, $vars);
            if($contents){
		$featured = $item->featured_id ? "featured_id=$item->featured_id" : null;
	        $html .= "<li id=\"$id\" class=\"$item_class\" data-timestamp=\"$time\" $featured>";
	        $html .= $contents;
	        $html .= '</li>';
	    }
    }
}

$html .= $after;
$style = '';
if(elgg_is_xhr() || get_input('ajax') || elgg_get_viewtype() == 'json' || get_input('show_loading') == 'false'){
	$show_loading = false;
}else{
	$show_loading = true;
}

if( $show_loading)
	$style = 'display:none;';

$html = "<ul id=\"$list_id\" class=\"$list_class\" style=\"$style\" data-options=\"$data_options\">$html</ul>";
if($show_loading)	
	$html .= "<div class=\"minds-content-loading\"><span class=\"loader-sprite\">&#127758;</span><p> Loading... </p></div>";

if ($position == 'before' || $position == 'both' && !$ajaxify) {
    $html = $nav . $html;
}

if ($position == 'after' || $position == 'both') {
    $html .= $nav;
}

echo $html;
