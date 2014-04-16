<?php

require_once(dirname(dirname(__FILE__)) . '/engine/start.php');

elgg_set_ignore_access();

//remove all users from search..
$limit = 100;
$serviceSearch = new MindsSearch();
while(true){
	break; //skip
	$call = $serviceSearch->search('minds','user', 'all', 'all', 'all', 100, 0);
	$total = $call['hits']['total'];
	if($total < 1){
		echo "We have reached the end of the line  Limit is $limit/total is $total\n";
		break;
	}
	$results = $call['hits']['hits'];

	foreach($results as $result){
		$es = new elasticsearch();
		$es->index = 'ext';
		$es->remove($result['_type'], $result['_id']);
		echo "removed {$result['_id']} \n";

	}
}

//now re-add all of our users
$offset = "264279897667538944";
while(true){
	$users = elgg_get_entities(array('type'=>'user', 'limit'=>100, 'offset'=>$offset));
	if(count($users) < 1){
		echo "we have reached the end of the line \n";
		break;
	}
	$offset = end($users)->guid;
	foreach($users as $user){

		$es = new elasticsearch();
		$es->index = 'ext';
		$item = elasticsearch_render($user);
		$es->add($item->type, $item->id, json_encode($item));
		echo "indexed $user->guid \n";
	}
}
