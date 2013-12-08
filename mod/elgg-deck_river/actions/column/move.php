<?php

$tab = get_input('tab');
$column = get_input('column');
$position = get_input('position');

// Get the settings of the current column of the current user
$owner = elgg_get_logged_in_user_entity();
$user_river_options = json_decode($owner->getPrivateSetting('deck_river_settings'), true);

if ($tab && $column) {

	// sort the list and remove the list that's being moved from the array
	$sorted_columns = array();
	foreach ($user_river_options[$tab] as $index => $col) {
		if ($index != $column) {
			$sorted_columns[$index] = $col;
		}
	}

	// split the array in two and recombine with the moved list in middle
	$before = array_slice($sorted_columns, 0, $position);
	$before[$column] = $user_river_options[$tab][$column];
	$after = array_slice($sorted_columns, $position);
	$columns = array_merge($before, $after);

	// save new order
	$user_river_options[$tab] = $columns;
	$owner->setPrivateSetting('deck_river_settings', json_encode($user_river_options));

}
