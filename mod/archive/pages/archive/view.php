<?php

$guid = get_input('guid');

$entity = get_entity($guid);

if(!$entity){
	forward();
	return false;
}

elgg_set_page_owner_guid($entity->getOwnerGUID());
$owner = elgg_get_page_owner_entity();
));

$title = strip_tags($entity->title);
$description = strip_tags($entity->description);
$sidebar_comments = true;
$content = false;

set_input('description', $description);
set_input('keywords', $entity->tags);
$trending = true;
switch($entity->subtype){
	case 'video':
    case 'audio':		
		if(!elgg_is_xhr())
			$sidebar_comments = false;
		
		$video_location = elgg_get_site_url().'/archive/embed';
		$video_location_secure = str_replace('http://', 'https://', $video_location);
		$thumbnail = $entity->getIconURL();
	
		minds\plugin\social\start::setMetatags('og:type', 'video');
		minds\plugin\social\start::setMetatags('og:url', $entity->getPermaURL());
		minds\plugin\social\start::setMetatags('og:image', $thumbnail);
		minds\plugin\social\start::setMetatags('og:title', $title);
		minds\plugin\social\start::setMetatags('og:description', $description);
		minds\plugin\social\start::setMetatags('og:video:url', $entity->getSourceUrl('360.mp4'));
		minds\plugin\social\start::setMetatags('og:video:secure_url',   $entity->getSourceUrl('360.mp4')); 
		minds\plugin\social\start::setMetatags('og:video:type', 'video/mp4');
		minds\plugin\social\start::setMetatags('og:video:width', '1280');
		minds\plugin\social\start::setMetatags('og:video:height', '720');
	 
		minds\plugin\social\start::setMetatags('twitter:card', 'player');
		minds\plugin\social\start::setMetatags('twitter:url', $entity->getURL());
		minds\plugin\social\start::setMetatags('twitter:title', $entity->title);
		minds\plugin\social\start::setMetatags('twitter:image', $thumbnail);
		minds\plugin\social\start::setMetatags('twitter:description', $description);
		minds\plugin\social\start::setMetatags('twitter:player', $video_location);
		minds\plugin\social\start::setMetatags('twitter:player:width', '1280');
		minds\plugin\social\start::setMetatags('twitter:player:height', '720');
		
		$body = "<div class=\"cinemr-screen\">";
		$body .= elgg_view_entity($entity, array('full_view' => true, 'video_only'=> true));
		$body .= "</div>";
		
		$title_block = elgg_view_title(strip_tags($title), array('class' => 'elgg-heading-main'));
		
		$content .= '<div class="archive-description">'.$entity->description.'</div>';
		$content .= elgg_view('minds/license', array('license'=>$entity->license));
		$content .= elgg_view('minds_social/social_footer');
		$content .= elgg_view_comments($entity) . "</div>";

		$body .= elgg_view_layout("content", array(	
			'menu' => $menu,
			'title' => $title_block,
			'content'=> $content,
		));
		
		echo elgg_view_page($title,$body, 'default', array('class'=>'cinemr-screen-body'));
		exit;
		break;
	case 'image':
		minds\plugin\social\start::setMetatags('og:type', 'mindscom:photo');
		minds\plugin\social\start::setMetatags('og:title', $entity->title);
		minds\plugin\social\start::setMetatags('og:description', $entity->description ? $photo->description : $entity->getUrl());
		minds\plugin\social\start::setMetatags('og:image',$entity->getIconURL('large'));
		minds\plugin\social\start::setMetatags('mindscom:photo',$entity->getIconURL('large'));
		minds\plugin\social\start::setMetatags('og:url',$entity->getPermaUrl());
		 
		minds\plugin\social\start::setMetatags('twitter:card', 'photo');
		minds\plugin\social\start::setMetatags('twitter:url', $entity->getURL());
		minds\plugin\social\start::setMetatags('twitter:title', $entity->title);
		minds\plugin\social\start::setMetatags('twitter:image', $entity->getIconURL('large'));
		minds\plugin\social\start::setMetatags('twitter:description', $entity->description ? $entity->description : $entity->getUrl());

        if($entity->getContainerEntity())    
		    $subtitle = elgg_view('output/url', array('href'=>$entity->getContainerEntity()->getURL(), 'text'=>'Back to \''. $entity->getContainerEntity()->title .'\''));
        else
            $subtitle = "";
		/**
		 * If loaded via our photo viewer, then don't show a standard page
		 */
		if(get_input('view') == 'spotlight' || elgg_is_xhr()){
			elgg_set_viewtype('spotlight');
			$trending = false;
		}
		$sidebar = elgg_view('output/longtext', array('value'=>$entity->description, 'style'=>'padding:12px;font-size:11px;overflow:scroll; max-height:100px;'));
		break;
	case 'album':
		minds\plugin\social\start::setMetatags('og:type', 'mindscom:photo');
		minds\plugin\social\start::setMetatags('og:url', $entity->getPermaURL());
		minds\plugin\social\start::setMetatags('og:image', $entity->getIconURL('large'));
		minds\plugin\social\start::setMetatags('og:title', $title);
		minds\plugin\social\start::setMetatags('og:description', $description);
		$trending = false;
		
		/**
		 * Perhaps slightly hacky, but if this is an asynchronous call then we should show a list of images
		 * @todo cleanup someway. Although images are the exception to the rule? (@MEH)
		 */
		 if(get_input('ajax') || elgg_get_viewtype() == 'json'){
		 	echo elgg_view_page('', elgg_list_entities(array('container_guid'=>$entity->guid, 'limit'=>get_input('limit', 24), 'offset'=>get_input('offset',''))));
			return true;
		 }
		
		break;
	case 'file':
		minds\plugin\social\start::setMetatags('og:type', 'article');
		minds\plugin\social\start::setMetatags('og:url', $entity->getPermaURL());
		minds\plugin\social\start::setMetatags('og:image', $entity->getIconURL('large'));
		minds\plugin\social\start::setMetatags('og:title', $title);
		minds\plugin\social\start::setMetatags('og:description', $description);
		
		 
		minds\plugin\social\start::setMetatags('twitter:card', 'summary');
		minds\plugin\social\start::setMetatags('twitter:url', $entity->getURL());
		minds\plugin\social\start::setMetatags('twitter:title', $title);
		minds\plugin\social\start::setMetatags('twitter:image', $entity->getIconURL());
		minds\plugin\social\start::setMetatags('twitter:description', $description);
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

if(!$content)
	$content = elgg_view_entity($entity, array('full_view' => true));

//$content .=  elgg_view('minds/ads', array('type'=>'content-below-banner'));
//$content .= elgg_view_comments($entity);

//$sidebar = elgg_view('archive/sidebar', array('guid'=>$guid));

$title_block = elgg_view_title($title, array('class' => 'elgg-heading-main'));

if(elgg_is_active_plugin('analytics') && $trending && false){

	$trending_guids = analytics_retrieve(array('context'=>'archive','limit'=> get_input('limit', 2), 'offset'=>get_input('offset', '')));

	$trending = elgg_list_entities(array(
					'guids' => $trending_guids,
 					'full_view' => FALSE,
                    'archive_view' => TRUE,
                    'limit'=>$limit,
                    'offset' => $offset
	));

} else {
	$trending = '';
}

if($sidebar_comments)
	$sidebar .= elgg_view_comments($entity);

$menu = elgg_view_menu('entity', array(
        'entity' => $entity,
        'handler' => 'archive',
        'sort_by' => 'priority',
        'class' => 'elgg-menu-hz',
    ));

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

