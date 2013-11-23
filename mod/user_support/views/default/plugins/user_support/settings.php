<?php

	$plugin = $vars["entity"];
	
	$noyes_options = array(
		"no" => elgg_echo("option:no"),
		"yes" => elgg_echo("option:yes")
	);
	
	$yesno_options = array_reverse($noyes_options, true);
	
	$floating_button_options = array(
		"no" => elgg_echo("option:no"),
		"left|top" => elgg_echo("user_support:settings:help_center:show_floating_button:left_top"),
		"left|bottom" => elgg_echo("user_support:settings:help_center:show_floating_button:left_bottom"),
		"right|top" => elgg_echo("user_support:settings:help_center:show_floating_button:right_top"),
		"right|bottom" => elgg_echo("user_support:settings:help_center:show_floating_button:right_bottom")
	);
	
	$help = "<label>" . elgg_echo("user_support:settings:help:enabled") . "&nbsp;";
	$help .= elgg_view("input/dropdown", array("name" => "params[help_enabled]", "value" => $plugin->help_enabled, "options_values" => $yesno_options)) . "</label><br />";
	
	echo elgg_view_module("inline", elgg_echo("user_support:settings:help:title"), $help);

	$help_center = "<label>" . elgg_echo("user_support:settings:help_center:add_help_center_site_menu_item") . "&nbsp;";
	$help_center .= elgg_view("input/dropdown", array("name" => "params[add_help_center_site_menu_item]", "value" => $plugin->add_help_center_site_menu_item, "options_values" => $noyes_options)) . "</label><br />";
	
	$help_center .= "<label>" . elgg_echo("user_support:settings:help_center:show_floating_button") . "&nbsp;";
	$help_center .= elgg_view("input/dropdown", array("name" => "params[show_floating_button]", "value" => $plugin->show_floating_button, "options_values" => $floating_button_options)) . "</label><br />";
	
	$help_center .= "<label>" . elgg_echo("user_support:settings:help_center:float_button_offset") . "&nbsp;";
	$help_center .= elgg_view("input/text", array("name" => "params[float_button_offset]", "value" => (int) $plugin->float_button_offset, "class" => "user-support-setting-small-input")) . "px</label><br />";
	
	$help_center .= "<label>" . elgg_echo("user_support:settings:help_center:show_as_popup") . "&nbsp;";
	$help_center .= elgg_view("input/dropdown", array("name" => "params[show_as_popup]", "value" => $plugin->show_as_popup, "options_values" => $yesno_options)) . "</label><br />";
	
	echo elgg_view_module("inline", elgg_echo("user_support:settings:help_center:title"), $help_center);
	
	$faq = "<label>" . elgg_echo("user_support:settings:faq:add_faq_site_menu_item") . "&nbsp;";
	$faq .= elgg_view("input/dropdown", array("name" => "params[add_faq_site_menu_item]", "value" => $plugin->add_faq_site_menu_item, "options_values" => $yesno_options)) . "</label><br />";
	
	$faq .= "<label>" . elgg_echo("user_support:settings:faq:add_faq_footer_menu_item") . "&nbsp;";
	$faq .= elgg_view("input/dropdown", array("name" => "params[add_faq_footer_menu_item]", "value" => $plugin->add_faq_footer_menu_item, "options_values" => $noyes_options)) . "</label><br />";
	
	echo elgg_view_module("inline", elgg_echo("user_support:settings:faq:title"), $faq);

	$support_tickets = "";
	if (elgg_is_active_plugin("groups")) {
		$group_options = array(
				"type" => "group",
				"limit" => false
		);
	
		if($groups = elgg_get_entities($group_options)){
			$group_options_value = array();
				
			foreach ($groups as $group) {
				$group_options_value[$group->getGUID()] = $group->name;
			}
				
			natcasesort($group_options_value);
				
			$group_options_value = array(0 => elgg_echo("user_support:settings:support_tickets:help_group:none")) + $group_options_value;
		}
	
		$support_tickets .= "<div>";
		$support_tickets .= elgg_echo("user_support:settings:support_tickets:help_group");
		$support_tickets .= "&nbsp;" . elgg_view("input/dropdown", array("name" => "params[help_group_guid]", "options_values" => $group_options_value, "value" => (int) $plugin->help_group_guid));
		$support_tickets .= "</div>";
	}
	
	echo elgg_view_module("inline", elgg_echo("user_support:settings:support_tickets:title"), $support_tickets);