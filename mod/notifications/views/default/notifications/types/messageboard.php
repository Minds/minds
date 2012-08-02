<?php 
$user = elgg_get_logged_in_user_entity();

$options = array(	'types'=>'object',
					'subtypes'=>'notification',
					'owner_guid' => $user->getGUID()
				);

$notifications = elgg_get_entities($options);

if($notifications){
	$list = elgg_view_entity_list($notifications);
	$list .= "See more";
} else {
	$list = 'No notifications';
}

echo elgg_view_module('popup', null, $list, array(
														'id' => 'notification',
														'class' => 'notifications popup hidden',
													));
													
													
								