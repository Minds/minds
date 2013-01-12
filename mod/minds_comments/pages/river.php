<?php

/**
 * Main activity stream list page
 */
elgg_load_js('hj.river.base');

$options = array();

$page_type = preg_replace('[\W]', '', get_input('page_type', 'all'));
$type = preg_replace('[\W]', '', get_input('type', 'all'));
$subtype = preg_replace('[\W]', '', get_input('subtype', ''));

if ($subtype) {
	$selector = "type=$type&subtype=$subtype";
} else {
	$selector = "type=$type";
}

if (!$pair = get_input('type_subtype_pairs', false)) {
	if ($type != 'all') {
		$options['type'] = $type;
		if ($subtype) {
			$options['type_subtype_pairs'] = array($type => $subtype);
		}
	}
} else {
	$options['type_subtype_pairs'] = $pair;
}
switch ($page_type) {
	case 'mine':
		$title = elgg_echo('river:mine');
		$page_filter = 'mine';
		$options['subject_guid'] = elgg_get_logged_in_user_guid();
		break;
	case 'friends':
		$title = elgg_echo('river:friends');
		$page_filter = 'friends';
		$options['relationship_guid'] = elgg_get_logged_in_user_guid();
		$options['relationship'] = 'friend';
		break;
	default:
		$title = elgg_echo('river:all');
		$page_filter = 'all';
		break;
}

if (!elgg_is_xhr()) {
	$options['data-options'] = htmlentities(json_encode($options), ENT_QUOTES, 'UTF-8');
	$options['limit'] = 10;
	$options['pagination'] = true;
	$options['base_url'] = 'activity';
	$options['list_id'] = 'elgg-river-main';

	$activity = elgg_list_river($options);

	$content = elgg_view('core/river/filter', array('selector' => $selector));

	$sidebar = elgg_view('core/river/sidebar');

	$params = array(
		'content' => $content . $activity,
		'sidebar' => $sidebar,
		'filter_context' => $page_filter,
		'class' => 'elgg-river-layout',
	);

	$body = elgg_view_layout('content', $params);

	echo elgg_view_page($title, $body);
} else {
	$sync = get_input('sync');
	$ts = (int) get_input('time');
	if (!$ts) {
		$ts = time();
	}
	$options = get_input('options');
	if ($sync == 'new') {
		$options['wheres'] = array("rv.posted > {$ts}");
		$options['order_by'] = 'rv.posted asc';
		$options['limit'] = 0;
	} else {
		$options['wheres'] = array("rv.posted < {$ts}");
		$options['order_by'] = 'rv.posted desc';
	}
	$defaults = array(
		'offset' => (int) max(get_input('offset', 0), 0),
		'limit' => (int) max(get_input('limit', 10), 0),
		'pagination' => TRUE,
		'base_url' => 'activity',
		'list_class' => 'elgg-list-river elgg-river', // @todo remove elgg-river in Elgg 1.9
	);

	$options = array_merge($defaults, $options);

	$items = elgg_get_river($options);

	if (is_array($items) && count($items) > 0) {
		foreach ($items as $key => $item) {
			$id = "item-{$item->getType()}-{$item->id}";
			$time = $item->posted;

			$html = "<li id=\"$id\" class=\"elgg-item\" data-timestamp=\"$time\">";
			$html .= elgg_view_list_item($item, $vars);
			$html .= '</li>';

			$output[] = $html;
		}
	}
	print(json_encode($output));
	exit;
}
