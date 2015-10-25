<?php 

$data = $vars['data'];

global $jsonexport;


$return = array();

foreach($data as $result){
	$item['_type']  = $result['_type'];
	$item['_source']  = $result['_source'];
	$return[] = $item;
}
$jsonexport['result'] = $return;




