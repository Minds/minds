<?php
	/**
	 * @file actions/create_demo_entity.php
	 * @brief Creates a views_counter demo entity 
	 */

	$options = array('type'=>'object','subtypes'=>'views_counter_demo');
	$demo_entities = elgg_get_entities($options);
	
	if ($demo_entities) {
		$demo_entities_counter = count($demo_entities);
	} else {
		$demo_entities_counter = 0;
	}
	
	$entity = new ElggObject();
	$entity->title = 'Views counter '.($demo_entities_counter + 1);
	$entity->subtype= 'views_counter_demo';
	$entity->save();
	
	system_message(elgg_echo('views_counter:demo_entity_created'));
	
	forward(REFERER);
