<?php
/**
 * Filter
 */
$query = get_input('q');
$type = get_input('type');

$path = elgg_get_site_url().'search/?q='.$query;

$types = array('all', 'photo', 'video', 'sound');
/**
 * @todo move these to the library rather than view
 */
foreach($types as $type){
	$params = array(
			'name' => 'minds_search:type:'.$type,
			'text' => elgg_echo('minds_search:type:'.$type),
			'href' => $path.'&type='.$type,
		);
	elgg_register_menu_item('page', $params);
}

/**
 * @todo add license options
 */
