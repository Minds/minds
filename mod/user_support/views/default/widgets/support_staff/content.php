<?php

	$widget = elgg_extract("entity", $vars);
	
	$num_display = (int) $widget->num_display;
	if ($num_display < 1) {
		$num_display = 4;
	}
	
	$options = array(
		"type" => "object",
		"subtype" => UserSupportTicket::SUBTYPE,
		"limit" => $num_display,
		"metadata_name_value_pairs" => array("status" => UserSupportTicket::OPEN),
		"pagination" => false,
		"full_view" => false,
		"order_by" => "e.time_updated desc"
	);
	
	if ($content = elgg_list_entities_from_metadata($options)) {
		$content .= "<div class='elgg-widget-more clearfix'>";
		$content .= elgg_view("output/url", array("text" => elgg_echo("user_support:read_more"), "href" => "user_support/support_ticket", "class" => "float-alt"));
		$content .= "</div>";
	} else {
		$content = elgg_view("output/longtext", array("value" => elgg_echo("notfound")));
	}
	
	echo $content;