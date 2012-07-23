<?php
	/**
	 * @file admin_page.php
	 * @brief Displays the admin page for views_counter plugin
	 */

	require_once(dirname(dirname(dirname(__FILE__))).'/engine/start.php');
	
	admin_gatekeeper();
	
	// Get the subtype of entities of the type object with exception of user and group that gets a special treatment
	$entity_type = get_input('entity_type','user');
	$title = elgg_echo('views_counter:admin_page');

	// Shows the types pulldown that the admin may add a views counter system
	$area2 = elgg_view('views_counter/forms/types_pulldown',array('entity_type'=>$entity_type));
	
	// List the entities of the previous selected subtype
	$area2 .= elgg_view('views_counter/list_entities',array('entity_type'=>$entity_type));
	
	$body = elgg_view_layout('one_sidebar', array('content' => $area2));
	
	echo elgg_view_page($title, $body);
