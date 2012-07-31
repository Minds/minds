<?php

$room = $_GET['room_name'];

//record current room in cookie to show in rooms list
if ($room) setcookie("userroom",urlencode($room),time()+86400);

require_once(dirname(dirname(dirname(__FILE__))) . '/engine/start.php');
$ElggUser=get_loggedin_user();
if (isset($ElggUser)) include("int.login.php");
  else include("int.login2.php");

// cek userloggedin in group / friends of this room
if (isset($eroom)) {
  if (isset($ElggUser)) {
  $sqlgroup1 = "SELECT user_guid FROM ".$CONFIG->dbprefix."access_collection_membership ".
						 "WHERE user_guid = '".get_loggedin_userid()."' AND access_collection_id = '".$eroom->access_id."';";		
  if ($rowgroup = get_data_row($sqlgroup1)) {
	 $useringroup = 1;
  }
}

		$rtmp_server = datalist_get('vcons_rtmp_server');
		$rtmp_amf = datalist_get('vcons_rtmp_amf');
    $rtmfp_server = datalist_get('vcons_rtmfp_server');
    $p2pGroup = datalist_get('vcons_p2pGroup');
		$camMaxBandwidth = datalist_get('vcons_camMaxBandwidth');
		$bufferFullPlayback = datalist_get('vcons_bufferFullPlayback');
		$bufferLivePlayback = datalist_get('vcons_bufferLivePlayback');
		$bufferFull = datalist_get('vcons_bufferFull');
		$bufferLive = datalist_get('vcons_bufferLive');
		$disableBandwidthDetection = datalist_get('vcons_disableBandwidthDetection');
		$disableUploadDetection = datalist_get('vcons_disableUploadDetection');
		$limitByBandwidth = datalist_get('vcons_limitByBandwidth');
		$statusInterval = datalist_get('vcons_statusInterval');
		//$availability = datalist_get('vcons_availability');

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

		function get_perm2($userisowner, $userinmoderator, $useringroup, $permtype) {
			$returnperm = 1;
			if ($permtype == 0) {	// all
				$returnperm = 0;
			}
			if ($permtype == 1) {	// groups
				if ($useringroup == 1) $returnperm = 0;
			}
			if ($permtype == 2) {	// moderator
				if ($userinmoderator == 1) $returnperm = 0;
			}
			if ($permtype == 3) {	// owner
				if ($userisowner == 1) $returnperm = 0;
			}
			return $returnperm;
		}

	$nilai = explode("^", $eroom->description);
	$description = $nilai[0];
	$welcome = $nilai[1];
    if (isset($ElggUser)) { $visitor = 0; // visitor is not loggedin user
    } else $visitor = $nilai[2];
    $background_url = $nilai[3];
    $change_background = get_perm($userisowner, $userinmoderator, $useringroup, $nilai[4]);
    $room_limit = $nilai[5];
    $showTimer = get_perm($userisowner, $userinmoderator, $useringroup, $nilai[6]);
    $regularCams = get_perm($userisowner, $userinmoderator, $useringroup, $nilai[7]);
    $regularWatch = get_perm($userisowner, $userinmoderator, $useringroup, $nilai[8]);
	$camWidth = $nilai[9];
    $camHeight = $nilai[10];
    $camfps = $nilai[11];
    $micrate = $nilai[12];
    $camBandwidth = $nilai[13];
    $showCamSettings = get_perm($userisowner, $userinmoderator, $useringroup, $nilai[14]);
    $advancedCamSettings = get_perm($userisowner, $userinmoderator, $useringroup, $nilai[15]);
    $configureSource = get_perm($userisowner, $userinmoderator, $useringroup, $nilai[16]);
    $disableVideo = get_perm2($userisowner, $userinmoderator, $useringroup, $nilai[17]);
    $disableSound = get_perm2($userisowner, $userinmoderator, $useringroup, $nilai[18]);
    $files_enabled = get_perm($userisowner, $userinmoderator, $useringroup, $nilai[19]);
    $file_upload = get_perm($userisowner, $userinmoderator, $useringroup, $nilai[20]);
    $file_delete = get_perm($userisowner, $userinmoderator, $useringroup, $nilai[21]);
    $chat_enabled = get_perm($userisowner, $userinmoderator, $useringroup, $nilai[22]);
    $floodProtection = $nilai[23];
    $writeText = get_perm($userisowner, $userinmoderator, $useringroup, $nilai[24]);
    $privateTextchat = get_perm($userisowner, $userinmoderator, $useringroup, $nilai[25]);
    $externalStream = get_perm($userisowner, $userinmoderator, $useringroup, $nilai[26]);
    $slideShow = get_perm($userisowner, $userinmoderator, $useringroup, $nilai[27]);
    $users_enabled = get_perm($userisowner, $userinmoderator, $useringroup, $nilai[28]);
    $publicVideosN = $nilai[29];
    $publicVideosAdd = get_perm($userisowner, $userinmoderator, $useringroup, $nilai[30]);
    $publicVideosMax = $nilai[31];
    $publicVideosW = $nilai[32];
    $publicVideosH = $nilai[33];
    $publicVideosX = $nilai[34];
    $publicVideosY = $nilai[35];
    $publicVideosColumns = $nilai[36];
    $publicVideosRows = $nilai[37];
    $autoplayServer = $nilai[38];
    $autoplayStream = $nilai[39];
    $layoutCode = html_entity_decode($nilai[40]);
    $fillWindow = $nilai[41];
    $filterRegex = $nilai[42];
    $filterReplace = $nilai[43];
    $visitor = get_perm($userisowner, $userinmoderator, $useringroup, $nilai[47]); // change name
    $admin = get_perm($userisowner, $userinmoderator, $useringroup, $nilai[48]);
    // $ztime = $nilai[49];
    $verboseLevel = $nilai[50];
	
		// update timelastaccess room nilai [51]
		$newdescription = "";
		for ($i = 0; $i <= 50; $i++) {
			$newdescription .= $nilai[$i]."^";
		}
		$ztime = time();
		$newdescription .= $ztime."^";
		$result = update_data("UPDATE {$CONFIG->dbprefix}objects_entity 
		set description='$newdescription' 
		where guid=$eroom->guid ;");

  if (isset($ElggUser)) {		
		// prosedur utk menangani user access time, room type, live user count
		$objectb = new ElggObject();		 
		$objectb->subtype = "videowhisper";
		$objectb->title = $room;
		$objectb->description = "3:1:".$ElggUser->get("username").":".$ztime;	// 1 utk videoconference, 2 utk videochat, 3 utk videoconsultation, 4 utk livestreaming
		$objectb->access_id = 2; 
		$objectb->save();

// vwconnect
if (is_plugin_enabled (vwconnect)) {
	// twitter
	/* Load required lib files. */
	$vwconnect = 0;
	$link = $CONFIG->url."videoconsultation/".$room;
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
		$connection->post('statuses/update', array('status' => 'I just entered this video consultation room '.$link));

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
                    'message' => 'I just entered this video consultation room '.$link
                    )
                );
		} catch (FacebookApiException $e) {
			error_log($e);
			$user = null;
		}
	}
}
} // end vwconnect
} // end isset ($ElggUser)
} // end isset ($eroom)

//replace bad words or expression
$filterRegex=urlencode($filterRegex);
$filterReplace=urlencode($filterReplace);

$layoutCode=<<<layoutEND
$layoutCode
layoutEND;

if (!$room) $room="Lobby";
$friendlyroom=ucwords(str_replace("-"," ",$room));
$room=urlencode($room);

$debug="$key--$adminkey--$room";

if (!$background_url) $background_url=urlencode("templates/consultation/background.jpg");

$welcome = $welcome."<br>".$description;

?>server=<?=$rtmp_server?>&serverAMF=<?=$rtmp_amf?>&serverRTMFP=<?=$rtmfp_server?>&p2pGroup=<?=$p2pGroup?>&supportRTMP=1&supportP2P=1&alwaysRTMP=0&alwaysP2P=0&room=<?=$room?>&welcome=<?=$welcome?>&username=<?=$username?>&msg=<?=$message?>&visitor=<?=$visitor?>&loggedin=<?=$loggedin?>&background_url=<?=$background_url?>&change_background=<?=$change_background?>&room_limit=<?=$room_limit?>&administrator=<?=$admin?>&showTimer=<?=$showTimer?>&showCredit=1&disconnectOnTimeout=1&statusInterval=<?=$statusInterval?>&regularCams=<?=$regularCams?>&regularWatch=<?=$regularWatch?>&camWidth=<?=$camWidth?>&camHeight=<?=$camHeight?>&camFPS=<?=$camfps?>&micRate=<?=$micrate?>&camBandwidth=<?=$camBandwidth?>&showCamSettings=<?=$showCamSettings?>&advancedCamSettings=<?=$advancedCamSettings?>&camMaxBandwidth=<?=$camMaxBandwidth?>&configureSource=<?=$configureSource?>&disableVideo=<?=$disableVideo?>&disableSound=<?=$disableSound?>&bufferLive=<?=$bufferLive?>&bufferFull=<?=$bufferFull?>&bufferLivePlayback=<?=$bufferLivePlayback?>&bufferFullPlayback=<?=$bufferFullPlayback?>&disableBandwidthDetection=<?=$disableBandwidthDetection?>&disableUploadDetection=<?=$disableUploadDetection?>&limitByBandwidth=<?=$limitByBandwidth?>&files_enabled=<?=$files_enabled?>&file_upload=<?=$file_upload?>&file_delete=<?=$file_delete?>&chat_enabled=<?=$chat_enabled?>&floodProtection=<?=$floodProtection?>&writeText=<?=$writeText?>&privateTextchat=<?=$privateTextchat?>&externalStream=<?=$externalStream?>&slideShow=<?=$slideShow?>&users_enabled=<?=$users_enabled?>&publicVideosN=<?=$publicVideosN?>&publicVideosAdd=<?=$publicVideosAdd?>&publicVideosMax=<?=$publicVideosMax?>&publicVideosW=<?=$publicVideosW?>&publicVideosH=<?=$publicVideosH?>&publicVideosX=<?=$publicVideosX?>&publicVideosY=<?=$publicVideosY?>&publicVideosColumns=<?=$publicVideosColumns?>&publicVideosRows=<?=$publicVideosRows?>&autoplayServer=<?=$autoplayServer?>&autoplayStream=<?=$autoplayStream?>&layoutCode=<?=urlencode($layoutCode)?>&fillWindow=<?=$fillWindow?>&filterRegex=<?=$filterRegex?>&filterReplace=<?=$filterReplace?>&debugmessage=<?=urlencode($debug)?>&verboseLevel=<?=$verboseLevel?>&loadstatus=1
