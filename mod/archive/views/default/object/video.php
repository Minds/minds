<?php

$full = elgg_extract('full_view', $vars, FALSE);
$entity = elgg_extract('entity', $vars);

$owner = $entity->getOwnerEntity(true);

if($full){
	
		
} else {
		
	$menu = elgg_view_menu('entity', array(
		'entity' => $entity,
		'handler' => 'archive',
		'sort_by' => 'priority',
		'class' => 'elgg-menu-hz',
	));
	
	$owner = $entity->getOwnerEntity(true); 
	$owner_link = elgg_view('output/url', array(
		'text' => $owner->name,
		'href' => $owner->getURL()
	));
	
	$title = elgg_view('output/url', array(
		'text' => $entity->title,
		'href' => $entity->getURL(),
	));
	
	$description = $entity->description ? minds_filter(substr(strip_tags($entity->description), 0, 125) . '...') : '';
	
	$subtitle = '<i>'.
		elgg_echo('by') . ' ' . $owner_link . ' ' .
		elgg_view_friendly_time($entity->time_created) . '</i>'; 
		//elgg_echo("kalturavideo:label:length") . ' <strong class="kaltura_video_length">'.$entity->kaltura_video_length.'</strong>';
	
	
	'<b class="kaltura_video_created">'. elgg_view_friendly_time($entity->time_created).'</b> by ' . $owner_link;
	
	$params = array(
		'entity' => $album,
		'title' => $title,
		'metadata' => $menu,
		'subtitle' => $subtitle,
		'content'=>$description,
		'tags' => elgg_view('output/tags', array('tags' => $entity->tags)),
	);
	$params = $params + $vars;
	$summary = elgg_view('object/elements/summary', $params);
	
	$image = elgg_view('output/url', array(
			'href' => $entity->getURL(),
			'class' => 'uiVideoInline archive entity',
			'text' =>  '<span></span><img src=\'' . kaltura_get_thumnail($entity->kaltura_video_id, 515, 290, 60, $entity->thumbnail_sec) . '\'/>',
			'title' => $entity->title,
		));
	
	$title = elgg_view('output/url', array('href'=>$entity->getURL(), 'text'=>elgg_view_title($entity->title)));

	$extras = '<span class="extras"> <p class="time">'. $date . '</p>' . $menu .'</span>';
	
	$body = '<span class="info">' . $title . $extras . '<span>';
	
	$content = $image . $body;
	echo $menu;
	$header = elgg_view_image_block(elgg_view_entity_icon($owner, 'small'), $title . $subtitle);
	echo $header;
	echo $image;
}
