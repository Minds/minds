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
$type = get_input("type",'all');
$license = get_input("license", 'all');
$source = get_input("source", 'all');
$category = get_input("category", 'all');

/**
 * Update orientation
 * @todo make this a trigger...
 */
if(!elgg_get_plugin_user_setting('search', elgg_get_logged_in_user_guid(), 'minds_search')){
	elgg_set_plugin_user_setting('search', true);
}

/**
 * Minds Search. Local
 */
/*$sort = array('name:desc', 'title:desc');
$call = elasticsearch_parse($query, $object_type, $sort, 5, $offset);
$hits = $call['hits'];
$items = $hits['hits'];

if (count($items) > 0) {

	foreach ($items as $item) {
		$guids[] = $item['_source']['guid'];

	}

	$entities = elgg_get_entities(array('guids' => $guids));

	$results = elgg_view('minds_search/results', array('results' => $entities));

	$results .= elgg_view('navigation/pagination', array('count' => $hits['total'], 'limit' => $limit, 'offset' => $offset));

} else {
	$params['content'] = 'sorry, no results';
}
//$params['title'] = $title;
$params['sidebar'] = elgg_view('minds_search/stats', array('stats' => $call));*/

$serviceSearch = new MindsSearch();
$call = $serviceSearch->search($query,$type, $source, $license, $category,  $limit,$offset);
$hits = $call['hits'];
$items = $hits['hits'];
if (count($items) > 0) {
	$results .= elgg_view('minds_search/services/services', array('data'=>$items));
	$results .= elgg_view('navigation/pagination', array('count'=>$hits['total'], 'limit'=>$limit, 'offset'=>$offset));
		
	$params['class'] = 'minds-search';
} else {
	$params['content'] = 'sorry, no results';
}

$params['layout'] = 'one_column';
$content = elgg_view('minds_search/nav');
$content .= $results;
$params['content'] = $content;

$title_block = elgg_view_title('Searching - <i>' .$query . '</i>', array('class' => 'elgg-heading-main'));
$params['header'] = <<<HTML
<div class="elgg-head clearfix">
	$title_block$menu
</div>
HTML;

if (!$query) {
	$params['layout'] = 'one_column';
	$params['content'] = elgg_view('minds_search/splash');
}

$body = elgg_view_layout($params['layout'], $params);

echo elgg_view_page($title, $body);
