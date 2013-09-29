<?php
/**
 * Elgg add friend action
 *
 * @package Elgg.Core
 * @subpackage Friends.Management
 */

// Get the GUID of the user to friend
$friend_guid = get_input('friend');
$friend = get_entity($friend_guid, 'user');
if (!$friend) {
	register_error(elgg_echo('error:missing_data'));
	forward(REFERER);
}

if(!elgg_is_logged_in()){
	global $SESSION;
	$SESSION['to_friend'] = $friend_guid;
	$SESSION['last_forward_from'] = elgg_get_site_url().'action/friends/add?friend='.$friend_guid;
	forward('login?returntoreferer=true');
} 

$errors = false;

// Get the user
try {
	if (!elgg_get_logged_in_user_entity()->addFriend($friend_guid)) {
		$errors = true;
	}
} catch (Exception $e) {
	register_error(elgg_echo("friends:add:failure", array($friend->name)));
	$errors = true;
}
if (!$errors) {
	// add to river
	//add_to_river('river/relationship/friend/create', 'friend', elgg_get_logged_in_user_guid(), $friend_guid);
	system_message(elgg_echo("friends:add:successful", array($friend->name)));
}

if(get_input('ajax')){
	if(!$errors){
		echo 'subscribed';
	}
}
//Send notification...... Chris

$from_guid = elgg_get_logged_in_user_guid();

notification_create(array($friend_guid), $from_guid, $guid, array('description'=>$message,'notification_view'=>'friends'));


// Forward back to the page you friended the user on
forward(REFERER);
