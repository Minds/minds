<?php

$guid = get_input('guid');

$entity = get_entity($guid);

if(!$entity){
	forward();
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

set_input('description', $description);
set_input('keywords', $entity->tags);
$trending = true;
switch($entity->subtype){
	case 'video':
		$video_location = elgg_get_site_url().'/archive/embed';
		$video_location_secure = str_replace('http://', 'https://', $video_location);
		$thumbnail = $entity->getIconURL();
	
		minds_set_metatags('og:type', 'article');
		minds_set_metatags('og:url', $entity->getPermaURL());
		minds_set_metatags('og:image', $thumbnail);
		minds_set_metatags('og:title', $title);
		minds_set_metatags('og:description', $description);
		//minds_set_metatags('og:video:url', $video_location);
		//minds_set_metatags('og:video:secure_url',  $video_location_secure); 
		//minds_set_metatags('og:video:width', '1280');
		//minds_set_metatags('og:video:height', '720');
	 
		minds_set_metatags('twitter:card', 'player');
		minds_set_metatags('twitter:url', $entity->getURL());
		minds_set_metatags('twitter:title', $entity->title);
		minds_set_metatags('twitter:image', $thumbnail);
		minds_set_metatags('twitter:description', $description);
		minds_set_metatags('twitter:player', $video_location);
		minds_set_metatags('twitter:player:width', '1280');
		minds_set_metatags('twitter:player:height', '720');
		break;
	case 'image':
		minds_set_metatags('og:type', 'mindscom:photo');
		minds_set_metatags('og:title', $entity->title);
		minds_set_metatags('og:description', $entity->description ? $photo->description : $entity->getUrl());
		minds_set_metatags('og:image',$entity->getIconURL('large'));
		minds_set_metatags('mindscom:photo',$entity->getIconURL('large'));
		minds_set_metatags('og:url',$entity->getPermaUrl());
		 
		minds_set_metatags('twitter:card', 'photo');
		minds_set_metatags('twitter:url', $entity->getURL());
		minds_set_metatags('twitter:title', $entity->title);
		minds_set_metatags('twitter:image', $entity->getIconURL('large'));
		minds_set_metatags('twitter:description', $entity->description ? $entity->description : $entity->getUrl());
		
		$subtitle = elgg_view('output/url', array('href'=>$entity->getContainerEntity()->getURL(), 'text'=>'Back to \''. $entity->getContainerEntity()->title .'\''));
		break;
	case 'album':
		$trending = false;
		break;
	case 'file':
		minds_set_metatags('og:type', 'article');
		minds_set_metatags('og:url', $entity->getPermaURL());
		minds_set_metatags('og:image', $entity->getIconURL('large'));
		minds_set_metatags('og:title', $title);
		minds_set_metatags('og:description', $description);
		
		 
		minds_set_metatags('twitter:card', 'summary');
		minds_set_metatags('twitter:url', $entity->getURL());
		minds_set_metatags('twitter:title', $title);
		minds_set_metatags('twitter:image', $entity->getIconURL());
		minds_set_metatags('twitter:description', $description);
		break;
}		
	
elgg_push_breadcrumb(elgg_echo('archive:all'), 'archive/all');

/*$crumbs_title = $owner->name;
if (elgg_instanceof($owner, 'group')) {
	elgg_push_breadcrumb($crumbs_title, "archive/group/$owner->guid/all");
} else {
	elgg_push_breadcrumb($crumbs_title, "archive/$owner->username");
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

}*/

elgg_push_breadcrumb($title);

/**
 * If loaded via our photo viewer, then don't show a standard page
 */
if(get_input('view') == 'spotlight' && elgg_is_xhr()){
	elgg_set_viewtype('spotlight');
	$trending = false;
}

$content = elgg_view_entity($entity, array('full_view' => true));

//$content .=  elgg_view('minds/ads', array('type'=>'content-below-banner'));
//$content .= elgg_view_comments($entity);

//$sidebar = elgg_view('archive/sidebar', array('guid'=>$guid));

$title_block = elgg_view_title($title, array('class' => 'elgg-heading-main'));

if(elgg_is_active_plugin('analytics') && $trending){

	$trending_guids = analytics_retrieve(array('context'=>'archive','limit'=> get_input('limit', 2), 'offset'=>get_input('offset', '')));

	$trending = elgg_list_entities(array(
					'guids' => $trending_guids,
 					'full_view' => FALSE,
                    'archive_view' => TRUE,
                    'limit'=>$limit,
                    'offset' => $offset
	));

}

$sidebar = elgg_view_comments($entity);

$body = elgg_view_layout("content", array(	
					'class' => 'archive',
					'filter'=> '', 
					'title' => $title,
					'subtitle'=> $subtitle,
					'content'=> $content,
					'menu' => $menu,
					'sidebar' => $sidebar,
					'footer' => $trending
				));

echo elgg_view_page($title,$body);
