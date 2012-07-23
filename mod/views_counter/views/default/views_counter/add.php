<?php
	/**
	 * @file views/default/views_counter/add.php
	 * @brief Add a view counter to any elgg entity
	 * 
	 * @uses $vars['entity'] An elgg entity which the views counter will be added
	 * @uses $vars['entity_guid'] An elgg entity guid that may be used instead of $vars['entity'] 
	 */

	$entity_guid = (get_input('entity_guid')) ? (get_input('entity_guid')) : ($vars['entity']->guid);

	if ($entity_guid && ($vars['full_view'] || $vars['full'] || $vars['views_counter_full_view_override'])) {
		add_views_counter($vars['entity']->guid);
	}	
