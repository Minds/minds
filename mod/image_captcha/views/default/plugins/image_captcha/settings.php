<?php
	
	$options = array(
		"general" => elgg_echo("image_captcha:settings:icons:general"), 
		"fruit" => elgg_echo("image_captcha:settings:icons:fruit")
		);
	
	$body = "<label>" . elgg_echo("image_captcha:settings:icons") . "</label><br />";
	$body .= elgg_view("input/dropdown", array("name" => "params[icon_types]", "value" => $vars["entity"]->icon_types, "options_values" => $options));
	
	echo $body;