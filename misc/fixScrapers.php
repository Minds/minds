<?php

require_once(dirname(dirname(__FILE__)) . '/engine/start.php');
elgg_set_ignore_access();

$users = elgg_get_entities(array('type'=>'user', 'limit'=>0));

foreach($users as $user){
	$scrapers = elgg_get_entities(array('type'=>'object', 'subtype'=>'scraper', 'owner_guid'=>$user->guid));
	foreach($scrapers as $scraper){
		$scraper->access_id = 2;
		$scraper->save();
	}
}
