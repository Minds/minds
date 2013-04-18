<?php
/* 
 * View for displaying archive titles
 * 
 */

$entities = $vars['entities'];


foreach($entities as $entity){
	
	
	if($entity->getSubtype() == 'kaltura_video'){
		$icon = elgg_view('output/img', array(
				'src' => kaltura_get_thumnail($entity->kaltura_video_id, 300, 185, 100),
				'title' => $entity->title,
				'alt' => $entity->title,
		));
		$title = $entity->title;
	} elseif($entity->getSubtype() == 'image') {
		$icon = elgg_view('output/img', array(
				'src' => $entity->getIconURL('large'),
				'title' => $entity->title,
				'alt' => $entity->title,
		));
		$title = $entity->getTitle();
	} elseif($entity->getSubtype() == 'file') {
		continue;
	}
	
	$owner = $entity->getOwnerEntity();
	
	echo '<div class="thumbnail-tile">';
	
		echo elgg_view('output/url', array(
				'text' => $icon,
				'href' => $entity->getURL(),
			));
	
		echo '<div class="hover"> <div class="inner">';	
			echo '<div class="title">' . elgg_view('output/url', array('href'=>$entity->getURL(), 'text' =>$title)) . '</div>';
			echo '<div class="owner">' . elgg_echo('archive:owner_tag') . elgg_view('output/url', array('href'=>$owner->getURL(), 'text'=> $owner->name)) . '</div>'; 
			echo elgg_view_menu('thumbs', array('entity'=>$entity));
		echo '</div></div>';
	
	echo '</div>';
}
