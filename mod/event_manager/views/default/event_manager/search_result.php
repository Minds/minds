<?php

	$result = elgg_view('event_manager/event_sort_menu');
	
	$options = array(
		"count" => $vars["count"],
		"offset" => $vars["offset"],
		"full_view" => false,
		"pagination" => false
	);
	
	$list = elgg_view_entity_list($vars["entities"], $options);
	
	$result .= "<div id='event_manager_event_listing'>";
	if(!empty($list)){
		$result .= $list;
	} else {
		$result .= elgg_echo('event_manager:list:noresults');
	}
	$result .= "</div>";
	
	$result .= elgg_view("event_manager/onthemap", $vars);
	
	if($vars["count"] > EVENT_MANAGER_SEARCH_LIST_LIMIT) {
		$result .= '<div id="event_manager_event_list_search_more" rel="'. ((isset($vars["offset"])) ? $vars["offset"] : EVENT_MANAGER_SEARCH_LIST_LIMIT).'">';
		$result .= elgg_echo('event_manager:list:showmorevents');
		$result .= ' (' . ($vars["count"] - ($offset + EVENT_MANAGER_SEARCH_LIST_LIMIT)) . ')</div>';
	}
	
	echo elgg_view_module("main", "", $result);
	