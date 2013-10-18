<?php

$elgg_path = str_replace(elgg_get_site_url(), '', $vars['path']);
$path = explode('/', $elgg_path);

if($elgg_path == elgg_get_site_url() || $elgg_path == null){

elgg_set_viewtype('json');

if(!include_once(elgg_get_plugins_path() . 'minds/pages/index.php')){
		return false;
}

elgg_set_viewtype('default');
$out = ob_get_contents();
ob_end_clean();

} else {

ob_start();
elgg_set_viewtype('json');
page_handler(array_shift($path), implode('/', $path));
elgg_set_viewtype('default');
$out = ob_get_contents();
ob_end_clean();

}

$json = json_decode($out);
if(!$json){
	return;
}
switch(get_input('items_type')){
	case 'entity':
		$new_json = array();
		foreach ($json as $child){
			 foreach ($child as $grandchild){
				$new_json = array_merge($new_json,$grandchild);
			}
		}
		
		// Removing duplicates
		// This will be unnecessary when #4504 fixed.
		$buggy = $new_json;
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
					switch($item->subtype){
						case 'album':
							$items[] = new TidypicsAlbum($item);
							break;
						case 'image':
							$items[] = new TidypicsImage($item);
							break;
						default:
							$items[] = new ElggObject($item);
					}
					break;
			}
			break;
		case 'annotation': 
			$items = $json;
			break;
		case 'river':
			$items[] = new MindsNewsItem($item);
			break;
	}
}
header('Content-type: text/plain');
//hack to remove the first entity
array_shift($items);
if(get_input('items_type') == 'river')
echo elgg_view('page/components/list', array("items" => $items, "list_class"=>'elgg-list-river elgg-river'));
else
echo elgg_view('page/components/list', array("items" => $items));

