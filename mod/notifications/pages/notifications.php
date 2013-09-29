<?php

require_once(dirname(dirname(dirname(dirname(__FILE__)))) . '/engine/start.php');


if(get_input('full')){
	
	gatekeeper();
	
	$title = elgg_echo('notifications');
	
	$options = array(	'type'=> 'notification',
				'attrs' => array('to_guid'=>elgg_get_logged_in_user_guid())
			);
	
//	$notifications = elgg_get_entities_from_metadata($options);
	

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

	 $options = array(       'type'=> 'notification',
                		'limit' => 10,
		                'attrs' => array('to_guid'=>elgg_get_logged_in_user_guid())
                        );

<<<<<<< HEAD
	$options = array(	'types'=>'object',
						'subtypes'=>'notification',
						//'owner_guid' => $user->getGUID(),
						'limit' => 10,
						'metadata_name_value_pairs' => array(array('name'=>'to_guid', 'value'=>$user->getGUID(), 'operand' => '='))
					);
	
	$notifications = elgg_get_entities_from_metadata($options);
	
	$list = elgg_view_entity_list($notifications);
=======
        $content = elgg_list_entities($options);
>>>>>>> cassandra
	
	$content .= elgg_view('output/url', array('href'=>'notifications/view', 'text'=>'See more'));
	
	
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
