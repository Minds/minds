<?php
elgg_push_context('sidebar');

echo elgg_view('minds/ads', array('type'=>'content-side-single'));

/**
 * Other videos module
 */
$guid = elgg_extract('guid', $vars);
if($guid){
	$video = get_entity($guid, 'object');
	$owners_videos = elgg_get_entities(array('type'=>'object', 'subtypes'=>array('kaltura_video'), 'owner_guid'=>$video->owner_guid, 'limit'=>2));
	if (($key = array_search($video, $owners_videos)) !== false) {
	    unset($owners_videos[$key]);
	}
	if(count($owners_videos) > 0){
		$owners_videos = elgg_view_entity_list($owners_videos, array('full_view'=>false, 'sidebar'=>true));
		echo elgg_view_module('aside', elgg_echo('archive:morefromuser:title', array($video->getOwnerEntity()->name)), $owners_videos, array('class'=>'sidebar'));
	}
	
	echo elgg_view('minds/ads', array('type'=>'content-side-single-2'));
}
/** 
 * Featured videos
 */
$featured = minds_get_featured('kaltura_video', 3);
$content = elgg_view_entity_list($featured, array('full_view'=>false, 'sidebar'=>true));

echo elgg_view_module('aside', elgg_echo('archive:featured:title'), $content, array('class'=>'sidebar'));

elgg_pop_context();
