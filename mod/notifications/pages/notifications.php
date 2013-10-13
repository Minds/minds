<?php

require_once(dirname(dirname(dirname(dirname(__FILE__)))) . '/engine/start.php');

$options = array(
		'type' => 'notification',
		'attrs' => array('namespace' => 'notifications:'.get_input('user_guid',elgg_get_logged_in_user_guid())),
		'limit' => get_input('limit', 12),
		'offset' => get_input('offset','')
	);

if(get_input('full')){
	
	gatekeeper();
	
	$title = elgg_echo('notifications');
	

	$content = elgg_list_entities($options);
	$params = array(
		'content' => $content,
		'title' => $title,
		'sidebar' => $sidebar,
		'filter_override' => '',
		'class' => 'notifications'
		);


	$body = elgg_view_layout('content', $params);
	
	echo elgg_view_page($title, $body);
	
} else {
	
	
	
$user = elgg_get_logged_in_user_entity();

if($user){


        $content = elgg_list_entities($options);
	
//	$content .= elgg_view('output/url', array('href'=>'notifications/view', 'text'=>'See more'));
	
	
	echo $content;
} else {
	
	echo elgg_echo('notifications:not_logged_in');
	
}
/*$result = new stdClass();
//$result->count = $message_count;
$result->ajax_view = $list;

echo json_encode($result);*/


//mark all as read.
//notifications_mark_read($notifications);
}
?>
