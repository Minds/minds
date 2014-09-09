<?php
/**
 * Main activity stream list page
 */

//if(!get_input('linear')){
//	set_input('linear', 'on');
//}

if(!elgg_is_logged_in())
	forward('/login');

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
$options['offset'] = get_input('offset',"");

$user = elgg_get_logged_in_user_entity();

if(!$user){
	 $page_type = 'featured';
} /*elseif(count($user->getFriends()) == 0 || $user->getFriends() == false || !$user->getFriends()){
	$page_type = 'featured';
}*/

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
$vars['filter_context'] = $page_filter;
$options['list_class'] = 'x2 minds-list-river';

$options['prepend'] = "<li class=\"elgg-item minds-fixed-post-box\">".elgg_view_form('deck_river/post',  
						array(	'action'=>'action/deck_river/post/add', 
								'name'=>'post',
								'class'=>'minds-fixed-post-box', 
								'enctype' => 'multipart/form-data'
						),
						array(	'to_guid'=> $user->guid, 
						 	//	'access_id'=> ACCESS_PRIVATE, 
						 		'hide_accounts'=>true
						)
					) . "</li>".
					"<li class=\"elgg-item minds-news-filter-box\">".elgg_view('page/layouts/content/river_filter', $vars). 
					"</li>";

$options['list_class'] = 'elgg-list minds-list-river x2 mason';
$activity = elgg_list_river($options);
if (!$activity) {
	$activity = elgg_echo('river:none');
}

$sidebar .= elgg_view('channel/sidebar', array(
	'user' => $user
));

$params = array(
	'content' =>  $content . $activity,
	'avatar' => $avatar,
	'sidebar' => $sidebar,
	'filter_context' => $page_filter,
	'filter' => false,
	'header' => $header,
	'class' => 'elgg-river-layout',
);

$body = elgg_view_layout('fixed', $params);

echo elgg_view_page($title, $body, 'default', array('class'=>'news'));
