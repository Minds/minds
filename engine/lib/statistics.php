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
	global $CONFIG, $SUBTYPE_CACHE, $DB;
	$entity_stats = array();
	$owner_guid = (int)$owner_guid;

	$types = array('object','user', 'group', 'notification','widget');

	$subtypes =$SUBTYPE_CACHE;

	foreach ($types as $type) {
		$count = $DB->cfs['entities_by_time']->get_count($type);
		$entity_stats[$type]['__base__'] = $count;
	}

	foreach($subtypes as $subtype){
		$type = $subtype->type;
		$subtype = $subtype->subtype;
		$count = $DB->cfs['entities_by_time']->get_count($type . ':' . $subtype);
		$entity_stats[$type][$subtype] = $count;
	}
	return $entity_stats;
}

/**
 * Return the number of users registered in the system.
 *
 * @param bool $show_deactivated Count not enabled users?
 *
 * @return int
 */
function get_number_users($show_deactivated = false) {
	global $CONFIG,$DB;

	$count = $DB->cfs['entities_by_time']->get_count('user');
	
	return $count;

}

/**
 * Return a list of how many users are currently online, rendered as a view.
 *
 * @return string
  */
function get_online_users() {
	$count = find_active_users(600, 10, 0, true);
	$objects = find_active_users(600, 10);

	if ($objects) {
		return elgg_view_entity_list($objects, array(
			'count' => $count,
			'limit' => 10,
		));
	}
	return '';
}

/**
 * Initialise the statistics admin page.
 *
 * @return void
 * @access private
 */
function statistics_init() {
	elgg_extend_view('core/settings/statistics', 'core/settings/statistics/online');
	elgg_extend_view('core/settings/statistics', 'core/settings/statistics/numentities');
}

/// Register init function
elgg_register_event_handler('init', 'system', 'statistics_init');
