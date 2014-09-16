<?php

global $CONFIG, $jsonexport;
$dbprefix = $CONFIG->dbprefix;

// Get callbacks
$params = get_input('params', 'false');
$time_method = get_input('time_method', 'false');
$time_posted = get_input('time_posted', 'false');

$jsonexport = array();

// detect network
if ($params && $method = $params['method']) {

	unset($params['method']);

	$columns = deck_river_get_networks_account('tumblr_account');
	$column = $columns[0]; // @todo why the first ? Check limit rate and take the most free ?

	$account = $column->getAccount();

	$account->doAction('', $method, $params);

	$jsonexport['results'] = $result;
}

echo json_encode($jsonexport);
