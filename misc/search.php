<?php

require_once(dirname(dirname(__FILE__)) . '/engine/start.php');

elgg_set_ignore_access();

//remove all users from search..
$limit = 100;
$serviceSearch = new MindsSearch();
while(true){
//	break; //skip
	$call = $serviceSearch->search('minds','user', 'all', 'all', 'all', 100, 0);
	$total = $call['hits']['total'];
	echo $total;
	if($total < 1){
		echo "We have reached the end of the line  Limit is $limit/total is $total\n";
		break;
	}
	$results = $call['hits']['hits'];
	var_dump($results); 
}
