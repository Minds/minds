<?php

	$spam_login_filter_ip_list = get_input('spam_login_filter_ip_list');
	$error = FALSE;

	if (!$spam_login_filter_ip_list) {
		register_error(elgg_echo('spam_login_filter:errors:unknown_ips'));
		forward('admin/administer_utilities/manageip');
	}

	$access = access_get_show_hidden_status();
	access_show_hidden_entities(TRUE);

	foreach ($spam_login_filter_ip_list as $guid) {
		$ip = get_entity($guid);

		if (!$ip->delete()) {
			$error = TRUE;
			continue;
		}
	}

	access_show_hidden_entities($access);

	if (count($spam_login_filter_ip_list) == 1) {
		$message_txt = elgg_echo('spam_login_filter:messages:deleted_ip');
		$error_txt = elgg_echo('spam_login_filter:errors:could_not_delete_ip');
	} else {
		$message_txt = elgg_echo('spam_login_filter:messages:deleted_ips');
		$error_txt = elgg_echo('spam_login_filter:errors:could_not_delete_ips');
	}

	if ($error) {
		register_error($error_txt);
	} else {
		system_message($message_txt);
	}

	forward('admin/administer_utilities/manageip');