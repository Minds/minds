<?php

require_once(dirname(dirname(dirname(dirname(__FILE__)))).'/engine/start.php');

$es = new elasticsearch();
$es->index = $CONFIG->elasticsearch_prefix . 'comments';

$result = $es->query('entity', NULL, 'time_created:asc',2000,4000);
foreach($result['hits']['hits'] as $item){
	$data = $item['_source'];
var_dump($data);
	$comment = new \minds\plugin\comments\entities\comment();
	$comment->description = $data['description'];
	$comment->parent_guid = $data['pid'];
	$comment->owner_guid = $data['owner_guid'];
	$comment->time_created = $data['time_created'];

	$comment->save();
}
