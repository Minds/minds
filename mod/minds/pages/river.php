<?php
/**
 * Main activity stream list page
 */

$options = array();

$page_type = preg_replace('[\W]', '', get_input('page_type', 'friends'));
$type = preg_replace('[\W]', '', get_input('type', 'all'));
$subtype = preg_replace('[\W]', '', get_input('subtype', ''));
if ($subtype) {
	$selector = "type=$type&subtype=$subtype";
} else {
	$selector = "type=$type";
}

if ($type != 'all') {
	$options['type'] = $type;
	if ($subtype) {
		$options['subtype'] = $subtype;
	}
}

switch ($page_type) {
	case 'all':
		$title = elgg_echo('river:friends');
		$page_filter = 'all';
		break;
	case 'trending':
		$title = elgg_echo('river:trending');
		$page_filter = 'trending';
		//GET THE TRENDING FEATURES
		$options['object_guids'] = thumbs_trending('guids');
		break;
	case 'single':
		$id = get_input('id');
		$options['ids'] = $id;
		break;
	case 'thumbsup':
		$title = elgg_echo('river:thumbs-up');
		$page_filter = 'thumbsup';
		//GET THE TRENDING FEATURES
		$options['object_guids'] = thumbs_up_history();
		break;
	case 'thumbsdown':
		$title = elgg_echo('river:thumbs-down');
		$page_filter = 'thumbsdown';
		//GET THE TRENDING FEATURES
		$options['object_guids'] = thumbs_down_history();
		break;
	default:
		$page_filter = 'friends';
		//$options['relationship_guid'] = elgg_get_logged_in_user_guid();
		//$options['relationship'] = 'friend';
		$friends = get_user_friends(elgg_get_logged_in_user_guid(), $subtype = ELGG_ENTITIES_ANY_VALUE, 0, 0);
		foreach($friends as $friend){
			$friend_guids[] = $friend->guid;
		}
		$page_filter = 'friends';
		$options['subject_guids'] = array_merge(array(elgg_get_logged_in_user_guid()), $friend_guids);
		break;
}

$activity = elgg_list_river($options);
if (!$activity) {
	$activity = elgg_echo('river:none');
}

$content = elgg_view('core/river/filter', array('selector' => $selector));

$sidebar = elgg_view('core/river/sidebar');

$params = array(
	'content' =>  $content . $activity,
	'sidebar' => $sidebar,
	'filter_context' => $page_filter,
	'filter_override' => elgg_view('page/layouts/content/river_filter', $vars),
	'class' => 'elgg-river-layout',
);

$body = elgg_view_layout('river', $params);

echo elgg_view_page($title, $body);
