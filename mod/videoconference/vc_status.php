<?
/*
POST Variables:
u=Username
s=Session, usually same as username
r=Room
ct=session time (in milliseconds)
lt=last session time received from this script in (milliseconds)
*/

 //Start elgg engine
  require_once(dirname(dirname(dirname(__FILE__))) . '/engine/start.php');
	
$room=$_POST[r];
$session=$_POST[s];
$username=$_POST[u];

$currentTime=$_POST[ct];
$lastTime=$_POST[lt];

$maximumSessionTime=0; //900000ms=15 minutes

$disconnect=""; //anything else than "" will disconnect with that message

$ElggUser=get_loggedin_user();
if (isset($ElggUser))  $username=$ElggUser->get("username");
	
	// get room guid dg subtipe videowhisper
	$sql = "SELECT guid, description FROM ".$CONFIG->dbprefix."objects_entity ".
							 "WHERE title = '".$room."' AND LEFT(description,(4+".strlen($username).")) = '1:1:".$username."' ORDER BY guid DESC LIMIT 1;";		
//								echo $sql;
//								echo "<br />".$room;
	if ($row = get_data_row($sql)) {
		$guid = $row->guid;
		$nilai = explode(":", $row->description);
		
		$ztime = time();
		$newdescription = "";

    for ($i = 0; $i <= 2; $i++) {
			if ($i == 1) 
				$newdescription .= "1:"; // still online
			else
				$newdescription .= $nilai[$i].":";
		}
			$newdescription .= $ztime;	// modify the last access time.

		$result = update_data("UPDATE {$CONFIG->dbprefix}objects_entity 
		set description='$newdescription' 
		where guid=$guid ;");
	}
	
?>timeTotal=<?=$maximumSessionTime?>&timeUsed=<?=$currentTime?>&lastTime=<?=$currentTime?>&disconnect=<?=$disconnect?>&loadstatus=1
