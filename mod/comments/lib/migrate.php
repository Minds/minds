<?php

require_once(dirname(dirname(dirname(dirname(__FILE__)))).'/engine/start.php');
global $CONFIG;

$client = new \Elasticsearch\Client(array('hosts'=>array(\elgg_get_plugin_setting('server_addr','search'))));
$params = array();
$body['query']['query_string']['query'] = 'minds';
$params['index'] = $CONFIG->elasticsearch_prefix . 'comments';
$params['type'] = 'entity';
$params['size'] = 2000;
$params['from'] = 0;
$params['body']  = $body;

$result = $client->search($params);

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
