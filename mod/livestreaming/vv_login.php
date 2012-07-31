<?php

$room=$_COOKIE['room_name'];

//record current room in cookie to show in rooms list
if ($room) setcookie("userroom",urlencode($room),time()+86400);

require_once(dirname(dirname(dirname(__FILE__))) . '/engine/start.php');
$ElggUser=get_loggedin_user();
if (isset($ElggUser)) include("int.login.php");
  else include("int.login2.php");

if (!$room) {
		$loggedin=0;
		$message="You need a cookie enabled browser!";
}

// cek userloggedin in group / friends of this room
if (isset($eroom)) {
  if (isset($ElggUser)) {
  $sqlgroup1 = "SELECT user_guid FROM ".$CONFIG->dbprefix."access_collection_membership ".
						 "WHERE user_guid = '".get_loggedin_userid()."' AND access_collection_id = '".$eroom->access_id."';";		
  if ($rowgroup = get_data_row($sqlgroup1)) {
	 $useringroup = 1;
  }
}

    $rtmp_server = datalist_get('lstr_rtmp_server');
    $rtmp_amf = datalist_get('lstr_rtmp_amf');
    $rtmfp_server = datalist_get('lstr_rtmfp_server');
    $p2pGroup = datalist_get('lstr_p2pGroup');
    $bufferFull = datalist_get('lstr_bufferFull2');
    $bufferLive = datalist_get('lstr_bufferLive2');
    $tokenKey = datalist_get('lstr_tokenKey');
    $serverProxy = datalist_get('lstr_serverProxy');
    $statusInterval = datalist_get('lstr_statusInterval');

    	function get_perm($userisowner, $userinmoderator, $useringroup, $permtype) {
      global $ElggUser;
			$returnperm = 0;
			if ($permtype == 0) {	// all
				$returnperm = 1;
			}
			if ($permtype == 1) {	// groups
				if ($useringroup == 1) $returnperm = 1;
			}
			if ($permtype == 2) {	// moderator
				if ($userinmoderator == 1) $returnperm = 1;
			}
			if ($permtype == 3) {	// owner
				if ($userisowner == 1) $returnperm = 1;
			}
			if ($permtype == 5) {	// visitor
        if (!isset($ElggUser)) $returnperm = 1;
			}
			return $returnperm;
		}

    $nilai = explode("^", $eroom->description);
    $description = $nilai[0];
    $welcome = $nilai[18];
    $offlineMessage = $nilai[19];
    $visitor = get_perm($userisowner, $userinmoderator, $useringroup, $nilai[33]); // change name
    $overLogo = $nilai[42];
    $overLink = $nilai[43];

		// update timelastaccess room nilai [44]
		$newdescription = "";
		for ($i = 0; $i <= 43; $i++) {
			$newdescription .= $nilai[$i]."^";
		}
		$ztime = time();
		$newdescription .= $ztime."^";
		$result = update_data("UPDATE {$CONFIG->dbprefix}objects_entity 
		set description='$newdescription' 
		where guid=$eroom->guid ;");

 if (isset($ElggUser)) {		
		// prosedur user access time, room type, live user count
		$objectb = new ElggObject();		 
		$objectb->subtype = "videowhisper";
		$objectb->title = $room;
		$objectb->description = "5:1:".$ElggUser->get("username").":".$ztime;	// 1 utk livestreaming, 2 utk videochat, 3 utk videoconsultation, 4 utk livestreaming
		$objectb->access_id = 2; 
		$objectb->save();

      // vwconnect
  if (is_plugin_enabled (vwconnect)) {
	// twitter
	/* Load required lib files. */
	$vwconnect = 0;
	$link = $CONFIG->url."livestreaming/".$room."?live=2";
	if (is_dir('../vwconnect')) {
		session_start();
		require_once('../vwconnect/twitteroauth/twitteroauth.php');
		require_once('../vwconnect/config.php');
		$vwconnect = 1;
	}
$sqllastpost_tw = "SELECT ".$CONFIG->dbprefix."objects_entity.description, ".
			 $CONFIG->dbprefix."objects_entity.guid ".
			 "FROM ( ".$CONFIG->dbprefix."entities ".$CONFIG->dbprefix."entities ".
       "INNER JOIN ".
       $CONFIG->dbprefix."entity_subtypes ".$CONFIG->dbprefix."entity_subtypes ".
       "ON (".$CONFIG->dbprefix."entities.subtype = ".$CONFIG->dbprefix."entity_subtypes.id)) ".
       "INNER JOIN ".
       $CONFIG->dbprefix."objects_entity ".$CONFIG->dbprefix."objects_entity ".
       "ON (".$CONFIG->dbprefix."objects_entity.guid = ".$CONFIG->dbprefix."entities.guid) ".
			 "WHERE ".$CONFIG->dbprefix."entity_subtypes.subtype = 'vwconnecttw' AND ".
			 $CONFIG->dbprefix."objects_entity.title = '".$ElggUser->get("username")."' ORDER BY guid DESC LIMIT 1;";
			 
	$lastpost_tw = "";
	$tw_floodprotection = datalist_get('vwconnect_tw_floodProtection');
	$tw_exptime = $ztime - $tw_floodprotection;
	if ($rowlastpost_tw = get_data_row($sqllastpost_tw)) {
		$lastpost_tw = $rowlastpost_tw->description;
	}	
	if ($vwconnect > 0 && $lastpost_tw < $tw_exptime && isset($_SESSION['access_token']) && isset($_SESSION['access_token']['oauth_token']) && isset($_SESSION['access_token']['oauth_token_secret'])) {  
		/* Get user access tokens out of the session. */
		$access_token = $_SESSION['access_token'];

		/* Create a TwitterOauth object with consumer/user tokens. */
		$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $access_token['oauth_token'], $access_token['oauth_token_secret']);

		/* If method is set change API call made. Test is called by default. */
		$twitter = $connection->get('account/verify_credentials');
		$connection->post('statuses/update', array('status' => 'I just entered this room '.$link));

		// prosedur utk floodprotection twitter
		$objecttw = new ElggObject();
		$objecttw->subtype = "vwconnecttw";
		$objecttw->title = $ElggUser->get("username");
		$objecttw->description = $ztime;
		$objecttw->access_id = 2; 
		$objecttw->save();   
   }

	// fb
$sqllastpost_fb = "SELECT ".$CONFIG->dbprefix."objects_entity.description, ".
			 $CONFIG->dbprefix."objects_entity.guid ".
			 "FROM ( ".$CONFIG->dbprefix."entities ".$CONFIG->dbprefix."entities ".
       "INNER JOIN ".
       $CONFIG->dbprefix."entity_subtypes ".$CONFIG->dbprefix."entity_subtypes ".
       "ON (".$CONFIG->dbprefix."entities.subtype = ".$CONFIG->dbprefix."entity_subtypes.id)) ".
       "INNER JOIN ".
       $CONFIG->dbprefix."objects_entity ".$CONFIG->dbprefix."objects_entity ".
       "ON (".$CONFIG->dbprefix."objects_entity.guid = ".$CONFIG->dbprefix."entities.guid) ".
			 "WHERE ".$CONFIG->dbprefix."entity_subtypes.subtype = 'vwconnectfb' AND ".
			 $CONFIG->dbprefix."objects_entity.title = '".$ElggUser->get("username")."' ORDER BY guid DESC LIMIT 1;";
			 
	$lastpost_fb = "";
	$fb_floodprotection = datalist_get('vwconnect_fb_floodProtection');
	$fb_exptime = $ztime - $fb_floodprotection;
	if ($rowlastpost_fb = get_data_row($sqllastpost_fb)) {
		$lastpost_fb = $rowlastpost_fb->description;
	}

	if ($vwconnect == 1 && $lastpost_fb < $fb_exptime) {
		require '../vwconnect/src/facebook.php';
		
// Create our Application instance (replace this with your appId and secret).
$appId = datalist_get('vwconnect_facebook_appId');
$secret = datalist_get('vwconnect_facebook_secret');

$facebook = new Facebook(array(
  'appId' => $appId,
  'secret' => $secret,
  'cookie' => true,
));

	// Get User ID
	$fbuser = $facebook->getUser();

	if ($fbuser) {

			// prosedur utk floodprotection facebook
			$objectfb = new ElggObject();
			$objectfb->subtype = "vwconnectfb";
			$objectfb->title = $ElggUser->get("username");
			$objectfb->description = $ztime;
			$objectfb->access_id = 2; 
			$objectfb->save();						
	try {
			// Proceed knowing you have a logged in user who's authenticated.
			$user_profile = $facebook->api('/me');
$publishStream = $facebook->api("/$user/feed", 'post', array(
                    'message' => 'I just entered this room '.$link
                    )
                );
		} catch (FacebookApiException $e) {
			error_log($e);
			$user = null;
		}
	}
}
} // end vwconnect
} // end isset($ElggUser)
} // end isset($eroom)

$welcome=$welcome."<br>".$description;

if (!$room) $room="Lobby";
$friendlyroom=ucwords(str_replace("-"," ",$room));
$room=urlencode($room);

?>server=<?=$rtmp_server?>&serverAMF=<?=$rtmp_amf?>&tokenKey=<?=$tokenKey?>&serverRTMFP=<?=$rtmfp_server?>&p2pGroup=<?=$p2pGroup?>&bufferLive=<?=$bufferLive?>&bufferFull=<?=$bufferFull?>&welcome=<?=$welcome?>&username=<?=$username?>&userType=<?=$userType?>&msg=<?=$message?>&visitor=<?=$visitor?>&loggedin=<?=$loggedin?>&showCredit=1&disconnectOnTimeout=1&offlineMessage=<?=$offlineMessage?>&serverProxy=<?=$serverProxy?>&statusInterval=<?=$statusInterval?>&overLogo=<?=$overLogo?>&overLink=<?=$overLink?>&loadstatus=1
