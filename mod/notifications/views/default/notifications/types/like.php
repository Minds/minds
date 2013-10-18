<?php

$notification = elgg_extract('entity', $vars);
$params = unserialize($notification->params);
$type = $params['type'] ? $params['type'] : 'entity';

$actor = get_entity($notification->from_guid, 'user');

try{
	if($type == 'entity'){
		$object = get_entity($notification->object_guid, 'object');
		if($object instanceof ElggEntity){
			$subtype = $object->getSubtype();
			if($subtype == 'thewire'){
				$object_title = 'your post';
			}elseif($subtype == 'wallpost'){
					$object_title = 'your thought';
			} elseif($subtype == 'hjannotation') { 
				$object = get_entity($object->parent_guid);
				$object_title = ' your comment';
				return true;//do not likes on old comments as some are messed up
			}elseif($subtype == 'image'){
				$object_title = $object->getTitle();
			}elseif($subtype == 'tidypics_batch'){
				$object = $object->getContainerEntity();
				$object_title = ' your images in ' . $object->title;
			}else{
				$object_title = $object->title;
			}
			$object_url = $object->getURL();
		}
	}elseif($type=='river'){
		$object_title = 'your post';
		$object_url = elgg_get_site_url() . 'news/single?id=' . $notification -> object_guid;
	}elseif($type=='comment'){
		$comment_type = $params['comment_type'];
		if($comment_type == 'river'){
			$object_url = elgg_get_site_url() . 'news/single?id=' . $notification -> object_guid;	
		} else {
			$object = get_entity($notification->object_guid);
			if($object instanceof ElggObject)
			$object_url = $object->getURL();
		}
		$object_title = 'your comment';
	}
	
	$description = $notification->description;
	if (strlen($description) > 60){
	  $description = substr($notification->description,0,75) . '...' ;
	} 
	
	$body .= elgg_view('output/url', array('href'=>$actor->getURL(), 'text'=>$actor->name));
	$body .= ' has voted up ';
	$body .= elgg_view('output/url', array('href'=>$object_url, 'text'=> $object_title));
	
	$body .= "<br/>";
	
	$body .= "<div class='notify_description'>" .  $description . "</div>";
	
	$body .= "<span class='notify_time'>" . elgg_view_friendly_time($notification->time_created) . "</span>";
	
	echo $body;
	
} catch(Exception $e){
	return false;
}
