<?php

	$help_url = elgg_extract("HTTP_REFERER", $_SERVER);
	$help_context = user_support_get_help_context($help_url);
	if (empty($help_context)) {
		$help_context = user_support_get_help_context();
	}
	
	$contextual_help_object = false;
	if (elgg_get_plugin_setting("help_enabled", "user_support") != "no") {
		$contextual_help_object = user_support_get_help_for_context($help_context);
	}
	
	$group = null;
	if (elgg_is_active_plugin("groups")) {
		$group_guid = (int) elgg_get_plugin_setting("help_group_guid", "user_support");
		if (!empty($group_guid) && ($group = get_entity($group_guid))) {
			if (!elgg_instanceof($group, "group", null, "ElggGroup")) {
				$group = null;
			}
		}
	}
	
	$faq_options = array(
		"type" => "object",
		"subtype" => UserSupportFAQ::SUBTYPE,
		"site_guids" => false,
		"limit" => 5,
		"metadata_name_value_pairs" => array("name" => "help_context", "value" => $help_context),
		"full_view" => false,
		"pagination" => false
	);
	
	//$faq = elgg_list_entities_from_metadata($faq_options);
	
	$help_center = elgg_view("user_support/help_center", array(
			"group" => $group,
			"contextual_help_object" => $contextual_help_object,
			"faq" => $faq,
			"help_url" => $help_url,
			"help_context" => $help_context
	));
	
	// check if this is popup or not
	if (elgg_is_xhr()) {
		echo elgg_view_module("info", elgg_echo("user_support:help_center:title"), $help_center, array("class" => "user-support-help-center-popup"));
	} else {
		$page_data = elgg_view_layout("content", array(
				
			"title" => elgg_echo("user_support:help_center:title"),
			"content" => $help_center,
			"filter" => false
		));
		
		echo elgg_view_page(elgg_echo("user_support:help_center:title"), $page_data);
	}