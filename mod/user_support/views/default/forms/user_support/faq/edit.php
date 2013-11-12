<?php

	$noyes_options = array(
		"no" => elgg_echo("option:no"),
		"yes" => elgg_echo("option:yes")
	);

	$help_context = elgg_extract("help_context", $vars);
	$form_data = "";
	
	if ($entity = elgg_extract("entity", $vars, false)) {
		$title = elgg_get_sticky_value("user_support_faq", "title", $entity->title);
		$desc = elgg_get_sticky_value("user_support_faq", "description", $entity->description);
		$access_id = (int) elgg_get_sticky_value("user_support_faq", "access_id", $entity->access_id);
		$container_guid = $entity->getContainerGUID();
		
		$tags = elgg_get_sticky_value("user_support_faq", "tags", $entity->tags);
		$comments = elgg_get_sticky_value("user_support_faq", "allow_comments", $entity->allow_comments);
		$context = elgg_get_sticky_value("user_support_faq", "help_context", $entity->help_context);
		if(!empty($context) && !is_array($context)){
			$context = array($context);
		} elseif(empty($context)){
			$context = array();
		}
		
		$submit_text = elgg_echo("edit");
		
		$form_data = elgg_view("input/hidden", array("name" => "guid", "value" => (int) $entity->getGUID()));
	} elseif ($annotation = elgg_extract("annotation", $vars, false)) {
		$entity = $annotation->getEntity();
		
		$title = elgg_get_sticky_value("user_support_faq", "title", $entity->title);
		$desc = elgg_get_sticky_value("user_support_faq", "description", $annotation->value);
		$access_id = elgg_get_sticky_value("user_support_faq", "access_id", $annotation->access_id);
		$container_guid = elgg_get_page_owner_guid();
		
		$tags = elgg_get_sticky_value("user_support_faq", "tags", $entity->tags);
		$comments = elgg_get_sticky_value("user_support_faq", "allow_comments", "no");
		$context = elgg_get_sticky_value("user_support_faq", "help_context", $entity->help_context);
		if(!empty($context) && !is_array($context)){
			$context = array($context);
		} elseif(empty($context)){
			$context = array();
		}
		
		$submit_text = elgg_echo("save");
	} else {
		$title = elgg_get_sticky_value("user_support_faq", "title");
		$desc = elgg_get_sticky_value("user_support_faq", "description");
		$access_id = elgg_get_sticky_value("user_support_faq", "access_id", get_default_access());
		$container_guid = elgg_get_page_owner_guid();
		
		$tags = elgg_get_sticky_value("user_support_faq", "tags", array());
		$comments = elgg_get_sticky_value("user_support_faq", "allow_comments", "no");
		$context = elgg_get_sticky_value("user_support_faq", "help_context", array());
		
		$submit_text = elgg_echo("save");
	}
	
	elgg_clear_sticky_form("user_support_faq");

	$form_data .= "<div>";
	$form_data .= "<label>" . elgg_echo("user_support:question") . "</label>";
	$form_data .= elgg_view("input/text", array("name" => "title", "value" => $title));
	$form_data .= "</div>";
	
	$form_data .= "<div>";
	$form_data .= "<label>" . elgg_echo("user_support:anwser") . "</label>";
	$form_data .= elgg_view("input/longtext", array("name" => "description", "value" => $desc));
	$form_data .= "</div>";

	$form_data .= "<div>";
	$form_data .= "<label>" . elgg_echo("tags") . "<label>";
	$form_data .= elgg_view("input/tags", array("name" => "tags", "value" => $tags));
	$form_data .= "</div>";
	
	if(elgg_is_admin_logged_in() && !empty($help_context)){
		$form_data .= "<div>";
		$form_data .= "<label>" . elgg_echo("user_support:help_context") . "</label><br />";
		
		$form_data .= "<select name='help_context[]' multiple='multiple' size='" . min(count($help_context), 5) . "'>";
		foreach($help_context as $hc){
			$selected = "";
			if(in_array($hc, $context)){
				$selected = "selected='selected'";
			}
			$form_data .= "<option value='" . $hc . "' " . $selected . ">" . $hc . "</option>";
		}
		$form_data .= "</select>";
		$form_data .= "</div>";
	}
	
	$form_data .= "<div>";
	$form_data .= "<label>" . elgg_echo("access") . "</label>";
	$form_data .= "&nbsp;" . elgg_view("input/access", array("name" => "access_id", "value" => $access_id));
	$form_data .= "</div>";

	$form_data .= "<div>";
	$form_data .= "<label>" . elgg_echo("user_support:allow_comments") . "</label>";
	$form_data .= "&nbsp;" . elgg_view("input/dropdown", array("name" => "allow_comments", "options_values" => $noyes_options, "value" => $comments));
	$form_data .= "</div>";
	
	$form_data .= "<div class='elgg-foot'>";
	$form_data .= elgg_view("input/hidden", array("name" => "container_guid", "value" => $container_guid));
	$form_data .= elgg_view("input/submit", array("value" => $submit_text));
	$form_data .= "</div>";

	echo $form_data;