<?php

$notification = elgg_extract('entity', $vars);
$params = unserialize($notification->params);
$type = $params['type'] ? $params['type'] : 'entity';


	$object = get_entity($notification->object_guid,'object');
	if($object instanceof ElggEntity){
		$subtype = $object->getSubtype();
	} else {
		return false;
	}

	if($subtype == 'tidypics'){
			$object = $object->getContainerEntity();
			$object_title = ' Your images in ' . $object->title;
		}elseif($subtype == 'blog'){
				$object_title = 'Your blog post' ;
		}elseif($subtype == 'kaltura_video'){
				$object_title = 'Your media, ';
		}else{
			$object_title = $object->title;
		}
		$object_url = $object->getURL();
		
	$body .= elgg_view('output/url', array('href'=>$object_url, 'text'=> $object_title));
	$body .= ' has been Featured!';
	$body .= "<br/>";
	
	$body .= "<div class='notify_description'>" .  $description . "</div>";

$body .= "<span class='notify_time'>" . elgg_view_friendly_time($notification-> time_created) . "</span>";

echo $body;
