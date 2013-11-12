<?php

	$q = sanitise_string(get_input("q"));
	$content = "";
	
	$params = array(
		"query" => $q,
		"search_type" => "entities",
		"type" => "object",
		"subtype" => UserSupportHelp::SUBTYPE,
		"limit" => 5,
		"offset" => 0,
		"sort" => "relevance",
		"order" => "desc",
		"owner_guid" => ELGG_ENTITIES_ANY_VALUE,
		"container_guid" => ELGG_ENTITIES_ANY_VALUE,
		"pagination" => false,
		"full_view" => false,
		"view_type_toggle" => false
	);
	
	if($result = elgg_trigger_plugin_hook("search", "object:" . UserSupportHelp::SUBTYPE, $params, array())){
		$help_entities = $result["entities"];
	} elseif($result = elgg_trigger_plugin_hook("search", "object", $params, array())){
		$help_entities = $result["entities"];
	}
	
	if(!empty($help_entities)){
		$content .= elgg_view_entity_list($help_entities, $params);
	}
	
	// Search in FAQ
	$params["subtype"] = UserSupportFAQ::SUBTYPE;
	
	if($result = elgg_trigger_plugin_hook("search", "object:" . UserSupportFAQ::SUBTYPE, $params, array())){
		$faq_entities = $result["entities"];
	} elseif($result = elgg_trigger_plugin_hook("search", "object", $params, array())){
		$faq_entities = $result["entities"];
	}
	
	if(!empty($faq_entities)){
		$content .= elgg_view_entity_list($faq_entities, $params);
	}
	
	if(empty($help_entities) && empty($faq_entities)){
		$content = elgg_echo("notfound");
	}
	
	echo elgg_view_module("info", elgg_echo("search:results", array("\"" . $q . "\"")), $content, array("class" => "mts"));