<?php
/**
 * Elastic Search 
 *
 * @package elasticsearch
 */

elgg_set_context('search');

// Get the guid
$query = get_input("q");

$call = elasticsearch_parse($query);
$hits = $call['hits'];
$items = $hits['hits'];


foreach($items as $item){
	$guids[] = $item['_source']['guid'];

}

$entities = elgg_list_entities(array('guids'=>$guids));


$params['content'] = $entities;
//$params['title'] = $title;
//$params['sidebar'] = $sidebar;

$body = elgg_view_layout('content', $params);

echo elgg_view_page($title, $body);
