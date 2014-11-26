<?php
/**
 * Calculates and updates the total messages
 */

namespace minds\plugin\gatherings;

use minds\core;

class counter{

	static public $unread;

	static public function increment($user = NULL){

		if(!$user)
                        $user = elgg_get_logged_in_user_entity();

		$indexes = new core\data\indexes();
		return $indexes->set("object:gathering:conversations:unread", array($user->guid=>1));
	}

	static public function get($user = NULL){

		if(!$user)
			$user = elgg_get_logged_in_user_entity();

		if(self::$unread)
			return self::$unread;

		$result = core\data\indexes::fetch("object:gathering:conversations:unread", array('offset'=>$user->guid, 'limit'=>1));
		if($result){
			self::$unread = reset($result);
			return reset($result);
		}

		return 0;

	}
	
	static public function clear($user = NULL){
		
		if(!$user)
                        $user = elgg_get_logged_in_user_entity();

		$indexes = new core\data\indexes();
		$indexes->set("object:gathering:conversations:unread", array($user->guid=>0));

		self::$unread = 0;
		
	}

}
