<?php
/**
 * Minds Inviter Invite Action
 */
 
$emails = get_input('emails');
$user = elgg_get_logged_in_user_entity();

foreach($emails as $email){

	$friend = get_user_by_email($email);
	if($friend){
		$user->addFriend($friend[0]->getGUID());
//		continue;
	}
	
	elgg_send_email('minds@minds.com', $email, elgg_echo('minds_inviter:subject', array($user->name)), elgg_echo('minds_inviter:body', array($user->name, $user->email)));
}
 echo '<script type="text/javascript">
     self.close();
</script>'; exit;

