<?php
  //Start elgg engine
  require_once(dirname(dirname(dirname(__FILE__))) . '/engine/start.php');

// get room description for non loggedin user
$sqldesc = "SELECT ".$CONFIG->dbprefix."objects_entity.description, ".$CONFIG->dbprefix."objects_entity.guid ".
  "FROM    (   ".$CONFIG->dbprefix."entities ".$CONFIG->dbprefix."entities ".
           "INNER JOIN 
              ".$CONFIG->dbprefix."entity_subtypes ".$CONFIG->dbprefix."entity_subtypes ".
           "ON (".$CONFIG->dbprefix."entities.subtype = ".$CONFIG->dbprefix."entity_subtypes.id)) ".
       "INNER JOIN
          ".$CONFIG->dbprefix."objects_entity ".$CONFIG->dbprefix."objects_entity ".
       "ON (".$CONFIG->dbprefix."entities.guid = ".$CONFIG->dbprefix."objects_entity.guid) ".
 "WHERE ".$CONFIG->dbprefix."objects_entity.title = '".$room."'
       AND ".$CONFIG->dbprefix."entity_subtypes.subtype = 'livestreaming' ORDER BY guid DESC LIMIT 1;";
//echo $sqldesc;
	if ($eroom = get_data_row($sqldesc)) {
		$nilai = explode("^", $eroom->description);
    $visitor = $nilai[29];
    if ($visitor) {
      $userType=0;
	    $username="VW".base_convert((time()-1224350000).rand(0,10),10,36);
	    $loggedin=1;
    } else {
      $loggedin=0;
      $message="Register or login and try again to access this room!";
    }
	} else {
     $message=urlencode("Room '$room' not found!");
     $loggedin=0;
  }

?>
