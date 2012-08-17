<?php

require_once(dirname(dirname(dirname(dirname(__FILE__)))) . '/engine/start.php');

gatekeeper();

if(get_input('full')){
	
	$title = elgg_echo('notifications');
	
	$options = array(	'types'=>'object',
					'subtypes'=>'notification',
					'metadata_name_value_pairs' => array(array('name'=>'to_guid', 'value'=>$user->getGUID(), 'operand' => '='))
				);
	
	$notifications = elgg_get_entities_from_metadata($options);
	

	$content = elgg_list_entities_from_metadata($options);
	
	$params = array(
		'content' => $content,
		'title' => $title,
		'sidebar' => $sidebar,
		'filter_override' => '',
		);


	$body = elgg_view_layout('content', $params);
	
	echo elgg_view_page($title, $body);
	
} else {
	
	
	
$user = elgg_get_logged_in_user_entity();


$options = array(	'types'=>'object',
					'subtypes'=>'notification',
					//'owner_guid' => $user->getGUID(),
					'limit' => 5,
					'metadata_name_value_pairs' => array(array('name'=>'to_guid', 'value'=>$user->getGUID(), 'operand' => '='))
				);

$notifications = elgg_get_entities_from_metadata($options);

$list = elgg_view_entity_list($notifications);

$list .= elgg_view('output/url', array('href'=>'notifications/view', 'text'=>'See more'));


echo $list;
/*$result = new stdClass();
//$result->count = $message_count;
$result->ajax_view = $list;

echo json_encode($result);*/


//mark all as read.
//notifications_mark_read($notifications);
}
?>