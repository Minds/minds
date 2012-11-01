<?php
/* 
 * View for displaying archive titles
 * 
 */

$entities = $vars['entities'];


foreach($entities as $entity){
	echo '<div class="thumbnail-tile">';
	
	$icon = elgg_view('output/img', array(
			'src' => kaltura_get_thumnail($entity->kaltura_video_id, 250, 100, 100),
			'title' => $ob->title,
			'alt' => $ob->title,
	));
	
	echo elgg_view('output/url', array(
			'text' => $icon,
			'href' => $entity->getURL(),
		));
		
	echo elgg_view_menu('entity', array('entity'=>$entity));
	
	echo '</div>';
}
