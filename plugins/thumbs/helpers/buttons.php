<?php
/**
 * Thumb button helper functions
 */
 
namespace minds\plugin\thumbs\helpers;

class buttons{

	/*
	 * Discover if the user has thumbed up the entity
	 */
	static public function hasThumbed($entity, $action = 'up'){
		$user_guids = $entity->{"thumbs:$action:user_guids"};
		if($user_guids && is_array($user_guids))
			if(in_array(elgg_get_logged_in_user_guid(), $user_guids))
				return true;
		
		return false;
	}
	
}
