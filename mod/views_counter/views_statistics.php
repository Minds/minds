<?php
	/**
	 * @file mod/views_counter/views_statistics.php
	 * @brief Displays the views statistics for one entity
	 */
	
	admin_gatekeeper();
	
	$entity_guid = get_input('entity_guid');
	$entity = get_entity($entity_guid);
	
	$title = elgg_echo('views_counter:views_statistics');	
	
	$area2 = elgg_view_title($title);
	// Shows the views statistics for an elgg entity
	$area2 .= elgg_view('views_counter/views_statistics',array('entity'=>$entity));
	
	$options = array(
	  'content' => $area2,
	  'sidebar' => $area1,
	);
	
	
	$body = elgg_view_layout('admin', $options);
	
	echo elgg_view_page($title, $body);
