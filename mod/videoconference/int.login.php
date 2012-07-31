<?php
  //Start elgg engine
  require_once(dirname(dirname(dirname(__FILE__))) . '/engine/start.php');

  //user info
  $ElggUser=get_loggedin_user();
  $userType=0;

   if (!(isset($ElggUser)) || !($ElggUser->guid > 0))
   {
      $loggedin=0;
      $message=urlencode("Register or login and try again to access this room!");
      if (isadminloggedin()) $userType=3;

   }
   else
   {
    $username=$ElggUser->get("username");
    //$username=$ElggUser->get("name");
    $loggedin=1;
  
    //room info
    $options = array();
    $options['metadata_name_value_pairs'] = array('room' => $room);
    $options['types'] = 'object';
    $options['subtypes'] = 'videoconference';
    $erooms = elgg_get_entities_from_metadata($options);
	
	  $userisowner = 0;
	  $userinlist = 0;
    $userinmoderator = 0;
	  $useringroup = 0;
	
    if (count($erooms)&&$erooms) 
    {
      $eroom = $erooms[0];
      if ($eroom->owner_guid == get_loggedin_userid()) {
			 $userType=3; //owner is admin
			 $userisowner = 1;
		  }
		$usernamex=$ElggUser->get("username");
		$emailx=$ElggUser->get("email");
		$userlistx = explode("^", $eroom->description);
		$userlist = explode(",", $userlistx[23]);
		$moderatorlist = explode(",", $userlistx[24]);
		if (trim($userlistx[23]) != '') {
			$found = 0;
			 foreach ($userlist as $key => $val) { 
				if ($usernamex == trim($val)) {
					$found = 1;
					$userinlist = 1;
				}
				if ($emailx == trim($val)) {
					$found = 1;
					$userinlist = 1;
				}
			 }
			 if ($found === 0) {
				$message=urlencode("Access to '$room' is limited to certain users / group!");
				$loggedin=0;
			 }
		  } else {
			 $userinlist = 1;
		  }
		
		  if (trim($userlistx[24]) != '') {
			 foreach ($moderatorlist as $key => $val) { 
				if ($usernamex == trim($val)) {
					$userinmoderator = 1;
				}
				if ($emailx == trim($val)) {
					$userinmoderator = 1;
				}
			 }
		 }	
    }
    else {
     $message=urlencode("Room '$room' not found!");
     $loggedin=0;
    }            
   }

?>
