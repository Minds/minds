<?php

require('engine/start.php');

$slice = new phpcassa\ColumnSlice("", "", 50, true);
$guids = $DB->cfs['entities_by_time']->get('user', $slice);		

foreach($guids as $guid => $ts){
	$user = get_entity($guid, 'user');

	$bot = 0;

	//Check for whitespaces in name
	if(strlen(trim($user->name)) != strlen($user->name)) {
		$bot++;
	}

	//check if username is the same as diplay name (double points)
	if($user->name == $user->username){
		$bot++;
		$bot++;
	}

	//Check for number in name
	if (preg_match('#[0-9]#', $user->name)){
		$bot++;
	}

	//check if outlook.com
	$parts = explode('@',$user->email);
	if($parts[1] == 'outlook.com'){
		$bot++;
	}

	echo "USER $user->username has $bot points \n";

	if($bot >= 3){
//		$user->delete();
	}
}
