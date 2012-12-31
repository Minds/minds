<?php

function minds_search_register_service($name, $classname){
	global $SEARCH_SERVICES;
	
	$service =  new stdClass();
	$service->name = $name;
	$service->classname = $classname;
	
	$SEARCH_SERVICES[] = $service;
	return true;
}
function minds_search_return_services(){
	
	global $SEARCH_SERVICES;
	foreach($SEARCH_SERVICES as $service){
			$return[] = $service;
	}
	return $return;
}
function minds_search_sidebar_menu(){
	$menu_item = new ElggMenuItem('test', elgg_echo('test'), $_SERVER['REQUEST_URI'].'&services[]=youtube');
	//elgg_register_menu_item('page', $menu_item);
}
function minds_search_index($service){
	if($service == 'flickr'){
		$flickr = new MindsSearchFlickr();
		$flickr->index();
	}elseif($service =='pixabay'){
		/*
		 * Drop all pixabay as they do not give us ids!!
		 */
		$pixabay = new MindsSearchPixabay();
		$pixabay->index();
	}elseif($service == 'freesound'){
		$freesound = new MindsSearchFreesound();
		$freesound->index();
	}elseif($service =='openclipart'){
		$openclipart = new MindsSearchOpenClipart();
		$openclipart->index();
	}elseif($service =='ccmixter'){
		$ccmixter = new MindsSearchCCMixter();
		$ccmixter->index();
	}elseif($service=='soundcloud'){
		$soundcloud = new MindsSearchSoundcloud();
		$soundcloud->index();
	}elseif($service=='youtube'){
		$youtube = new MindsSearchYoutube();
		$youtube->index();
	}elseif($service =='archivedotorg'){
		$archivedotorg = new MindsSearchArchivedotOrg();
		$archivedotorg->index();
	}
	echo $service;
}

minds_search_register_service('flickr', 'MindsSearchFlickr');
minds_search_register_service('openclipart', 'MindsSearchOpenClipart');
minds_search_register_service('pixabay', 'MindsSearchPixabay');
minds_search_register_service('youtube', 'MindsSearchYoutube');
minds_search_register_service('freesound', 'MindsSearchFreesound');
minds_search_register_service('soundcloud', 'MindsSearchSoundcloud');
minds_search_register_service('ccmixter', 'MindsSearchCCMixter');
minds_search_register_service('archivedotorg', 'MindsSearchArchivedotOrg');