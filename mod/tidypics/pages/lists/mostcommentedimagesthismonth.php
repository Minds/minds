<?php

	/**
	 * Tidypics full view of an image
	 * Given a GUID, this page will try and display any entity
	 * 
	 */

	// Load Elgg engine
	include_once dirname(dirname(dirname(dirname(dirname(__FILE__))))) . "/engine/start.php";

	global $CONFIG;
	$prefix = $CONFIG->dbprefix;
	$max = 24;
	
	
	//find timestamps for first and last days of this month
	$time_info = new stdClass();
	$time_info->start = mktime(0,0,0, date("m"), 1, date("Y"));
	$time_info->end = mktime();
	
	//this works but is wildly inefficient
	//$annotations = get_annotations(0, "object", "image", "tp_view", "", "", 5000);
	
	$sql = "SELECT ent.guid, count( * ) AS views
			FROM " . $prefix . "entities ent
			INNER JOIN " . $prefix . "entity_subtypes sub ON ent.subtype = sub.id
			AND sub.subtype = 'image'
			INNER JOIN " . $prefix . "annotations ann1 ON ann1.entity_guid = ent.guid
			INNER JOIN " . $prefix . "metastrings ms ON ms.id = ann1.name_id
			AND ms.string = 'generic_comment'
			WHERE ann1.time_created BETWEEN $time_info->start AND $time_info->end
			GROUP BY ent.guid
			ORDER BY views DESC
			LIMIT $max";
	
	$result = get_data($sql);

	$entities = array();
	foreach($result as $entity) {
		$entities[] = get_entity($entity->guid);
	}
	
	tidypics_mostviewed_submenus();
	$title = elgg_echo("tidypics:mostcommentedthismonth");
	$area2 = elgg_view_title($title);
	$area2 .= elgg_view_entity_list($entities, $max, 0, $max, false);
	$body = elgg_view_layout('two_column_left_sidebar', '', $area2);
	page_draw($title, $body);
?>