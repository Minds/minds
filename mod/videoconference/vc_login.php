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

    //configure a picture to show when this user is clicked
  if (isset($ElggUser)) {
  	$ver=explode('.', get_version(true));			
  	if ($ver[1]>7) $image_url = $ElggUser->getIconURL('large');
    else $image_url = get_entity_icon_url ($ElggUser, 'large');
    $userPicture=urlencode($image_url);
    $userLink=urlencode($ElggUser->getURL());
  } else {
    $userPicture=urlencode("defaultpicture.png");
    $userLink=urlencode("http://www.videowhisper.com/");
  }

    $rtmp_server = datalist_get('vconf_rtmp_server');
    $rtmp_amf = datalist_get('vconf_rtmp_amf');
    $rtmfp_server = datalist_get('vconf_rtmfp_server');
    $p2pGroup = datalist_get('vconf_p2pGroup');
    $enableRTMP = datalist_get('vconf_enableRTMP');
    $enableP2P = datalist_get('vconf_enableP2P');
    $supportRTMP = datalist_get('vconf_supportRTMP');
    $supportP2P = datalist_get('vconf_supportP2P');
    $alwaysRTMP = datalist_get('vconf_alwaysRTMP');
    $alwaysP2P = datalist_get('vconf_alwaysP2P');
    $camMaxBandwidth = datalist_get('vconf_camMaxBandwidth');
    $bufferFullPlayback = datalist_get('vconf_bufferFullPlayback');
    $bufferLivePlayback = datalist_get('vconf_bufferLivePlayback');
    $bufferFull = datalist_get('vconf_bufferFull');
    $bufferLive = datalist_get('vconf_bufferLive');
    $disableBandwidthDetection = datalist_get('vconf_disableBandwidthDetection');
    $disableUploadDetection = datalist_get('vconf_disableUploadDetection');
    $limitByBandwidth = datalist_get('vconf_limitByBandwidth');
    $adsTimeout = datalist_get('vconf_adsTimeout');
    $adsInterval = datalist_get('vconf_adsInterval');
    $statusInterval = datalist_get('vconf_statusInterval');
    $ws_ads = datalist_get('vconf_ws_ads');
//    $cleanUpSetting = datalist_get('vconf_cleanUp');        
    
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
    $background_url = $nilai[5];
    $layoutCode = html_entity_decode($nilai[6]); 
    $fillWindow = $nilai[7];
    $filterRegex = $nilai[8];
    $filterReplace = $nilai[9];
    $camFPS = $nilai[10];
    $micRate = $nilai[11];
		
    $showCamSettings = get_perm($userisowner, $userinmoderator, $useringroup, $nilai[12]);
    $advancedCamSettings = get_perm($userisowner, $userinmoderator, $useringroup, $nilai[13]);
    $configureSource = get_perm($userisowner, $userinmoderator, $useringroup, $nilai[14]);
    $disableVideo = get_perm2($userisowner, $userinmoderator, $useringroup, $nilai[15]);
    $disableSound = get_perm2($userisowner, $userinmoderator, $useringroup, $nilai[16]);
    $panelFiles = get_perm($userisowner, $userinmoderator, $useringroup, $nilai[17]);
    $file_upload = get_perm($userisowner, $userinmoderator, $useringroup, $nilai[18]);
    $file_delete = get_perm($userisowner, $userinmoderator, $useringroup, $nilai[19]);
    $tutorial = get_perm($userisowner, $userinmoderator, $useringroup, $nilai[20]);
    $autoViewCams = get_perm($userisowner, $userinmoderator, $useringroup, $nilai[21]);
    $showTimer = get_perm($userisowner, $userinmoderator, $useringroup, $nilai[22]); //$nilai[23] for userlist,$nilai[24] for moderatorlist, $nilai[25] for cleanup 
    $writeText = get_perm($userisowner, $userinmoderator, $useringroup, $nilai[26]);
    $regularWatch = get_perm($userisowner, $userinmoderator, $useringroup, $nilai[27]);
    $newWatch = get_perm($userisowner, $userinmoderator, $useringroup, $nilai[28]);
    $privateTextchat = get_perm($userisowner, $userinmoderator, $useringroup, $nilai[29]);
    $floodProtection = $nilai[30];
    // $visitor = $nilai[31];
    $admin = get_perm($userisowner, $userinmoderator, $useringroup, $nilai[32]);
    // $ztime = $nilai[33];
    $panelRooms = get_perm($userisowner, $userinmoderator, $useringroup, $nilai[34]);
    $panelUsers = get_perm($userisowner, $userinmoderator, $useringroup, $nilai[35]);
    $verboseLevel = $nilai[36];
		
		// update timelastaccess room nilai [37]
		$newdescription = "";
		for ($i = 0; $i <= 36; $i++) {
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
		$objectb->description = "1:1:".$ElggUser->get("username").":".$ztime;	// 1 utk videoconference, 2 utk videochat, 3 utk videoconsultation, 4 utk livestreaming
		$objectb->access_id = 2; 
		$objectb->save();

// vwconnect
if (is_plugin_enabled (vwconnect)) {
	// twitter
	/* Load required lib files. */
	$vwconnect = 0;
	$link = $CONFIG->url."videoconference/".$room;
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
		$connection->post('statuses/update', array('status' => 'I just entered this video conference room '.$link));
   
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
			$publishStream = $facebook->api("/$user/feed", 'post', array('message' => 'I just entered this video conference room '.$link ));
		} catch (FacebookApiException $e) {
			error_log($e);
			$user = null;
		}
	}
}
} // end vwconnect
} // end isset ($ElggUser)
} // end isset ($eroom)

$layoutCode=<<<layoutEND
$layoutCode
layoutEND;

if (!$room) $room="Lobby";
$friendlyroom=ucwords(str_replace("-"," ",$room));
$room=urlencode($room);

//replace bad words or expression
$filterRegex=urlencode($filterRegex);
$filterReplace=urlencode($filterReplace);

$welcome=$welcome."<br>".$description;
		
?>firstParameter=fix&server=<?=$rtmp_server?>&serverAMF=<?=$rtmp_amf?>&serverRTMFP=<?=$rtmfp_server?>&p2pGroup=<?=$p2pGroup?>&enableRTMP=<?=$enableRTMP?>&enableP2P=<?=$enableP2P?>&supportRTMP=<?=$supportRTMP?>&supportP2P=<?=$supportP2P?>&alwaysRTMP=<?=$alwaysRTMP?>&alwaysP2P=<?=$alwaysP2P?>&username=<?=urlencode($username)?>&loggedin=<?=$loggedin?>&userType=<?=$userType?>&administrator=<?=$admin?>&room=<?=urlencode($room)?>&welcome=<?=urlencode($welcome)?>&userPicture=<?=$userPicture?>&userLink=<?=$userLink?>&webserver=&msg=<?=$message?>&tutorial=<?=$tutorial?>&room_delete=0&room_create=0&file_upload=<?=$file_upload?>&file_delete=<?=$file_delete?>&panelFiles=<?=$panelFiles?>&panelRooms=<?=$panelRooms?>&panelUsers=<?=$panelUsers?>&verboseLevel=<?=$verboseLevel?>&showTimer=<?=$showTimer?>&showCredit=1&disconnectOnTimeout=0&camWidth=<?=$camWidth?>&camHeight=<?=$camHeight?>&camFPS=<?=$camFPS?>&micRate=<?=$micRate?>&camBandwidth=<?=$camBandwidth?>&bufferLive=<?=$bufferLive?>&bufferFull=<?=$bufferFull?>&bufferLivePlayback=<?=$bufferLivePlayback?>&bufferFullPlayback=<?=$bufferFullPlayback?>&showCamSettings=<?=$showCamSettings?>&advancedCamSettings=<?=$advancedCamSettings?>&camMaxBandwidth=<?=$camMaxBandwidth?>&configureSource=<?=$configureSource?>&disableVideo=<?=$disableVideo?>&disableSound=<?=$disableSound?>&disableBandwidthDetection=<?=$disableBandwidthDetection?>&disableUploadDetection=<?=$disableUploadDetection?>&limitByBandwidth=<?=$limitByBandwidth?>&background_url=<?=$background_url?>&autoViewCams=<?=$autoViewCams?>&layoutCode=<?=urlencode($layoutCode)?>&fillWindow=<?=$fillWindow?>&filterRegex=<?=urlencode($filterRegex)?>&filterReplace=<?=urlencode($filterReplace)?>&writeText=<?=$writeText?>&floodProtection=<?=$floodProtection?>&regularWatch=<?=$regularWatch?>&newWatch=<?=$newWatch?>&privateTextchat=<?=$privateTextchat?>&ws_ads=<?=urlencode($ws_ads)?>&adsTimeout=<?=$adsTimeout?>&adsInterval=<?=$adsInterval?>&statusInterval=<?=$statusInterval?>&loadstatus=1
