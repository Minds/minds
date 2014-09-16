<?php

expose_function(
	'deck_river.get_entities',
	'deck_river_get_entities',
	array(
		'tab' => array ('type' => 'string'),
		'column' => array ('type' => 'int'),
		'time_method' => array ('type' => 'string', 'required' => false),
		'time_posted' => array ('type' => 'int', 'required' => false),
		),
	$description = "une description",
	$call_method = "GET",
	$require_api_auth = false,
	$require_user_auth = true
);

function deck_river_get_entities($tab, $column, $time_method = '', $time_posted = '') {
	global $fb; $fb->info($tab, 'prout');
}
