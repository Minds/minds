<?php
gatekeeper();

$reason = get_input('reason');

$user = elgg_get_logged_in_user_entity();
$userguid = elgg_get_logged_in_user_guid();

$options = array(
	'type' => 'object',
	'subtype' => 'cancel_account_request',
	'owner_guid' => $userguid,
	'count' => true
);
$pending_requests = elgg_get_entities($options);

if ($pending_requests == 0){
	$request = new ElggObject();
	$request->subtype = "cancel_account_request";
	$request->access_id = 1; //logged_in
	
	$request->reason = $reason;
	
	$request->save();
	
	//Envío de correo electrónico al usuario
	//From
	$site = elgg_get_site_entity();
	if ($site && $site->email) {
		$from = $site->email;
	} else {
		$from = 'noreply@' . get_site_domain($site->guid);
	}
	//To
	$to = $user->email;	
	//Subject
	$subject = elgg_echo('cms_cancel_account:successfulrequestsubject');
	//Message
	$message = elgg_echo('cms_cancel_account:successfulrequestmessage', array('username' => $user->name));
	//Envío
	elgg_send_email($from, $to, $subject, $message);

}
else {
	register_error(elgg_echo('cms_cancel_account:invalidrequest'));	
}

$forward = "settings/user/$user->username";
forward($forward);
?>
