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
$limit = get_input("limit", 32);
$offset = get_input("offset");

$services = get_input("services", array('all'));
$types = get_input("types",'all');

/** 
 * Minds Search. Appears first.
 */
if(elgg_get_plugin_setting('elasticsearch_enabled')=='Yes'){
	$sort = 'name:asc';
	$call = elasticsearch_parse($query, $object_type, $sort, $limit, $offset);
	$hits = $call['hits'];
	$items = $hits['hits'];
	
	if($hits['total'] > 0){
		
		foreach($items as $item){
			$guids[] = $item['_source']['guid'];
		
		}
		
		$entities = elgg_get_entities(array('guids'=>$guids));
		
		$results = elgg_view('minds_search/results', array('results'=>$entities));
		
		$results .= elgg_view('navigation/pagination', array('count'=>$hits['total'], 'limit'=>$limit, 'offset'=>$offset));
		
	} else {
		$params['content'] = 'sorry, no results';
	}
	//$params['title'] = $title;
	$params['sidebar'] = elgg_view('minds_search/stats', array('stats'=>$call));
}
$page = ($offset/$limit)+1;

//$serviceSearch = new MindsSearch();
//$results .= elgg_view('minds_search/services', array('data'=>$serviceSearch->search($query,$types, $services, $limit,$page)));
//$results .= elgg_view('navigation/pagination', array('count'=>$limit*3, 'limit'=>$limit, 'offset'=>$offset));

minds_search_sidebar_menu();

$params['layout'] = 'one_sidebar';
$params['content'] = $results;
$params['sidebar'] = elgg_view('minds_search/sidebar', array());
$params['class'] = 'minds-search';


if(!$query){
	$params['layout'] = 'one_column';
	$params['content'] = elgg_view('minds_search/splash');
}

$body = elgg_view_layout($params['layout'], $params);

echo elgg_view_page($title, $body);
