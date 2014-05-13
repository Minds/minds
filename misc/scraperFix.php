<?php
require_once(dirname(dirname(__FILE__)) . '/engine/start.php');

while(true){
	elgg_set_ignore_access(true);
	
	$users = elgg_get_entities(array('type'=>'user', 'limit'=>50, 'offset'=>$offset, 'newest_first'=>false));
	$offset = end($users)->guid;
	foreach($users as $user){

		$scrapers = elgg_get_entities(array('subtype'=>'scraper', 'owner_guid'=>$user->guid, 'limit'=>0));
		foreach($scrapers as $scraper){
			$scraper->access_id = 2;
			$guid = 	$scraper->save();
			echo "$guid \n";
		}
	}
}
