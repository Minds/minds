<?php

$album = $vars['entity'];
$full_view = elgg_extract('full_view', $vars, false);

if(($full_view || get_input('items_type')) && get_input('type') != 'album'){
	
	$guids = $album->getChildrenGuids(get_input('limit',24), get_input('offset', ''));
	$images = elgg_get_entities(array('guids'=>$guids));
	echo elgg_view_entity_list($images, array('full_view'=>false, 'viewtype'=>'gallery', 'masonry'=>false, 'list_class'=>'minds-album', 'data-lightbox'=>$album->guid));

} else {
	echo elgg_view('export/entities', array('entity'=>$album));	
}
