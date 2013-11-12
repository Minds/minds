<?php

	user_support_staff_gatekeeper();
	
	$q = get_input("q");
	
	$options = array(
		"type" => "object",
		"subtype" => UserSupportTicket::SUBTYPE,
		"full_view" => false,
		"metadata_name_value_pairs" => array("status" => UserSupportTicket::OPEN),
		"order_by" => "e.time_updated DESC"
	);
	
	if (!empty($q)) {
		$options["joins"] = array("JOIN " . elgg_get_config("dbprefix") . "objects_entity oe ON e.guid = oe.guid");
		$options["wheres"] = array("oe.description LIKE '%" . sanitise_string($q) . "%'");
	}
	
	// build page elements
	$title_text = elgg_echo("user_support:tickets:list:title");
	
	// ignore access for support staff
	$ia = elgg_set_ignore_access(true);
	
	$form_vars = array(
		"method" => "GET",
		"disable_security" => true,
		"action" => "user_support/support_ticket"
	);
	$search = elgg_view_form("user_support/support_ticket/search", $form_vars);
	
	if(!($body = elgg_list_entities_from_metadata($options))){
		$body = elgg_echo("notfound");
	}
	
	// restore access
	elgg_set_ignore_access($ia);
	
	// build page
	$page_data = elgg_view_layout("content", array(
		"title" => $title_text,
		"content" => $search . $body,
		"filter" => elgg_view_menu("user_support", array("class" => "elgg-tabs"))
	));
	
	// draw page
	echo elgg_view_page($title_text, $page_data);
	