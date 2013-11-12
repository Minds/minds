<?php

	$widget = elgg_extract("entity", $vars);
	$owner = $widget->getOwnerEntity();
	
	$more_link = "user_support/faq";
	
	$num_display = (int) $widget->num_display;
	if ($num_display < 1) {
		$num_display = 4;
	}
	
	$options = array(
		"type" => "object",
		"subtype" => UserSupportFAQ::SUBTYPE,
		"site_guids" => false,
		"limit" => $num_display,
		"full_view" => false,
		"pagination" => false
	);
	
	if (elgg_instanceof($owner, "group")) {
		$options["container_guid"] = $owner->getGUID();
		
		$more_link .= "/group/" . $owner->getGUID() . "/all";
	}
	
	if ($content = elgg_list_entities($options)) {
		$content .= "<div class='elgg-widget-more clearfix'>";
		$content .= elgg_view("output/url", array("text" => elgg_echo("user_support:read_more"), "href" => $more_link, "class" => "float-alt"));
		$content .= "</div>";
	} else {
		$content = elgg_view("output/longtext", array("value" => elgg_echo("user_support:faq:not_found")));
	}
	
	echo $content;