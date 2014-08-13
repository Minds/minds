<?php
require_once(dirname(dirname(__FILE__)) . '/engine/start.php');

$user = new ElggUser('markandrewculp');


while(true){
	elgg_set_ignore_access(true);
	

		$scrapers = elgg_get_entities(array('subtype'=>'scraper',  'limit'=>400));
		foreach($scrapers as $scraper){
			$scraper->access_id = 2;
			$guid = 	$scraper->save();
			echo "$guid \n";
		}
exit;
}
