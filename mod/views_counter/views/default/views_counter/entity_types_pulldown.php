<?php
	/**
	 * @file views/default/views_counter/entity_types_pulldown.php
	 * @brief Displays a pulldown with the entity types and subtypes that the views counter plugin may be added on
	 */

	$entity_type = ($vars['entity_type']) ? ($vars['entity_type']) : 'users';
	$valid_types = get_valid_types_for_views_counter();
?>

<label>
	<?php
		echo elgg_echo('views_counter:select_type');
		echo elgg_view('input/dropdown',array('name'=>'entity_type','options' => $valid_types, 'value' => $entity_type));
	?>
</label>