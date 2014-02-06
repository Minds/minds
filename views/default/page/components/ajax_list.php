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
			 foreach ($child as  $grandchild){
				$new_json = array_merge($new_json,$grandchild);
			}
		}
		$json = $new_json;
		break;
	case 'search':
		$json = (array) $json->result;
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
foreach($json as $key => $item) {
	switch(get_input('items_type')) {
		case 'entity':
			switch($item->type) {
				case 'site':
					$items[$key] = new ElggSite($item);
					break;
				case 'user':
					$items[$key] = new ElggUser($item);
					break;
				case 'group':
					$items[$key] = new ElggGroup($item);
					break;
				case 'object':
					switch($item->subtype){
						case 'album':
							$items[$key] = new TidypicsAlbum($item);
							break;
						case 'image':
							$items[$key] = new TidypicsImage($item);
							break;
						default:
							$items[$key] = new ElggObject($item);
					}
					break;
			}
			break;
		case 'annotation': 
			$items = $json;
			break;
		case 'river':
			$items[$key] = new MindsNewsItem($item);
			break;
		case 'search':
			$new_item = array();
			$new_item['_source'] = (array) $item->_source;
			$new_item['_type'] = $item->_type;
			$items[$key] = $new_item;
	}
}
header('Content-type: text/plain');
//hack to remove the first entity
array_shift($items);

//hack to preserve ordering of featured list... keys won't work
if($elgg_path == elgg_get_site_url() || $elgg_path == null){
	usort($items, 'featured_sort');
}

if(get_input('items_type') == 'river'){
	echo elgg_view('page/components/list', array("items" => $items, "list_class"=>'elgg-list-river elgg-river'));
}elseif(get_input('items_type') == 'search'){
	echo elgg_view('minds_search/services/services', array('data'=>$items));
} else {
	echo elgg_view('page/components/list', array("items" => $items));
}

