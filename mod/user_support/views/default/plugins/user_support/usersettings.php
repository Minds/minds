<?php

	$plugin = elgg_extract("entity", $vars);
	$page_owner = elgg_get_page_owner_entity();
	
	$noyes_options = array(
		"no" => elgg_echo("option:no"),
		"yes" => elgg_echo("option:yes")
	);
	
	if ($page_owner->getGUID() == elgg_get_logged_in_user_guid()) {
		if (user_support_staff_gatekeeper(false, $page_owner->getGUID())) {
			$body = "<div>";
			$body .= elgg_echo("user_support:usersettings:admin_notify") . "<br />";
			$body .= elgg_view("input/dropdown", array("name" => "params[admin_notify]", "options_values" => $noyes_options, "value" => $plugin->getUserSetting("admin_notify", $page_owner->getGUID())));
			$body .= "</div>";
			
			echo $body;
		}
	}