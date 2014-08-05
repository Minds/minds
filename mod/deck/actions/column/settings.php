<?php

$tab_guid = get_input('tab_guid');
$column_name = get_input('column_name', 'column');
$column_guid = get_input('column_guid');
$network = get_input('network');
$params = get_input($network, array());
$submit = get_input('submit');

if (!$submit || !$tab || !$column) {
	//return;
}

// Get the settings of the current column of the current user
$owner = elgg_get_logged_in_user_entity();

if($column_guid){
	$column = get_entity($column_guid);
}else {
	$column = new ElggDeckColumn();
}
$column->name = elgg_echo($params['method']);
foreach($params as $k=>$param){
	if($param && strlen($param) > 0){
		$column->$k = $param;
	}
} 
if($column->save()){
	$column->addToTab($tab_guid);
}

$return = array();
$return['column'] = $column->guid;
$return['header'] = elgg_view('deck_river/columns/header', array('column' => $column)); 

echo json_encode($return);
return true;
