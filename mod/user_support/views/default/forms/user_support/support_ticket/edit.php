<?php

	$types_values = array(
		"question" => elgg_echo("user_support:support_type:question"),
		"bug" => elgg_echo("user_support:support_type:bug"),
		"request" => elgg_echo("user_support:support_type:request"),
	);

	$entity = elgg_extract("entity", $vars);
	
	if (!empty($entity)) {
		$title = $entity->description;
		$tags = $entity->tags;
		$help_url = $entity->help_url;
		$support_type = $entity->support_type;
		
		$form_body = elgg_view("input/hidden", array("name" => "guid", "value" => $entity->getGUID()));
		$form_body .= elgg_view("input/hidden", array("name" => "help_context", "value" => $entity->help_context));
	} else {
		$title = "";
		$tags = array();
		$help_url = elgg_extract("help_url", $vars);
		$support_type = "";
		$help_context = elgg_extract("help_context", $vars);
		
		if(!empty($help_context)){
			$form_body = elgg_view("input/hidden", array("name" => "help_context", "value" => $help_context));
		} else {
			$form_body = elgg_view("input/hidden", array("name" => "help_context", "value" => user_support_get_help_context()));
		}
	}
	
	$form_body .= "<div>";
	$form_body .= "<label>" . elgg_echo("user_support:question") . "</label>";
	$form_body .= elgg_view("input/plaintext", array("name" => "title", "value" => $title));
	$form_body .= "</div>";
	
	$form_body .= "<div>";
	$form_body .= "<label>" . elgg_echo("user_support:support_type") . "</label>";
	$form_body .= "&nbsp;" . elgg_view("input/dropdown", array("name" => "support_type", "options_values" => $types_values, "value" => $support_type));
	$form_body .= "</div>";
	
	$form_body .= "<div>";
	$form_body .= "<label>" . elgg_echo("tags") . "</label>";
	$form_body .= elgg_view("input/tags", array("name" => "tags", "value" => $tags));
	$form_body .= "</div>";
	
	if ($help_url) {
		$form_body .= "<div>";
		$form_body .= "<label>" . elgg_echo("user_support:url") . "</label>";
		$form_body .= elgg_view("input/url", array("name" => "help_url", "value" => $help_url));
		$form_body .= "</div>";
	}
	
	$form_body .= "<div class='elgg-foot'>";
	$form_body .= elgg_view("input/hidden", array("name" => "elgg_xhr", "value" => elgg_is_xhr()));
	$form_body .= elgg_view("input/submit", array("value" => elgg_echo("save")));
	$form_body .= "</div>";
	
	echo $form_body;