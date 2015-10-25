<?php

$notification = elgg_extract('entity', $vars);
$params = unserialize($notification->params);
$type = $params['type'] ? $params['type'] : 'entity';

$actor = get_entity($notification -> from_guid);


	$object = get_entity($notification->object_guid);
		if($object instanceof ElggEntity){
			$subtype = $object->getSubtype();
			if($subtype == 'thewire'){
				$object_title = 'your post';
			}elseif($subtype == 'wallpost'){
					$object_title = 'your thought';
			}elseif($subtype == 'tidypics'){
					$object = $object->getContainerEntity();
					$object_title = ' your images in ' . $object->title;
			}elseif($subtype == 'blog'){
					$object_title = 'your blog post' ;
			}else{
				$object_title = $object->title;
			}
				$object_url = $object->getURL();
		}
$body .= elgg_view('output/url', array('href'=>$actor->getURL(), 'text'=>$actor->name));
	$body .= ' has reminded ';
	$body .= elgg_view('output/url', array('href'=>$object_url, 'text'=> $object_title ?: "your post"));
	
	$body .= "<br/>";
	
	$body .= "<div class='notify_description'>" .  $description . "</div>";

$body .= "<span class='notify_time'>" . elgg_view_friendly_time($notification-> time_created) . "</span>";

echo $body;
