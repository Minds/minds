<?php

	$entity = elgg_extract("entity", $vars);
	$full_view = elgg_extract("full_view", $vars);
	
	// entity menu
	$entity_menu = "";
	if (!elgg_in_context("widgets")) {
		$entity_menu = elgg_view_menu("entity", array(
			"entity" => $entity,
			"handler" => "user_support/help",
			"sort_by" => "priority",
			"class" => "elgg-menu-hz"
		));
	}
	
	if (!$full_view) {
		
		$params = array(
			"title" => elgg_echo("user_support:help_center:help:title"),
			"metadata" => $entity_menu,
			"tags" => elgg_view("output/tags", array("value" => $entity->tags)),
			"content" => elgg_view("output/longtext", array("value" => $entity->description))
		);
		$params = array_merge($params, $vars);
		echo elgg_view("object/elements/summary", $params);
	}