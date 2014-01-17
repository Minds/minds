<?php
/**
 * Delete a user or users by guid
 *
 */

$user_guids = get_input('user_guids');
$error = FALSE;

if (!$user_guids) {
	register_error(elgg_echo('cms_cancel_account:errors:unknown_users'));
	forward(REFERRER);
}

foreach ($user_guids as $guid) {
	$user = get_entity($guid, 'user');
	if (!$user instanceof ElggUser) {
		$error = TRUE;
		continue;
	}

	//Preparar envío de correo electrónico al usuario
	//From
	$site = elgg_get_site_entity();
	$siteurl = elgg_get_site_url();
	if ($site && $site->email) {
		$from = $site->email;
	} else {
		$from = 'noreply@' . get_site_domain($site->guid);
	}
	//To
	$to = $user->email;	

		
	
	if (!$user->delete()) {
		$error = TRUE;
		
		//Subject
		$subject = elgg_echo('cms_cancel_account:mail:failedcancellationsubject');
		//Message
		$message = elgg_echo('cms_cancel_account:mail:failedcancellationmessage',array('username' => $user->name));
		//Envío de correo al usuario
		elgg_send_email($from, $to, $subject, $message);	
			
		continue;
	} else {
		
		//Subject
		$subject = elgg_echo('cms_cancel_account:mail:successfulcancellationsubject');
		//Message
		$message = elgg_echo('cms_cancel_account:mail:successfulcancellationmessage', array('username' => $user->name, 'siteurl' => $siteurl));
		//Envío de correo al usuario
		elgg_send_email($from, $to, $subject, $message);	
			
	}
	
//	if (!$user->delete()) {
//		$error = TRUE;
//		continue;
//	}	
}

if (count($user_guids) == 1) {
	$message_txt = elgg_echo('cms_cancel_account:messages:deleted_user');
	$error_txt = elgg_echo('cms_cancel_account:errors:could_not_delete_user');
} else {
	$message_txt = elgg_echo('cms_cancel_account:messages:deleted_users');
	$error_txt = elgg_echo('cms_cancel_account:errors:could_not_delete_users');
}

if ($error) {
	register_error($error_txt);
} else {
	system_message($message_txt);
}

forward(REFERER);
