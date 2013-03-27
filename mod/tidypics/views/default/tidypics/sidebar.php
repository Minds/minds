<?php
/**
 * Sidebar view
 */

$page = elgg_extract('page', $vars);
$image = elgg_extract('image', $vars);
$album = elgg_extract('album', $vars);
if ($album && $page == 'view') {
	
	/**
	 * Other albums from owner
	 */
	$owners_photos = elgg_get_entities(array('type'=>'object', 'subtype'=>'album', 'owner_guid'=>$image->owner_guid, 'limit'=>2));
	if (($key = array_search($image, $owners_photos)) !== false) {
	    unset($owners_photos[$key]);
	}
	if(count($owners_photos) > 0){
		$owners_photos = elgg_view_entity_list($owners_photos, array('full_view'=>false, 'sidebar'=>true));
		echo elgg_view_module('aside', elgg_echo('archive:morefromuser:title', array($image->getOwnerEntity()->name)), $owners_photos, array('class'=>'sidebar'));
	}
}

echo elgg_view('minds/ads', array('type'=>'content-side'));

$featured = minds_get_featured('album', 3);
$content = elgg_view_entity_list($featured, array('full_view'=>false, 'sidebar'=>true));

echo elgg_view_module('aside', elgg_echo('archive:featured:title'), $content, array('class'=>'sidebar'));