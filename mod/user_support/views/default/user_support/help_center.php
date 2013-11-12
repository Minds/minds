<?php

	$group = elgg_extract("group", $vars);
	$contextual_help_object = elgg_extract("contextual_help_object", $vars);
	$faq = elgg_extract("faq", $vars);
	
	$help_enabled = false;
	if (elgg_get_plugin_setting("help_enabled", "user_support") != "no") {
		$help_enabled = true;
	}
	
	echo elgg_view("input/text", array("id" => "user-support-help-center-search", "name" => "q", "value" => elgg_echo("search"), "class" => "mbs", "title" => elgg_echo("search")));
	
	echo "<div>";
	
	if ($user = elgg_get_logged_in_user_entity()) {
		echo elgg_view("output/url", array("text" => elgg_echo("user_support:help_center:ask"), "href" => "#user_support_ticket_edit_form_wrapper","onclick" => "$('#user_support_ticket_edit_form_wrapper').toggle();$.fancybox.resize();", "class" => "elgg-button elgg-button-action")) . "&nbsp;";
		echo elgg_view("output/url", array("text" => elgg_echo("user_support:menu:support_tickets:mine"), "href" => "user_support/support_ticket/owner/" . $user->username, "class" => "elgg-button elgg-button-action")) . "&nbsp;";
	}
	
	echo elgg_view("output/url", array("text" => elgg_echo("user_support:menu:faq"), "href" => "user_support/faq", "class" => "elgg-button elgg-button-action")) . "&nbsp;";
	
	if (!empty($group)) {
		echo elgg_view("output/url", array("text" => elgg_echo("user_support:help_center:help_group"), "href" => $group->getURL(), "class" => "elgg-button elgg-button-action")) . "&nbsp;";
	}
	
	if (elgg_is_admin_logged_in() && empty($contextual_help_object) && $help_enabled && elgg_is_xhr()) {
		echo elgg_view("output/url", array("text" => elgg_echo("user_support:help_center:help"), "href" => "#", "onclick" => "$('#user_support_help_edit_form_wrapper').toggle();$.fancybox.resize();", "class" => "elgg-button elgg-button-action")) . "&nbsp;";
	}

	echo "</div>";
	
	if (elgg_is_xhr() && $help_enabled) {
		if (!empty($contextual_help_object)) {
			echo elgg_view_module("info", elgg_echo("user_support:help_center:help:title"), elgg_view_entity($contextual_help_object, array("title" => "")), array("id" => "user_support_help_center_help", "class" => "mts"));
		}
		
		if (elgg_is_admin_logged_in()) {
			$form = elgg_view_form("user_support/help/edit", null, $vars);
			echo elgg_view_module("info", elgg_echo("user_support:forms:help:title"), $form, array("id" => "user_support_help_edit_form_wrapper", "class" => "hidden mts"));
		}
	}
	
	if (!empty($faq)) {
		echo elgg_view_module("info", elgg_echo("user_support:help_center:faq:title"), $faq, array("class" => "mts"));
	}
	
	if (elgg_is_logged_in()) {
		$form = elgg_view_form("user_support/support_ticket/edit", null, $vars);
		echo elgg_view_module("info", elgg_echo("user_support:help_center:ask"), $form, array("id" => "user_support_ticket_edit_form_wrapper", "class" => "hidden mts"));
	}
	
	echo "<div id='user_support_help_search_result_wrapper' class='hidden'></div>";
	