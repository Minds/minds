<?php
/**
 * Comment notification view
 */
$notification = elgg_extract('entity', $vars);

$from =  Minds\Core\Entities::build(new Minds\Entities\entity($notification->from_guid));

if(!$from){
	return false;
}
	
$entity =  Minds\Entities\Factory::build($notification->object_guid);
if ($entity) {
	switch($entity->type){
		case 'object':
			$text = $entity->title;	
			break;
		case 'activity':
			if($entity->owner_guid == elgg_get_logged_in_user_guid()){
				$text = 'your activity';
			} else {
				$text = $entity->getOwnerEntity()->name . '\'s activity';
			}
			break;
	}

	$href = $entity->getURL();
} 

$description = htmlspecialchars($notification->description, ENQ_QUOTES);
if (strlen($description) > 60) {
	$description = substr($notification -> description, 0, 75) . '...';
}

$body .= elgg_view('output/url', array('href' => $from->getURL(), 'text' => $from->name ?: $from->username));
$body .= ' commented on ';
$body .= elgg_view('output/url', array('href' => $href, 'text' => $text ?: 'your post'));
$body .= "<br/>";

$body .= "<div class='notify_description'>" . $description . "</div>";

$body .= "<span class='notify_time'>" . elgg_view_friendly_time($notification-> time_created) . "</span>";

echo $body;
