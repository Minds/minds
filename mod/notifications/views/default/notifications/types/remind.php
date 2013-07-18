<?php

$notification = elgg_extract('entity', $vars);
$params = unserialize($notification->params);
$type = $params['type'] ? $params['type'] : 'entity';
$actor = get_entity($notification -> from_guid);

if($type == 'entity')

{
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
			}elseif(subtype == 'blog'){
					$object_title = 'your blog';
			}elseif(subtype == 'remind'){
					$object_title = 'reminded' . $object->title;
			}else{
				$object_title = $object->title;
			}
				$object_url = $object->getURL();
		}
}

$body .= "<span class='notify_time'>" . elgg_view_friendly_time($notification-> time_created) . "</span>";

echo $body;