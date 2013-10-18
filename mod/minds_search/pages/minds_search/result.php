<?php
/**
 * Minds Search
 * Individual Result page
 */

elgg_set_context('search');

$id = get_input("id");

$search = new MindsSearch();
$item = $search->result($id);
$item = $item['hits']['hits'][0];

if(!$item){
	register_error('item does not exist');
	forward();
}

$results = elgg_view('minds_search/services/result', array('result'=>$item));
		
$params['layout'] = 'one_column';
$params['filter'] = false;
$params['content'] = $results;
//$params['sidebar'] = elgg_view('minds_search/sidebar', array('data'=>$items,'stats'=>$call));
$params['class'] = 'minds-search';

$body = elgg_view_layout($params['layout'], $params);

echo elgg_view_page($title, $body);
