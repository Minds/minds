<?php

	$entity = elgg_extract("entity", $vars);
	$full_view = elgg_extract("full_view", $vars);
	
	// entity menu
	$entity_menu = "";
	if(!elgg_in_context("widgets")){
		$entity_menu = elgg_view_menu("entity", array(
			"entity" => $entity,
			"handler" => "user_support/support_ticket",
			"sort_by" => "priority",
			"class" => "elgg-menu-hz"
		));
	}
	
	$loggedin_user = elgg_get_logged_in_user_entity();
	$owner = $entity->getOwnerEntity();
	
	if(!$full_view){
		// icon
		$icon = elgg_view_entity_icon($entity, "small");
		$info = "";
		
		// title
		if(!empty($entity->support_type)){
			$title = elgg_echo("user_support:support_type:" . $entity->support_type) . ": ";
		}
		$title_text = $entity->title;
		if(strlen($title_text) > 50){
			$title_text = elgg_get_excerpt($title, 50);
		}
		$title .= elgg_view("output/url", array("href" => $entity->getURL(), "text" => $title_text));
		
		// strapline
		$subtitle = user_support_time_created_string($entity);
		$subtitle .= " " . elgg_echo("by");
		$subtitle .= " " . elgg_view("output/url", array("href" => $owner->getURL(), "text" => $owner->name));
		
		// last comment by
		if($ann = $entity->getAnnotations("generic_comment", 1, 0, "desc")){
			$ann_owner = get_user($ann[0]->owner_guid);
			$url = elgg_view("output/url", array("href" => $ann_owner->getURL(), "text" => $ann_owner->name));
			$info = elgg_echo("user_support:last_comment", array($url));
		}
		
		$params = array(
			"entity" => $entity,
			"metadata" => $entity_menu,
			"content" => $info,
			"subtitle" => $subtitle,
			"tags" => elgg_view("output/tags", array("value" => $entity->tags)),
			"title" => $title
		);
		$params = $params + $vars;
		$list_body = elgg_view("object/elements/summary", $params);
		
		echo elgg_view_image_block($icon, $list_body);
	} else {
		// icon
		$icon = elgg_view_entity_icon($entity, "tiny");
		
		$subtitle = user_support_time_created_string($entity);
		$subtitle .= " " . elgg_echo("by");
		$subtitle .= " " . elgg_view("output/url", array("href" => $owner->getURL(), "text" => $owner->name));
		
		// summary
		$params = array(
			"entity" => $entity,
			"metadata" => $entity_menu,
			"tags" => elgg_view("output/tags", array("value" => $entity->tags)),
			"subtitle" => $subtitle,
			"title" => false
		);
		$params = $params + $vars;
		$summary = elgg_view("object/elements/summary", $params);
		
		// body
		$body = "";
		if(!empty($entity->help_url)){
			$body .= elgg_echo("user_support:url") . ": " . elgg_view("output/url", array("href" => $entity->help_url)) . "<br />";
		}
		
		if(!empty($entity->description)){
			$body .= elgg_view("output/longtext", array("value" => $entity->description));
		} elseif(strlen($entity->title) > 50) {
			$body .= elgg_view("output/longtext", array("value" => $entity->title));
		}
		
		// blog
		echo elgg_view('object/elements/full', array(
			'summary' => $summary,
			'icon' => $icon,
			'body' => $body,
		));
		
		// show all comments
		$ia = elgg_set_ignore_access(true);
		
		echo elgg_view_comments($entity);
		
		elgg_set_ignore_access($ia);
	}
	
