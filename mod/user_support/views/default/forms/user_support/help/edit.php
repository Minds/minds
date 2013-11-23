<?php 

	$entity = elgg_extract("contextual_help_object", $vars);

	if(!empty($entity)){
		$desc = $entity->description;
		$tags = $entity->tags;
		
		$form_body = elgg_view("input/hidden", array("name" => "guid", "value" => $entity->getGUID()));
		$form_body .= elgg_view("input/hidden", array("name" => "help_context", "value" => $entity->help_context));
	} else {
		
		$desc = "";
		$tags = array();
		$help_context = elgg_extract("help_context", $vars);
		
		if(!empty($help_context)){
			$form_body = elgg_view("input/hidden", array("name" => "help_context", "value" => $help_context));
		} else {
			$form_body = elgg_view("input/hidden", array("name" => "help_context", "value" => user_support_get_help_context()));
		}
	}
	
	$form_body .= "<div>";
	$form_body .= "<label>" . elgg_echo("description") . "</label>";
	$form_body .= elgg_view("input/plaintext", array("name" => "description", "value" => $desc));
	$form_body .= "</div>";
	
	$form_body .= "<div>";
	$form_body .= "<label>" . elgg_echo("tags") . "</label>";
	$form_body .= elgg_view("input/tags", array("name" => "tags", "value" => $tags));
	$form_body .= "</div>";
	
	$form_body .= "<div class='elgg-foot'>";
	$form_body .= elgg_view("input/submit", array("value" => elgg_echo("save")));
	$form_body .= "</div>";

	echo $form_body;