<?php

require_once(dirname(dirname(dirname(dirname(__FILE__)))) . '/engine/start.php');

$user_guid = get_input('user_guid', elgg_get_logged_in_user_guid());

$db = new \minds\core\data\call('entities_by_time');
$guids = $db->getRow('notifications:'.$user_guid, array('limit'=> get_input('limit', 12), 'offset'=>get_input('offset','')));

if(!$guids){
	echo 'Sorry, you don\'t have any notifications';
	return false;
}
$options = array(
	'guids'=>$guids,
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
	
	\minds\plugin\notifications\notifications::resetCounter($user->guid);
	//notifications_reset_counter($user->guid);

}
?>
