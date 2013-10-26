<?php

$guid = get_input('guid');

$entity = get_entity($guid, 'object');

if(!$entity){
	return false;
}

elgg_set_page_owner_guid($entity->getOwnerGUID());
$owner = elgg_get_page_owner_entity();

$menu = elgg_view_menu('entity', array(
		'entity' => $entity,
		'handler' => 'archive',
		'sort_by' => 'priority',
		'class' => 'elgg-menu-hz',
	));

$title = $entity->title;
$description = strip_tags($entity->description);

if($entity->getSubtype() == 'kaltura_video'){
		
	elgg_load_library('archive:kaltura');
	
	//set the tags
	$kaltura_server = elgg_get_plugin_setting('kaltura_server_url',  'archive');
	$partnerId = elgg_get_plugin_setting('partner_id', 'archive');
	
	$widgetUi = elgg_get_plugin_setting('custom_kdp', 'archive');
	
	$video_location = $kaltura_server . '/index.php/kwidget/wid/_'.$partnerId.'/uiconf_id/' . $widgetUi . '/entry_id/'. $entity->kaltura_video_id;
	$video_location_secure = str_replace('http://', 'https://', $video_location);	
	
	$thumbnail = kaltura_get_thumnail($entity->kaltura_video_id, 640, 360, 100);	
	
	minds_set_metatags('og:type', 'video.other');
	//minds_set_metatags('og:url',trim($videopost->getURL()));
	minds_set_metatags('og:image', $thumbnail);
	minds_set_metatags('og:title', $title);
	minds_set_metatags('og:description', $description);
	minds_set_metatags('og:video', $video_location);
	minds_set_metatags('og:video:secure_url',  $video_location_secure); 
	minds_set_metatags('og:video:width', '1280');
	minds_set_metatags('og:video:height', '720');
	minds_set_metatags('og:other', $video_location);
	 
	minds_set_metatags('twitter:card', 'player');
	minds_set_metatags('twitter:url', $entity->getURL());
	minds_set_metatags('twitter:title', $entity->title);
	minds_set_metatags('twitter:image', $thumbnail);
	minds_set_metatags('twitter:description', $description);
	minds_set_metatags('twitter:player', $video_location);
	minds_set_metatags('twitter:player:width', '1280');
	minds_set_metatags('twitter:player:height', '720');
	
} elseif($entity->getSubtype() == 'file'){
	
	minds_set_metatags('og:type', 'article');
	minds_set_metatags('og:url', $entity->getURL());
	minds_set_metatags('og:image', $entity->getIconURL('large'));
	minds_set_metatags('og:title', $title);
	minds_set_metatags('og:description', $description);
	
	 
	minds_set_metatags('twitter:card', 'summary');
	minds_set_metatags('twitter:url', $entity->getURL());
	minds_set_metatags('twitter:title', $title);
	minds_set_metatags('twitter:image', $entity->getIconURL());
	minds_set_metatags('twitter:description', $description);
	
} elseif($entity->getSubtype() == 'image'){
	
	minds_set_metatags('og:type', 'mindscom:photo');
	minds_set_metatags('og:title', $entity->getTitle());
	minds_set_metatags('og:description', $entity->description ? $photo->description : $entity->getUrl());
	minds_set_metatags('og:image',$entity->getIconURL('large'));
	minds_set_metatags('mindscom:photo',$entity->getIconURL('large'));
	minds_set_metatags('og:url',$entity->getUrl());
	 
	minds_set_metatags('twitter:card', 'photo');
	minds_set_metatags('twitter:url', $entity->getURL());
	minds_set_metatags('twitter:title', $entity->getTitle());
	minds_set_metatags('twitter:image', $entity->getIconURL('large'));
	minds_set_metatags('twitter:description', $entity->description ? $entity->description : $entity->getUrl());
	
}

elgg_push_breadcrumb(elgg_echo('archive:all'), 'archive/all');

$crumbs_title = $owner->name;
if (elgg_instanceof($owner, 'group')) {
	elgg_push_breadcrumb($crumbs_title, "archive/group/$owner->guid/all");
} else {
	elgg_push_breadcrumb($crumbs_title, "archive/$owner->username");
}

if($entity->getSubtype() == 'image'){
	//set the album
	$album = $entity->getContainerEntity('object');
	elgg_push_breadcrumb($album->title, $album->getURL());
}

if($entity->getSubtype() == 'album'){
	
	if ($entity->canEdit()) {
		elgg_register_menu_item('title', array(
			'name' => 'upload',
			'href' => 'archive/upload/album/' . $entity->getGUID(),
			'text' => elgg_echo('images:upload'),
			'link_class' => 'elgg-button elgg-button-action',
		));
	}

}

elgg_push_breadcrumb($title);

$content = elgg_view_entity($entity, array('full_view' => true));
$content .= elgg_view('minds/ads', array('type'=>'content-foot-user-1')); 
$content .= elgg_view_comments($entity);

$sidebar = elgg_view('archive/sidebar', array('guid'=>$guid));

$title_block = elgg_view_title($title, array('class' => 'elgg-heading-main'));

$trending_guids = analytics_retrieve(array('context'=>'archive','limit'=> $limit, 'offset'=>$offset));

$trending = elgg_list_entities(  array(  'guids' => $trending_guids,
                                        'full_view' => FALSE,
                                        'archive_view' => TRUE,
                                        'limit'=>$limit,
                                        'offset' => $offset
                                ));



$body = elgg_view_layout("content", array(	
					'filter'=> '', 
					'title' => $title,
					'content'=> $content,
					'menu' => $menu,
					'sidebar' => $sidebar,
					'footer' => $trending
				));

echo elgg_view_page($title,$body);

?>
