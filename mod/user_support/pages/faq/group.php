<?php

	// get the page owner
	$page_owner = elgg_get_page_owner_entity();
	
	if (!elgg_instanceof($page_owner, "group")) {
		register_error(elgg_echo("user_support:page_owner:not_group"));
		forward(REFERER);
	}
	
	elgg_push_context("faq");
	
	// build breadcrumb
	elgg_push_breadcrumb($page_owner->name);
	
	// build page elements
	$title_text = elgg_echo("user_support:faq:group:title", array($page_owner->name));
	
	
	$list_options = array(
		"type" => "object",
		"subtype" => UserSupportFAQ::SUBTYPE,
		"container_guid" => $page_owner->getGUID(),
		"full_view" => false
	);
	
	if (!($content = elgg_list_entities($list_options))) {
		$content = elgg_echo("user_support:faq:not_found");
	}
	
	// build page
	$page_data = elgg_view_layout("content", array(
		"title" => $title_text,
		"content" => $content,
		"filter" => ""
	));
	
	elgg_pop_context();
	
	// draw page
	echo elgg_view_page($title_text, $page_data);