<?php
	/**
	 * @file views/default/views_counter/demo_entity_create_button.php
	 * @brief Displays a form that allow a quickly creation of demo entity
	 */

	$action = $vars['url'].'action/views_counter/create_demo_entity'; 
	$form_body = elgg_view('input/submit',array('value'=>elgg_echo('views_counter:create_demo_entity')));
	
	echo elgg_view('input/form',array('body'=>$form_body,'action'=>$action));
?>