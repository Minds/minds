<?php
 //Start elgg engine
  require_once(dirname(dirname(dirname(__FILE__))) . '/engine/start.php');

  $ElggUser=get_loggedin_user();
if (isset ($ElggUser)) {
	$username=$ElggUser->get("username");
	
	// get room guid dg subtipe videowhisper
	$sql = "SELECT guid, description FROM ".$CONFIG->dbprefix."objects_entity ".
							 "WHERE title = '".$_GET['room']."' AND LEFT(description,(4+".strlen($username).")) = '1:1:".$username."' ORDER BY guid DESC LIMIT 1;";		
	if ($row = get_data_row($sql)) {
		$guid = $row->guid;
		$nilai = explode(":", $row->description);
		
		$newdescription = "";
		for ($i = 0; $i <= 2; $i++) {
			if ($i == 1) 
				$newdescription .= "0:"; // set status as 0 ( logout )
			else
				$newdescription .= $nilai[$i].":";
		}
		
		$ztime = time();
		$newdescription .= $ztime;	// modify the last access time

		$result = update_data("UPDATE {$CONFIG->dbprefix}objects_entity 
		set description='$newdescription' 
		where guid=$guid ;");
	}
}
    $ver=explode('.', get_version(true));
  	if ($ver[1]>7) forward("videoconference/all");
  	else forward("pg/videoconference/all");

?>
<p>
  <?=$_GET['message']?>
</p>
