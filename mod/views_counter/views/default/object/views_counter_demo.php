<?php
	/**
	 * @file views/default/object/views_counter_demo.php
	 * @brief Displays the views counter entity with the views counter and a delete button
	 */

if ($vars['full']) {
	// Just use add_views_counter() and pass the entity guid for have the views counter setted up for that entity
	add_views_counter($vars['entity']->guid);
	echo elgg_view('views_counter');
	echo '<br />';
	echo elgg_view('export/entity', $vars);
} else {
	echo elgg_view('object/listing_view',$vars);
	}
