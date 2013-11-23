<?php
$filter = (array) get_input("filter");
$query_params = array("filter" => $filter);
$faq_query = get_input("faq_query");
$menu = "";
$guids = null;

if (!empty($faq_query)) {
	$query_params["faq_query"] = $faq_query;
}

if (!empty($filter) || !empty($faq_query)) {
	// get entity guids for filtering elgg_get_tags
	$guid_options = array(
		"type" => "object",
		"subtype" => UserSupportFAQ::SUBTYPE,
		"site_guids" => false,
		"limit" => false,
		"metadata_name_value_pairs" => array(),
		"callback" => create_function('$row', 'return (int) $row->guid;')
	);
	
	foreach ($filter as $index => $tag) {
		if ($index > 2) {
			// prevent filtering on too much tags
			break;
		}
		$guid_options["metadata_name_value_pairs"][] = array("name" => "tags", "value" => $tag);
	}
	
	// text search
	if (!empty($faq_query)) {
		$guid_options["joins"] = array("JOIN " . elgg_get_config("dbprefix") . "objects_entity oe ON e.guid = oe.guid");
		$guid_options["wheres"] = array("(oe.title LIKE '%$faq_query%' OR oe.description LIKE '%$faq_query%')");
	}
	
	$guids = elgg_get_entities_from_metadata($guid_options);
	
	foreach ($filter as $index => $filter_tag) {
		if ($index > 2) {
			// prevent filtering on too much tags
			break;
		}
		$tag_query_params = $query_params;
		
		$pos = array_search($filter_tag, $tag_query_params["filter"]);
		// selected items can be deselected
		unset($tag_query_params["filter"][$pos]);
		
		if ($http_query = http_build_query($tag_query_params)) {
			$http_query = "?" . $http_query;
		}
		
		$url_options = array(
			"href" => "user_support/faq" . $http_query,
			"text" => elgg_view_icon("checkmark", "float") . $filter_tag
		);
		
		$menu .= "<li class='elgg-state-selected'>";
		$menu .= elgg_view("output/url", $url_options) . "</li>";
	}
}

if (($guids == null || (count($guids) > 1)) && (count($filter) < 3)) {
	$tag_options = array(
		"type" => "object",
		"subtype" => UserSupportFAQ::SUBTYPE,
		"tag_names" => array("tags"),
		"wheres" => array()
	);
	
	if (!empty($filter)) {
		$tag_options["wheres"][] = "(msv.string NOT IN ('" . implode("', '", $filter) . "'))";
	}
	
	if (!empty($guids)) {
		$tag_options["wheres"][] = "(e.guid IN (" . implode(",", $guids) . "))";
	}
	
	$tags = elgg_get_tags($tag_options);
	if ($tags) {
		foreach($tags as $tag) {
			$tag_text = $tag->tag;
			
			$tag_query_params = $query_params;
			
			// add the extra tag to the filter
			$tag_query_params["filter"][]  = $tag_text;
						
			$url_options = array(
				"href" => "user_support/faq?" . http_build_query($tag_query_params),
				"text" => elgg_view_icon("checkmark", "float") . $tag_text
			);
			
			$menu .= "<li>" . elgg_view("output/url", $url_options) . "</li>";
		}
	}
}

if ($menu) {
	$body = "<ul class='elgg-menu elgg-menu-page elgg-menu-faq'>" . $menu . "</ul>";
	
	echo elgg_view_module("aside", elgg_echo("filter"), $body);
}
