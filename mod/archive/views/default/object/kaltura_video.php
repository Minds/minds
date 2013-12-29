<?php

elgg_load_library('archive:kaltura');

$full = elgg_extract('full_view', $vars, FALSE);
$entity = elgg_extract('entity', $vars);

$owner = $entity->getOwnerEntity(true);

if($full){
	/**
	 * Check if the video is converted or not
	 */
	$mediaEntry = $entity->getEntry(); 
	if($mediaEntry->status != 2){
		echo '<div class="notconverted">';
			
			echo elgg_echo('kalturavideo:notconverted');
		
		echo '</div>';
		return true;
	}

	$widget = kaltura_create_generic_widget_html($entity->kaltura_video_id, 'l',$entity->monetized);
	$widgetm = kaltura_create_generic_widget_html($entity->kaltura_video_id , 'm',$entity->monetized);
	
	if(elgg_get_viewtype()=='mobile'){
		$widget = kaltura_create_generic_widget_html($entity->kaltura_video_id , 'mobile',$entity->monetized);
	}
	
	echo '<div class="archive-video-wrapper">' . $widget . '</div>'; 
	
	echo '<div class="archive-description">' . $entity->description . '</div>';

	echo '<div class="archive-footer">';	
	

	echo elgg_view('minds/license', array('license'=>$entity->license)); 
	
	 echo '<div class="archive-plays">' . $entity->getPlayCount() . ' plays</div>';

	echo elgg_view('output/url', array(	'href'=>'/action/archive/download?guid='.$entity->guid,
												'text'=> elgg_echo('minds:archive:download'),
												'is_action' => true,
												'class'=> 'elgg-button elgg-button-action archive-button archive-button-right'
																		
										));
	if(elgg_is_admin_logged_in()){
		echo elgg_view('output/url', array(	'href'=>'/action/archive/feature?guid='.$entity->guid,
				'text'=> $entity->featured == true ? elgg_echo('archive:featured:un-action') : elgg_echo('archive:featured:action'),
				'is_action' => true,
				'class'=> 'elgg-button elgg-button-action archive-button archive-button-right'
			));
											
	/*	echo elgg_view('output/url', array(	'href'=>'/action/archive/monetize?guid='.$entity->guid,
			'text'=> $entity->monetized == true ? elgg_echo('archive:monetized:un-action') : elgg_echo('archive:monetized:action'),
			'is_action' => true,
			'class'=> 'elgg-button elgg-button-action archive-button archive-button-right'
		));*/
	}
	
	echo '</div>';

	if($entity->access_id == 2){
		echo elgg_view('minds_social/social_footer');
	}
	
		
}  elseif(elgg_get_context()=='sidebar') {
	?>
	<div class="kalturavideoitem" id="kaltura_video_<?php echo $entity->kaltura_video_id; ?>">

	<div class="left">
		<?php 
			elgg_pop_context(); if(elgg_get_context()=='news'){ $width=140;$height=79;} else {$width=215;$height=121;} elgg_push_context('sidebar');?>
		<p><a href="<?php echo $vars['entity']->getURL(); ?>" class="play"><img src="<?php echo kaltura_get_thumnail($entity->kaltura_video_id, $width, $height, 100, $entity->thumbnail_sec); ?>" alt="<?php echo htmlspecialchars($vars['entity']->title); ?>" title="<?php echo htmlspecialchars($vars['entity']->title); ?>" /></a></p>
	</div>

	<h3><a href="<?php echo $vars['entity']->getURL(); ?>"><?php echo $vars['entity']->title; ?></a></h3>
	
	
	<p class="stamp">
		<?php echo elgg_echo('by'); ?> <a href="<?php echo $CONFIG->wwwroot.'archive/owner/'.$owner->username; ?>" title="<?php echo htmlspecialchars(elgg_echo("kalturavideo:user:showallvideos")); ?>"><?php echo $owner->name; ?></a>
		<?php echo elgg_view_friendly_time($entity->time_created);?>
	</p>
	</div>
<?php } else {
		
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
			'text' =>  '<span></span><img src=\'' . kaltura_get_thumnail($entity->kaltura_video_id, 515, 290, 100, $entity->thumbnail_sec) . '\'/>',
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
