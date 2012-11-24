<?php
/**
 * Minds Inviter Invite Action
 */
 
$emails = get_input('emails');

$user = elgg_get_logged_in_user_entity();

foreach($emails as $email){
	
	if($friend = get_user_by_email($email)){
		$user->addFriend($friend->getGUID());
		continue;
	}
	
	elgg_send_email('minds@minds.com', $email, elgg_echo('minds_inviter:subject'), elgg_echo('minds_invter:body', array($user->name)));
}


