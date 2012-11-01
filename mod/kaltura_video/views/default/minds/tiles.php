<?php
/* 
 * View for displaying archive titles
 * 
 */

$entities = $vars['entities'];


foreach($entities as $entity){
	
	
	if($entity->getSubtype() == 'kaltura_video'){
		$icon = elgg_view('output/img', array(
				'src' => kaltura_get_thumnail($entity->kaltura_video_id, 250, 100, 100),
				'title' => $entity->title,
				'alt' => $entity->title,
		));
	} elseif($entity->getSubtype() == 'image') {
		$icon = elgg_view('output/img', array(
				'src' => $entity->getIconURL('large'),
				'title' => $entity->title,
				'alt' => $entity->title,
		));
	} elseif($entity->getSubtype() == 'file') {
		continue;
	}
	
	echo '<div class="thumbnail-tile">';
	
	echo elgg_view('output/url', array(
			'text' => $icon,
			'href' => $entity->getURL(),
		));
		
	echo elgg_view_menu('entity', array('entity'=>$entity));
	
	echo '</div>';
}
