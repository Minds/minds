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

$options['limit'] = get_input('limit',12);

$user = elgg_get_logged_in_user_entity();
if(!$user){
	 $page_type = 'featured';
} elseif(count($user->getFriends()) == 0 || $user->getFriends() == false || !$user->getFriends()){
	$page_type = 'featured';
}

switch ($page_type) {
	case 'all':
		$title = elgg_echo('news');
		$page_filter = 'all';
		$options['type'] = 'timeline';
		break;
	case 'trending':
		$title = elgg_echo('river:trending');
		$page_filter = 'trending';
		//GET THE TRENDING FEATURES
		if(elgg_plugin_exists('analytics')){
			$options['object_guids'] = analytics_retrieve(array('limit'=>$options['limit']+3, 'offset'=>$options['offset']));
			$options['action_types'] = 'create';
			$options['offset'] = 0;
		} else {
			forward(REFERRER);
		}
		break;
	case 'featured':
		$title = elgg_echo('river:featured');
		$page_filter = 'featured';
		$options['owner_guid'] = 'feature'; //this should be renamed to owner...
		break;
	case 'single':
		$id = get_input('id');
		$page_filter = 'single';
		if(is_numeric($id)){
			//fully integer = must be a guid. We need a better way!!
			$options['action_types'] = 'create';
			$options['object_guids'] = array($id);
		} else {
			$options['ids'] = array($id);
		}
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
		$title = elgg_echo('news');
		$page_filter = 'friends';
		$options['type'] = 'timeline';
		$options['owner_guid'] = elgg_get_logged_in_user_guid();
		break;
}

$activity = elgg_list_river($options);
if (!$activity) {
	$activity = elgg_echo('river:none');
}

//$content = elgg_view('core/river/filter', array('selector' => $selector));
//$sidebar = elgg_view_form('wall/add', array('name'=>'elgg-wall-news'), array('to_guid'=> elgg_get_logged_in_user_guid(), 'ref'=>'news'));
//$sidebar .= elgg_view('core/river/sidebar');
$sidebar .= elgg_view('minds/ads', array('large-block'));

$vars['filter_context'] = $page_filter;
$title_block = elgg_view_title($title, array('class' => 'elgg-heading-main'));
$filter = elgg_view('page/layouts/content/river_filter', $vars);
$wall_add = elgg_view_form('wall/add',  array('name'=>'elgg-wall-news'), array('to_guid'=> elgg_get_logged_in_user_guid(), 'ref'=>'news'));
$header = <<<HTML
<div class="elgg-head clearfix">
	$title_block$wall_add
</div>
$filter
HTML;

$params = array(
	'content' =>  $content . $activity,
	'sidebar' => $sidebar,
	'filter_context' => $page_filter,
	'filter' => false,
	'header' => $header,
	'class' => 'elgg-river-layout',
);

$body = elgg_view_layout('one_column', $params);

echo elgg_view_page($title, $body, 'default', array('class'=>'news'));
