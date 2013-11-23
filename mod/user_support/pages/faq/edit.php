<?php

	admin_gatekeeper();
	
	elgg_set_page_owner_guid(elgg_get_logged_in_user_guid());
	
	$guid = (int) get_input("guid");
	if(!empty($guid) && ($entity = get_entity($guid))){
		if(!elgg_instanceof($entity, "object", UserSupportFAQ::SUBTYPE, "UserSupportFAQ")){
			$entity = null;
		} else {
			$title_text = elgg_echo("user_support:faq:edit:title:edit");
		}
	}

	// make breadcrumb
	elgg_push_breadcrumb($title_text);
	
	$help_context = user_support_find_unique_help_context();

	// build page
	$page_data = elgg_view_layout("content", array(
		"title" => $title_text,
		"content" => elgg_view_form("user_support/faq/edit", null, array("entity" => $entity, "help_context" => $help_context)),
		"filter" => ""
	));
	
	// draw page
	echo elgg_view_page($title_text, $page_data);