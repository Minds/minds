<?php
/**
 * Filter
 */
$data = $vars['data'];
$stats = $vars['stats'];
$count = $stats['hits']['total'];

$query = get_input('q');
$t = get_input('type', 'all');
$l = get_input('license', 'all');

$path = elgg_get_site_url() . 'search/?q=' . $query;

$types = array(0 => 'all', 1 => 'photo', 2 => 'video', 3 => 'sound', 4 => 'article', 5 => 'user', 6 => 'group');

/**
 * Counts
 */

foreach($data as $item){
	foreach($types as $type){
		if($item['_type'] == $type){
			$count[$type]++;
		}
	}
}

/**
 * @todo move these to the library rather than view
 */
foreach ($types as $k => $type) {
	if($type=='all'){
		$text =elgg_echo('minds_search:type:' . $type).' ('.$count.')';
	} else {
		$text =elgg_echo('minds_search:type:' . $type);
	}
	$params = array(	'name' => 'minds_search:type:' . $k . $type, 
						'text' => $text,
						'href' => $path . '&type=' . $type.'&license='.$l, 
						'priority' =>$k
					);
	elgg_register_menu_item('page', $params);
}

/**
 * @todo add license options
 */
$licenses = array('attribution-cc', 'attribution-sharealike-cc', 'attribution-noderivs-cc', 'attribution-noncommerical-cc', 'attribution-noncommercial-sharealike-cc', 'attribution-noncommercial-noderivs-cc', 'publicdomaincco');

foreach($licenses as $license){
	$params = array(	'name'=>'minds_search:license:'.$license,
						'text'=>elgg_echo('minds:license:'.$license),
						'href'=>$path.'&type='.$t.'&license='.$license,
						'section'=>'licenses',
						'item_class'=>$l==$license ? 'elgg-state-selected':null,
					);
	elgg_register_menu_item('page', $params);
}

minds_search_sidebar_menu();
