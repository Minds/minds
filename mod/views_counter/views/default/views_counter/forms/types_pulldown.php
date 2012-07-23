<?php
	/**
	 * @file views/default/views_counter/forms/types_pulldown.php
	 * @brief Displays a pulldown with the valid types for the views counter plugin
	 */

	$action = $vars['url'].'action/views_counter/list_entities';
	$form_body = elgg_view('views_counter/entity_types_pulldown',$vars);
	$form_body .= ' ';
	$form_body .= elgg_view('input/submit',array('value'=>elgg_echo('views_counter:see_entities')));
	echo elgg_view('input/form',array('action'=>$action,'body'=>$form_body));
?>