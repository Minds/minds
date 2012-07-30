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
	
	$sql = "SELECT ent.guid, u2.name AS owner, u.name AS voter, ms2.string as vote
			FROM " . $prefix . "entities ent
			INNER JOIN " . $prefix . "entity_subtypes sub ON ent.subtype = sub.id
			AND sub.subtype = 'image'
			INNER JOIN " . $prefix . "annotations ann1 ON ann1.entity_guid = ent.guid
			INNER JOIN " . $prefix . "metastrings ms ON ms.id = ann1.name_id
			AND ms.string = 'generic_rate'
			INNER JOIN " . $prefix . "metastrings ms2 ON ms2.id = ann1.value_id
			INNER JOIN " . $prefix . "users_entity u ON ann1.owner_guid = u.guid
			INNER JOIN " . $prefix . "users_entity u2 ON ent.owner_guid = u2.guid
			ORDER BY ann1.time_created DESC
			LIMIT $max";
	
	$result = get_data($sql);
	
	$title = "Recently rated images";
	$area2 = elgg_view_title($title);
	
	$entities = array();
	foreach($result as $entity) {
		$entities[] = get_entity($entity->guid);
		$full_entity = get_entity($entity->guid);
		$area2 .= "	<div class='tidypics_album_images'>
						Owner: $entity->owner<br />
						Voter: $entity->voter<br />
						Rating: $entity->vote
					</div>		
					";
		$area2 .= elgg_view_entity($full_entity);

	}
	

//	$area2 .= elgg_view_entity_list($entities, $max, 0, $max);
	$body = elgg_view_layout('two_column_left_sidebar', '', $area2);
	page_draw($title, $body);
?>