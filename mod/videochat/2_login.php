<?php

$room = $_GET['room_name'];

//record current room in cookie to show in rooms list
if ($room) setcookie("userroom",urlencode($room),time()+86400);

require_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");
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

// room settings
    $rtmp_server = datalist_get('vchat_rtmp_server');
    $rtmp_amf = datalist_get('vchat_rtmp_amf');
    $rtmfp_server = datalist_get('vchat_rtmfp_server');
    $rtmfp_snapshotsTime = datalist_get('vchat_snapshotsTime');
    $camMaxBandwidth = datalist_get('vchat_camMaxBandwidth');
    $bufferFullPlayback = datalist_get('vchat_bufferFullPlayback');
    $bufferLivePlayback = datalist_get('vchat_bufferLivePlayback');
    $bufferFull = datalist_get('vchat_bufferFull');
    $bufferLive = datalist_get('vchat_bufferLive');
    $disableBandwidthDetection = datalist_get('vchat_disableBandwidthDetection');
    $disableUploadDetection = datalist_get('vchat_disableUploadDetection');
    $limitByBandwidth = datalist_get('vchat_limitByBandwidth');
    $adsTimeout = datalist_get('vchat_adsTimeout');
    $adsInterval = datalist_get('vchat_adsInterval');
    $adServer = datalist_get('vchat_adServer');  
    $serverProxy = datalist_get('vchat_serverProxy');  

    	function get_perm($userisowner, $userinmoderator, $useringroup, $permtype) {
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
    $camWidth = $nilai[2];
    $camHeight = $nilai[3];
    $camBandwidth = $nilai[4];
    $filterRegex = $nilai[5];
    $filterReplace = $nilai[6];
    $camFPS = $nilai[7];
    $micRate = $nilai[8];
 
    $showCamSettings = get_perm($userisowner, $userinmoderator, $useringroup, $nilai[9]);
    $configureSource = get_perm($userisowner, $userinmoderator, $useringroup, $nilai[10]); 
    $disableVideo = get_perm2($userisowner, $userinmoderator, $useringroup, $nilai[11]);
    $disableSound = get_perm2($userisowner, $userinmoderator, $useringroup, $nilai[12]); 
    $showTimer = get_perm($userisowner, $userinmoderator, $useringroup, $nilai[13]); 
    //$userList = $nilai[14]; $moderatorList = $nilai[15]; $cleanUp = $nilai[16];
    $disableEmoticons = get_perm($userisowner, $userinmoderator, $useringroup, $nilai[17]); 
    $showTextChat = get_perm($userisowner, $userinmoderator, $useringroup, $nilai[18]); 
    $sendTextChat = get_perm($userisowner, $userinmoderator, $useringroup, $nilai[19]);
    $enableP2P = get_perm($userisowner, $userinmoderator, $useringroup, $nilai[20]); 
    $enableServer = get_perm($userisowner, $userinmoderator, $useringroup, $nilai[21]);
    $configureConnection = get_perm($userisowner, $userinmoderator, $useringroup, $nilai[22]); 
    $enableNext = get_perm($userisowner, $userinmoderator, $useringroup, $nilai[23]); 
    $enableBuzz = get_perm($userisowner, $userinmoderator, $useringroup, $nilai[24]); 
    $enableSoundFx = get_perm($userisowner, $userinmoderator, $useringroup, $nilai[25]); 
    $requestSnapshot = get_perm($userisowner, $userinmoderator, $useringroup, $nilai[26]); 
    $autoSnapshots = get_perm($userisowner, $userinmoderator, $useringroup, $nilai[27]); 
    $verboseLevel = $nilai[29]; 
    // $ztime = $nilai[30]);
    $camPicture = get_perm($userisowner, $userinmoderator, $useringroup, $nilai[31]); 
    $enableButtonLabels = get_perm($userisowner, $userinmoderator, $useringroup, $nilai[32]); 
    $enableFullscreen = get_perm($userisowner, $userinmoderator, $useringroup, $nilai[33]); 
    $enableSwap = get_perm($userisowner, $userinmoderator, $useringroup, $nilai[34]); 
    $enableLogout = get_perm($userisowner, $userinmoderator, $useringroup, $nilai[35]); 
    $enableLogo = get_perm($userisowner, $userinmoderator, $useringroup, $nilai[36]); 
    $enableHeaders = get_perm($userisowner, $userinmoderator, $useringroup, $nilai[37]); 
    $enableTitles = get_perm($userisowner, $userinmoderator, $useringroup, $nilai[38]); 
    $videoW = $nilai[39]; 
    $videoH = $nilai[40]; 
    $video2W = $nilai[41]; 
    $video2H = $nilai[42]; 
    $layoutCode = html_entity_decode($nilai[43]); 
    $chatTextColor = $nilai[44]; 

		// update timelastaccess room nilai [45]
		$newdescription = "";
		for ($i = 0; $i <= 44; $i++) {
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
		$objectb->description = "2:1:".$ElggUser->get("username").":".$ztime;	// 1 utk videoconference, 2 utk videochat, 3 utk videoconsultation, 4 utk livestreaming
		$objectb->access_id = 2; 
		$objectb->save();

      // vwconnect
  if (is_plugin_enabled (vwconnect)) {		
  	// twitter
	/* Load required lib files. */
	$vwconnect = 0;
	$link = $CONFIG->url."videochat/".$room;
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
		$connection->post('statuses/update', array('status' => 'I just entered this video chat room '.$link));

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
                    'message' => 'I just entered this video chat room '.$link
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

if (!$room) $room="Lobby";
$friendlyroom=ucwords(str_replace("-"," ",$room));
$room=urlencode($room);

//replace bad words or expression
$filterRegex=urlencode($filterRegex);
$filterReplace=urlencode($filterReplace);

$welcome=$welcome."<br>".$description;

?>fixOutput=decoy&server=<?=$rtmp_server?>&serverAMF=<?=$rtmp_amf?>&serverRTMFP=<?=$rtmfp_server?>&room=<?=urlencode($room)?>&welcome=<?=urlencode($welcome)?>&username=<?=$username?>&msg=<?=$message?>&loggedin=<?=$loggedin?>&showTimer=<?=$showTimer?>&showCredit=1&disconnectOnTimeout=0&camWidth=<?=$camWidth?>&camHeight=<?=$camHeight?>&camFPS=<?=$camFPS?>&micRate=<?=$micRate?>&camBandwidth=<?=$camBandwidth?>&limitByBandwidth=<?=$limitByBandwidth?>&showCamSettings=<?=$showCamSettings?>&camMaxBandwidth=<?=$camMaxBandwidth?>&disableBandwidthDetection=<?=$disableBandwidthDetection?>&disableUploadDetection=<?=$disableUploadDetection?>&verboseLevel=<?=$verboseLevel?>&disableVideo=<?=$disableVideo?>&disableSound=<?=$disableSound?>&bufferLive=<?=$bufferLive?>&bufferFull=<?=$bufferFull?>&bufferLivePlayback=<?=$bufferLivePlayback?>&bufferFullPlayback=<?=$bufferFullPlayback?>&filterRegex=<?=$filterRegex?>&filterReplace=<?=$filterReplace?>&disableEmoticons=<?=$disableEmoticons?>&showTextChat=<?=$showTextChat?>&sendTextChat=<?=$sendTextChat?>&enableP2P=<?=$enableP2P?>&enableServer=<?=$enableServer?>&configureConnection=<?=$configureConnection?>&configureSource=<?=$configureSource?>&enableNext=<?=$enableNext?>&enableBuzz=<?=$enableBuzz?>&enableSoundFx=<?=$enableSoundFx?>&requestSnapshot=<?=$requestSnapshot?>&autoSnapshots=<?=$autoSnapshots?>&snapshotsTime=<?=$snapshotsTime?>&adServer=<?=urlencode($adServer)?>&adsInterval=<?=$adsInterval?>&adsTimeout=<?=$adsTimeout?>&serverProxy=<?=$serverProxy?>&camPicture=<?=$camPicture?>&enableButtonLabels=<?=$enableButtonLabels?>&enableFullscreen=<?=$enableFullscreen?>&enableSwap=<?=$enableSwap?>&enableLogout=<?=$enableLogout?>&enableLogo=<?=$enableLogo?>&enableHeaders=<?=$enableHeaders?>&enableTitles=<?=$enableTitles?>&videoW=<?=$videoW?>&videoH=<?=$videoH?>&video2W=<?=$video2W?>&video2H=<?=$video2H?>&layoutCode=<?=urlencode($layoutCode)?>&chatTextColor=<?=$chatTextColor?>&loadstatus=1
