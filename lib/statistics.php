<?php
/**
 * Elgg statistics library.
 *
 * This file contains a number of functions for obtaining statistics about the running system.
 * These statistics are mainly used by the administration pages, and is also where the basic
 * views for statistics are added.
 *
 * @package Elgg.Core
 * @subpackage Statistics
 */

/**
 * Return an array reporting the number of various entities in the system.
 *
 * @param int $owner_guid Optional owner of the statistics
 *
 * @return array
 */
function get_entity_statistics($owner_guid = 0) {
	global $CONFIG, $SUBTYPE_CACHE;
	
	$db = new Minds\Core\Data\Call('entities_by_time', NULL, NULL, 1000000, 100000);
	
	$entity_stats = array();
	$owner_guid = (int)$owner_guid;

	if($owner_guid != 0){
		$prepend = ':user:'.$owner_guid;
	}

	$types = array(
//		'object',
		'user',
	);

	$subtypes =$SUBTYPE_CACHE;

	foreach ($types as $type) {
		$count = $db->countRow($type . $prepend);
		$entity_stats[$type]['__base__'] = $count;
	}

	foreach($subtypes as $subtype){
		$type = $subtype->type;
		$subtype = $subtype->subtype;
		$count = $db->countRow($type . ':' . $subtype . $prepend);
		$entity_stats[$type][$subtype] = $count;
	}
	return $entity_stats;
}

