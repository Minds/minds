<?php

$id = get_input('id');
$method = get_input('method');
$column_guid = get_input('column_guid');
$params = get_input('params', array());

$column = get_entity($column_guid);
if(!$column){
	register_error('column not found');
	return false;
}

/**
 * Get the account
 */
$account = $column->getAccount();
echo json_encode($account->doAction($id, $method, $params));
