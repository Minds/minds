<?php
/**
 * Elastic Search 
 *
 * @package elasticsearch
 */

elgg_set_context('search');

// Get the guid
$query = get_input("q");
$object_type = get_input("o_type");
$limit = get_input("limit", 25);
$offset = get_input("offset");
$sort = array('name:desc', 'title:desc');

$call = elasticsearch_parse($query, $object_type, $sort, $limit, $offset);
$hits = $call['hits'];
$items = $hits['hits'];

if($hits['total'] > 0){
	
	foreach($items as $item){
		$guids[] = $item['_source']['guid'];
	
	}
	
	$entities = elgg_get_entities(array('guids'=>$guids));
	
	$results = elgg_view('elasticsearch/results', array('results'=>$entities));
	
	$results .= elgg_view('navigation/pagination', array('count'=>$hits['total'], 'limit'=>$limit, 'offset'=>$offset));
	
	$params['content'] = $results;
} else {
	$params['content'] = 'sorry, no results';
}
//$params['title'] = $title;
$params['sidebar'] = elgg_view('elasticsearch/stats', array('stats'=>$call));

$body = elgg_view_layout('one_sidebar', $params);

echo elgg_view_page($title, $body);
