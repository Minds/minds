<?php

	$widget = elgg_extract("entity", $vars);
	
	$filter_options = array(
		UserSupportTicket::OPEN => elgg_echo("user_support:support_type:status:open"),
		UserSupportTicket::CLOSED => elgg_echo("user_support:support_type:status:closed"),
		"all" => elgg_echo("user_support:widgets:support_ticket:filter:all"),
	);
	
	$num_display = (int) $widget->num_display;
	if ($num_display < 1) {
		$num_display = 4;
	}
	
	echo "<div>";
	echo elgg_echo("user_support:widgets:support_ticket:filter");
	echo elgg_view("input/dropdown", array("name" => "params[filter]", "value" => $widget->filter, "options_values" => $filter_options, "class" => "mlm"));
	echo "</div>";
	
	echo "<div>";
	echo elgg_echo("widget:numbertodisplay");
	echo elgg_view("input/dropdown", array("name" => "params[num_display]", "value" => $num_display, "options" => range(1, 10), "class" => "mlm"));
	echo "</div>";