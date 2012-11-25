<?php
/**
 * Minds Inviter Invite Action
 */
 
$emails = get_input('emails');
$user = elgg_get_logged_in_user_entity();

$invite_code = generate_invite_code($user->username); //this will allow the news users to be friends with this user when they sign up!
$link =  elgg_get_site_url() . 'register?friend_guid=' . $user->guid . '&invitecode=' . $invite_code;

if(!is_array($emails)){
	$emails = trim($emails);
	if (strlen($emails) > 0) {
		$emails = preg_split('/\\s+/', $emails, -1, PREG_SPLIT_NO_EMPTY);
	}
}

foreach($emails as $email){
	
	if (!is_email_address($email)) {
		continue;
	}

	$friend = get_user_by_email($email);
	if($friend){
		$user->addFriend($friend[0]->getGUID());
		continue;
	}
	
	elgg_send_email('minds@minds.com', $email, elgg_echo('minds_inviter:subject', array($user->name)), elgg_echo('minds_inviter:body', array($user->name, $user->email, $link)));
}
echo '<script type="text/javascript">
	 window.opener.location = "' . elgg_get_site_url() .'invite?success=true";
     self.close();
</script>'; exit;

