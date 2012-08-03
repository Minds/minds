<?php

$elgg_path = str_replace(elgg_get_site_url(), '', $vars['path']);
$path = explode('/', $elgg_path);

ob_start();
elgg_set_viewtype('json');
page_handler(array_shift($path), implode('/', $path));
elgg_set_viewtype('default');
$out = ob_get_contents();
ob_end_clean();


$json = json_decode($out);

switch(get_input('items_type')){
	case 'entity':
		foreach ($json as $child) foreach ($child as $grandchild) $json = $grandchild;
		
		// Removing duplicates
		// This will be unnecessary when #4504 fixed.
		$buggy = $json;
		$json = array();
		$guids = array();
		foreach($buggy as $item) {
			$guids[] = $item->guid;
		}
		$guids = array_unique($guids);
		foreach(array_keys($guids) as $i) {
			$json[$i] = $buggy[$i];
		}
		
		break;
	case 'annotation': 
		foreach ($json as $child) {
			$json = $child;
		}
		$json = elgg_get_annotations(array(
			'items' => $json->guid,
			'offset' => get_input('offset'),
			'limit' => 25,
		));
		break;
	case 'river':
		$json = $json->activity;
		break;
}

$items = array();
foreach($json as $item) {
	switch(get_input('items_type')) {
		case 'entity':
			switch($item->type) {
				case 'site':
					$items[] = new ElggSite($item);
					break;
				case 'user':
					$items[] = new ElggUser($item);
					break;
				case 'group':
					$items[] = new ElggGroup($item);
					break;
				case 'object':
					$items[] = new ElggObject($item);
					break;
			}
			break;
		case 'annotation': 
			$items = $json;
			break;
		case 'river':
			$items[] = new ElggRiverItem($item);
			break;
	}
}
header('Content-type: text/plain');
if(get_input('items_type') == 'river')
echo elgg_view('page/components/list', array("items" => $items, "list_class"=>'elgg-list-river elgg-river'));
else
echo elgg_view('page/components/list', array("items" => $items));

