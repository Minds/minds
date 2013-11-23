<?php

	$entity = elgg_extract("entity", $vars);
	$container = $entity->getContainerEntity();
	$full_view = (bool) elgg_extract("full_view", $vars, false);
	
	// entity menu
	$entity_menu = "";
	if(!elgg_in_context("widgets")){
		$entity_menu = elgg_view_menu("entity", array(
			"entity" => $entity,
			"handler" => "user_support/faq",
			"sort_by" => "priority",
			"class" => "elgg-menu-hz"
		));
	}
	
	if(!$full_view){
		$icon = elgg_view_entity_icon($entity, "small");
		
		// anwser
		$info = "<div>";
		$info .= elgg_echo("user_support:anwser:short") . ": " . elgg_get_excerpt($entity->description, 150);
		$info .= elgg_view("output/url", array("href" => $entity->getURL(), "text" => elgg_echo("user_support:read_more"), "class" => "mlm"));
		$info .= "</div>";
		
		$subtext = "";
		if (elgg_instanceof($container, "group") && ($container->getGUID() != elgg_get_page_owner_guid())) {
			$group_link = elgg_view("output/url", array("text" => $container->name, "href" => $container->getURL(), "is_trusted" => true));
			$subtext = elgg_echo("river:ingroup", array($group_link));
		}
		
		$params = array(
			"entity" => $entity,
			"metadata" => $entity_menu,
			"content" => $info,
			"title" => elgg_view("output/url", array("href" => $entity->getURL(), "text" => $entity->title)),
			"subtitle" => $subtext
		);
		$params = $params + $vars;
		$list_body = elgg_view("object/elements/summary", $params);
		
		echo elgg_view_image_block($icon, $list_body);
	} else {
		$owner = $entity->getOwnerEntity();
		
		// icon
		$icon = elgg_view_entity_icon($entity, "tiny");
		
		// summary
		$params = array(
			"entity" => $entity,
			"metadata" => $entity_menu,
			"tags" => elgg_view("output/tags", array("value" => $entity->tags)),
			"subtitle" => user_support_time_created_string($entity),
			"title" => false
		);
		$params = $params + $vars;
		$summary = elgg_view("object/elements/summary", $params);
		
		// body
		$body = elgg_echo("user_support:anwser") . ": ";
		$body .= elgg_view("output/longtext", array("value" => $entity->description));
		
		// blog
		echo elgg_view('object/elements/full', array(
				'summary' => $summary,
				'icon' => $icon,
				'body' => $body,
		));
	}
