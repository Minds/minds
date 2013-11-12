<?php

	gatekeeper();
	
	$user = elgg_get_logged_in_user_entity();
	
	elgg_set_page_owner_guid($user->getGUID());
	
	$title_text = elgg_echo("user_support:help_center:ask");
	
	// breadcrumb
	elgg_push_breadcrumb(elgg_echo("user_support:tickets:mine:title"), "user_support/support_ticket/owner/" . $user->username);
	elgg_push_breadcrumb($title_text);
	
	// page elements
	$form = elgg_view_form("user_support/support_ticket/edit");
	
	// build page
	$page_data = elgg_view_layout("content", array(
		"title" => $title_text,
		"content" => $form,
		"filter" => ""
	));
	
	// draw page
	echo elgg_view_page($title_text, $page_data);