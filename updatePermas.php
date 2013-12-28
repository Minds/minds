<?php

require('engine/start.php');

$slice = new phpcassa\ColumnSlice("", "", 100, true);
$guids = $DB->cfs['entities_by_time']->get('user', $slice);		

foreach($guids as $guid => $ts){
	$user = get_entity($guid, 'user');
	if(!$user){
		echo "User $guid does not exits";
		db_remove('user', 'entities_by_time', array($guid));
	}
}
