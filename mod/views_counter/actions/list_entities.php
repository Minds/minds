<?php
	/**
	 * @file actions/list_entities.php
	 * @brief Just follow the user to the correct page for list the selected type
	 */
	
	$entity_type = get_input('entity_type');
	forward(elgg_get_site_url() . 'views_counter/list_entities/'.$entity_type);
