<?php

	$widget = elgg_extract("entity", $vars);
	$owner = $widget->getOwnerEntity();
	
	$filter = $widget->filter;
	if (empty($filter)) {
		$filter = UserSupportTicket::OPEN;
	}
	
	$num_display = (int) $widget->num_display;
	if ($num_display < 1) {
		$num_display = 4;
	}
	
	$more_link = "user_support/support_ticket/owner/" . $owner->username;
	
	$options = array(
		"type" => "object",
		"subtype" => UserSupportTicket::SUBTYPE,
		"owner_guid" => $widget->getOwnerGUID(),
		"limit" => $num_display,
		"pagination" => false,
		"full_view" => false,
		"order_by" => "e.time_updated desc"
	);
	
	if ($filter != "all") {
		$options["metadata_name_value_pairs"] = array("status" => $filter);
		
		if ($filter == UserSupportTicket::CLOSED) {
			$more_link .= "/archive";
		}
	}
	
	if ($content = elgg_list_entities_from_metadata($options)) {
		$content .= "<div class='elgg-widget-more clearfix'>";
		$content .= elgg_view("output/url", array("text" => elgg_echo("user_support:read_more"), "href" => $more_link, "class" => "float-alt"));
		$content .= "</div>";
	} else {
		$content = elgg_view("output/longtext", array("value" => elgg_echo("notfound")));
	}
	
	echo $content;