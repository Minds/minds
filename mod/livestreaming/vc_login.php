<?php

$room = $_GET['room_name'];

//record current room in cookie to show in rooms list
if ($room) setcookie("userroom",urlencode($room),time()+86400);

include("int.login.php");

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

    $rtmp_server = datalist_get('lstr_rtmp_server');
    $rtmp_amf = datalist_get('lstr_rtmp_amf');
    $rtmfp_server = datalist_get('lstr_rtmfp_server');
    $p2pGroup = datalist_get('lstr_p2pGroup');
    $camMaxBandwidth = datalist_get('lstr_camMaxBandwidth');
    $snapshotsTime = datalist_get('lstr_snapshotsTime');
    $externalInterval = datalist_get('lstr_externalInterval');
    $bufferFull = datalist_get('lstr_bufferFull');
    $bufferLive = datalist_get('lstr_bufferLive');
    $disableBandwidthDetection = datalist_get('lstr_disableBandwidthDetection');
    $limitByBandwidth = datalist_get('lstr_limitByBandwidth');
    $generateSnapshots = datalist_get('lstr_generateSnapshots');
    $tokenKey = datalist_get('lstr_tokenKey');
    $serverProxy = datalist_get('lstr_serverProxy');
    $enableRTMP = datalist_get('lstr_enableRTMP');
    $enableP2P = datalist_get('lstr_enableP2P');
    $supportRTMP = datalist_get('lstr_supportRTMP');
    $supportP2P = datalist_get('lstr_supportP2P');
    $alwaysRTMP = datalist_get('lstr_alwaysRTMP');
    $alwaysP2P = datalist_get('lstr_alwaysP2P'); 
    $videoCodec = datalist_get('lstr_videoCodec');
    $codecProfile = datalist_get('lstr_codecProfile');
    $codecLevel = datalist_get('lstr_codecLevel');
    $soundCodec = datalist_get('lstr_soundCodec');
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
    $welcome = $nilai[1];
    $camWidth = $nilai[2];
    $camHeight = $nilai[3];
    $room_limit = $nilai[4]; 
    $showTimer = get_perm($userisowner, $userinmoderator, $useringroup, $nilai[5]); 
    $camFPS = $nilai[6];
    $micRate = $nilai[7]; 
    $camBandwidth = $nilai[8]; 
    $showCamSettings = get_perm($userisowner, $userinmoderator, $useringroup, $nilai[9]); 
    $advancedCamSettings = get_perm($userisowner, $userinmoderator, $useringroup, $nilai[10]); 
    $configureSource = get_perm($userisowner, $userinmoderator, $useringroup, $nilai[11]); 
    $onlyVideo = get_perm($userisowner, $userinmoderator, $useringroup, $nilai[12]); 
    $noVideo = get_perm($userisowner, $userinmoderator, $useringroup, $nilai[13]); 
    $noEmbeds = get_perm($userisowner, $userinmoderator, $useringroup, $nilai[14]); 
    $labelColor = $nilai[15]; 
    $writeText = get_perm($userisowner, $userinmoderator, $useringroup, $nilai[16]); 
    $floodProtection = $nilai[17]; 
    $filterRegex = $nilai[21];
    $filterReplace = $nilai[22];
    $layoutCode = html_entity_decode($nilai[23]);
    $fillWindow = $nilai[24];
    $verboseLevel = $nilai[37];
    $privateTextchat = get_perm($userisowner, $userinmoderator, $useringroup, $nilai[39]);
    $soundQuality = $nilai[41];
    $overLogo = $nilai[42];
    $overLink = $nilai[43];

$layoutCode=<<<layoutEND
$layoutCode
layoutEND;

//replace bad words or expression
$filterRegex=urlencode($filterRegex);
$filterReplace=urlencode($filterReplace);

$welcome=$welcome."<br>".$description;


$ver=explode('.', get_version(true));			
if ($ver[1]>7) $base=$CONFIG->url."livestreaming/"; else $base=$CONFIG->url."pg/livestreaming/";
$base2=$CONFIG->url."mod/livestreaming/";
$linkcode=$base."".urlencode($room)."?live=2";
$imagecode=$base2."snapshots/".urlencode($room).".jpg";
$swfurl=$base."live_watch.swf?n=".urlencode($room);
$swfurl2=$base."live_video.swf?n=".urlencode($room);

$embedcode =<<<EMBEDEND
<object width="640" height="350"><param name="movie" value="$swfurl" /><param name="base" value="$base" /><param name="allowFullScreen" value="true" /><param name="allowscriptaccess" value="always" /><param name="scale" value="noscale" /><param name="salign" value="lt" /><embed src="$swfurl" base="$base" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" width="640" height="350" scale="noscale" salign="lt"></embed></object>
EMBEDEND;
$embedvcode =<<<EMBEDEND2
<object width="320" height="240"><param name="movie" value="$swfurl2" /><param name="base" value="$base" /><param name="scale" value="exactfit"/><param name="allowFullScreen" value="true" /><param name="allowscriptaccess" value="always" /><embed src="$swfurl2" base="$base" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" width="320" height="240" scale="exactfit"></embed></object>
EMBEDEND2;

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
		$objectb->description = "4:1:".$ElggUser->get("username").":".$ztime;	// 1 utk livestreaming, 2 utk videochat, 3 utk videoconsultation, 4 utk livestreaming
		$objectb->access_id = 2; 
		$objectb->save();

// vwconnect
if (is_plugin_enabled (vwconnect)) {
	// twitter
	/* Load required lib files. */
	$vwconnect = 0;
	$link2 = $CONFIG->url."livestreaming/".$room."?live=2";
	$link3 = $CONFIG->url."livestreaming/".$room."?live=3";
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
		$connection->post('statuses/update', array('status' => 'I just entered this room '.$link2.' (live watch), or '.$link3.'(live video)'));
		
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
                    'message' => 'I just entered this room '.$link2.' (live watch), or '.$link3.'(live video)'
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

if (!$room) $room="Lobby";
$friendlyroom=ucwords(str_replace("-"," ",$room));
$room=urlencode($room);

$username = $room;

?>server=<?=$rtmp_server?>&serverAMF=<?=$rtmp_amf?>&tokenKey=<?=$tokenKey?>&serverProxy=<?=$serverProxy?>&serverRTMFP=<?=$rtmfp_server?>&p2pGroup=<?=$p2pGroup?>&enableRTMP=<?=$enableRTMP?>&enableP2P=<?=$enableP2P?>&supportRTMP=<?=$supportRTMP?>&supportP2P=<?=$supportP2P?>&alwaysRTMP=<?=$alwaysRTMP?>&alwaysP2P=<?=$alwaysP2P?>&room=<?=$room?>&welcome=<?=$welcome?>&username=<?=$username?>&userType=3&userPicture=<?=$userPicture?>&userLink=<?=$userLink?>&overLogo=<?=$overLogo?>&overLink=<?=$overLink?>&webserver=&msg=<?=$message?>&loggedin=<?=$loggedin?>&linkcode=<?=urlencode($linkcode)?>&embedcode=<?=urlencode($embedcode)?>&embedvcode=<?=urlencode($embedvcode)?>&imagecode=<?=urlencode($imagecode)?>&room_limit=<?=$room_limit?>&showTimer=<?=$showTimer?>&showCredit=1&disconnectOnTimeout=1&statusInterval=<?=$statusInterval?>&camWidth=<?=$camWidth?>&camHeight=<?=$camHeight?>&camFPS=<?=$camFPS?>&micRate=<?=$micRate?>&camBandwidth=<?=$camBandwidth?>&bufferLive=<?=$bufferLive?>&bufferFull=<?=$bufferFull?>&videoCodec=<?=$videoCodec?>&codecProfile=<?=$codecProfile?>&codecLevel=<?=$codecLevel?>&soundCodec=<?=$soundCodec?>&soundQuality=<?=$soundQuality?>&showCamSettings=<?=$showCamSettings?>&advancedCamSettings=<?=$advancedCamSettings?>&camMaxBandwidth=<?=$camMaxBandwidth?>&disableBandwidthDetection=<?=$disableBandwidthDetection?>&limitByBandwidth=<?=$limitByBandwidth?>&configureSource=<?=$configureSource?>&generateSnapshots=<?=$generateSnapshots?>&snapshotsTime=<?=$snapshotsTime?>&onlyVideo=<?=$onlyVideo?>&noVideo=<?=$noVideo?>&noEmbeds=<?=$noEmbeds?>&labelColor=<?=$labelColor?>&writeText=<?=$writeText?>&floodProtection=<?=$floodProtection?>&privateTextchat=<?=$privateTextchat?>&filterRegex=<?=$filterRegex?>&filterReplace=<?=$filterReplace?>&externalInterval=<?=$externalInterval?>&layoutCode=<?=urlencode($layoutCode)?>&fillWindow=<?=$fillWindow?>&verboseLevel=<?=$verboseLevel?>&loadstatus=1
