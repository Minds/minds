<?php

	gatekeeper();
	
	$guid = (int) get_input("guid");

	$forward = true;
	
	// ignore access for support staff
	$ia = elgg_set_ignore_access(true);
	
	$entity = get_entity($guid);
	
	if(!empty($entity) && elgg_instanceof($entity, "object", UserSupportTicket::SUBTYPE, "UserSupportTicket")){
		$forward = false;
		elgg_set_page_owner_guid($entity->getOwnerGUID());

		// build page elements
		if(!empty($entity->support_type)){
			$title_text = elgg_echo("user_support:support_type:" . $entity->support_type) . ": ";
		}
		
		$title = $entity->title;
		if(strlen($title) > 50){
			$title = elgg_get_excerpt($title, 50);
		}
		$title_text .= $title;
		
		// build breadcrumb
		if($entity->getOwnerGUID() == elgg_get_logged_in_user_guid()){
			elgg_push_breadcrumb(elgg_echo("user_support:menu:support_tickets:mine"), "user_support/support_ticket/owner/" . $entity->getOwnerEntity()->username);
		} else {
			elgg_push_breadcrumb(elgg_echo("user_support:menu:support_tickets"), "user_support/support_ticket");
		}
		elgg_push_breadcrumb($title);
		
		// build page
		$page_data = elgg_view_layout("content", array(
			"title" => $title_text,
			"content" => elgg_view_entity($entity, array("full_view" => true)),
			"filter" => ""
		));
	}
	
	// restore access
	elgg_set_ignore_access($ia);
	
	if(!$forward){
		echo elgg_view_page($title_text, $page_data);
	} else {
		forward(REFERER);
	}
	