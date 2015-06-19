<?php
/**
 * Album view
 */

$full_view = elgg_extract('full_view', $vars, false);
$album = elgg_extract('entity', $vars);
	
if($full_view ){

	$guids = $album->getChildrenGuids(get_input('limit',24), get_input('offset', ''));
	if(!$guids)
		$images = array();
	else 
		$images = elgg_get_entities(array('guids'=>$guids));
	echo elgg_view_entity_list($images, array('full_view'=>false, 'viewtype'=>'gallery', 'masonry'=>false, 'list_class'=>'minds-album', 'data-lightbox'=>$album->guid));
	
} else {
	elgg_load_js('popup');
	$owner = $album->getOwnerEntity();
	
	$body = elgg_view('output/url', array(
		'text' => $img,
		'href' => $album->getURL(),
		'encode_text' => false,
		'is_trusted' => true,
	));
	
	$menu = elgg_view_menu('entity', array(
        'entity' => $album, 
        'handler' => 'archive',
        'sort_by' => 'priority',
        'class' => 'elgg-menu-hz',
    ));
	
	$img = elgg_view('output/img', array('src'=>$album->getIconURL('large'), 'class'=>'rich-image'));
	$title = elgg_view('output/url', array('href'=>$album->getURL(), 'text'=>elgg_view_title(strip_tags($album->title))));
	
	$owner_link  = elgg_view('output/url', array('href'=>$owner->getURL(), 'text'=>$owner->name));	
	
	$subtitle = '<i>' . elgg_echo('by') . ' ' . $owner_link . ' ' . elgg_view_friendly_time($album->time_created) . '</i>';
	
	$content = $img . $body;
	echo $menu;
	$header = elgg_view_image_block(elgg_view_entity_icon($owner, 'small'), $title . $subtitle);
	
	echo elgg_view('output/url', array(
		'href'=> $album->getURL(), 
		'text'=> elgg_view('output/img', array('src'=>$album->getIconURL('large'))),
		'class' => 'image-thumbnail',
		'data-album-guid'=>$album->guid
	));
	echo $header;
	
	
}
