<?php

	gatekeeper();
	
	$guid = (int) get_input("guid");
	
	$forward = true;
	
	if (($entity = get_entity($guid)) && elgg_instanceof($entity, "object", UserSupportTicket::SUBTYPE, "UserSupportTicket")) {
		if ($entity->canEdit()) {
			$forward = false;
			
			$owner = $entity->getOwnerEntity();
			
			elgg_set_page_owner_guid($owner->getGUID());
			
			$title_text = $entity->title;
			
			// breadcrumb
			if ($owner->getGUID() == elgg_get_logged_in_user_guid()) {
				elgg_push_breadcrumb(elgg_echo("user_support:tickets:mine:title"), "user_support/support_ticket/owner/" . $owner->username);
			} else {
				elgg_push_breadcrumb(elgg_echo("user_support:tickets:owner:title", array($owner->name)), "user_support/support_ticket/owner/" . $owner->username);
			}
			
			elgg_push_breadcrumb($title_text, $entity->getURL());
			elgg_push_breadcrumb(elgg_echo("edit"));
			
			// build page
			$page_data = elgg_view_layout("content", array(
				"title" => $title_text,
				"content" => elgg_view_form("user_support/support_ticket/edit", array(), array("entity" => $entity)),
				"filter" => ""
			));
		}
	}

	if(!$forward){
		echo elgg_view_page($title_text, $page_data);
	} else {
		forward(REFERER);
	}
	