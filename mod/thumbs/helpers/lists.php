<?php
/**
 * Lists of guids/entities of thumbed content
 */
 
namespace minds\plugin\thumbs\helpers;

use minds\core\data;
use minds\core\entities;
class lists{

	/*
	 * Return a list of guids that a user has thumbed
	 */
	static public function getUserThumbsGuids($user, $type = NULL, $params = array()){
		if($type)
			$guids = data\indexes::fetch("thumbs:up:user:$user->guid:$type", $params);
		else
			$guids = data\indexes::fetch("thumbs:up:user:$user->guid", $params);
		return array_keys($guids);
	}
	
	/**
	 * Return entitis that a user has thumbs
	 */
	static public function getUserThumbs($user, $type = NULL){
		
		
		$guids = self::getUserThumbsGuids($user, $type);
		
		if($guids)
			return entities::get(array('guids'=>$guids));
		
		return false;
	}
	
}
