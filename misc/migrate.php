<?php

require_once(dirname(dirname(__FILE__)) . "/engine/start.php");



$json = file_get_contents(dirname(__FILE__) . '/json.file');

$group = 365903183068794880;
$array = json_decode($json, true);
foreach($array['activity'][""] as $raw){

	$activity = new minds\entities\activity($raw);
	$activity->container_guid = $group;
	$activity->access_id = $group;
	$activity->indexes = array("activity:container:$group");
	var_dump($activity);
	$activity->save();

}
	

//login(new Minds\entities\user('mark'));

