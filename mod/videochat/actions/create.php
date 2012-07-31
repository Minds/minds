<?php

/**
	* @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	*/


// Make sure we're logged in (send us to the front page if not)
if (!isloggedin()) forward();

$welcome = get_input('welcome');
$cam = explode("x", get_input('resolution')); 
$camBandwidth = get_input('camBandwidth');
$filterRegex = get_input('filterRegex');
$filterReplace = get_input('filterReplace');
$camfps = get_input('camfps');
$micrate = get_input('micrate');
$showCamSettings = get_input('showCamSettings');
$configureSource = get_input('configureSource');
$disableVideo = get_input('disableVideo');
$disableSound = get_input('disableSound');
$showTimer = get_input('showTimer');
$userList = get_input('userList');
$moderatorList = get_input('moderatorList');
$cleanUp = get_input('cleanUp');
$cleanUp = $cleanUp*86400;     // convert to second
$disableEmoticons = get_input('disableEmoticons');
$showTextChat = get_input('showTextChat');
$sendTextChat = get_input('sendTextChat');
$enableP2P = get_input('enableP2P');
$enableServer = get_input('enableServer');
$configureConnection = get_input('configureConnection');
$enableNext = get_input('enableNext');
$enableBuzz = get_input('enableBuzz');
$enableSoundFx = get_input('enableSoundFx');
$requestSnapshot = get_input('requestSnapshot');
$autoSnapshots = get_input('autoSnapshots');
$visitor = get_input('visitor');
$verboseLevel = get_input('verboseLevel');
$ztime = time();
$camPicture = get_input('camPicture');
$enableButtonLabels = get_input('enableButtonLabels');
$enableFullscreen = get_input('enableFullscreen');
$enableSwap = get_input('enableSwap');
$enableLogout = get_input('enableLogout');
$enableLogo = get_input('enableLogo');
$enableHeaders = get_input('enableHeaders');
$enableTitles = get_input('enableTitles');
$videoW = get_input('videoW');
$videoH = get_input('videoH');
$video2W = get_input('video2W');
$video2H = get_input('video2H');
$layoutCode = get_input('layoutCode');
$chatTextColor = get_input('chatTextColor');

// Get input data
$room = get_input('room');
$description = elgg_substr(strip_tags(get_input('description')), 0, 300)."^";
$description .= $welcome."^"; 
$description .= $cam[0]."^";
$description .= $cam[1]."^";
$description .= $camBandwidth."^";
$description .= $filterRegex."^"; 
$description .= $filterReplace."^"; 
$description .= $camfps."^";
$description .= $micrate."^"; 
$description .= $showCamSettings."^";
$description .= $configureSource."^"; 
$description .= $disableVideo."^"; 
$description .= $disableSound."^"; 
$description .= $showTimer."^"; 
$description .= $userList."^"; 
$description .= $moderatorList."^"; 
$description .= $cleanUp."^";  
$description .= $disableEmoticons."^"; 
$description .= $showTextChat."^"; 
$description .= $sendTextChat."^";
$description .= $enableP2P."^"; 
$description .= $enableServer."^";
$description .= $configureConnection."^"; 
$description .= $enableNext."^"; 
$description .= $enableBuzz."^"; 
$description .= $enableSoundFx."^"; 
$description .= $requestSnapshot."^"; 
$description .= $autoSnapshots."^"; 
$description .= $visitor."^"; 
$description .= $verboseLevel."^"; 
$description .= $ztime."^";
$description .= $camPicture."^";
$description .= $enableButtonLabels."^";
$description .= $enableFullscreen."^";
$description .= $enableSwap."^";
$description .= $enableLogout."^";
$description .= $enableLogo."^";
$description .= $enableHeaders."^";
$description .= $enableTitles."^";
$description .= $videoW."^";
$description .= $videoH."^";
$description .= $video2W."^";
$description .= $video2H."^";
$description .= $layoutCode."^";
$description .= $chatTextColor."^";
$description .= $ztime."^";

$access_id = get_input('access_id');

function roomName($title)
{
	return strtolower(preg_replace("/[^0-9a-zA-Z]+/","-", trim($title)));
}

$options = array();
$options['metadata_name_value_pairs'] = array('room' => roomName($room));
$options['types'] = 'object';
$options['subtypes'] = 'videochat';
$erooms = elgg_get_entities_from_metadata($options);
if (count($erooms)&&$erooms)
{
	$eroom = $erooms[0];
	register_error(sprintf(elgg_echo("videochat:room_exists"), roomName($room)));
	
	$ver=explode('.', get_version(true));			
	if ($ver[1]>7)
	forward("videochat/add");
	else forward("pg/videochat/add");
}

$params['description'] = $description;

// Make sure the title / description aren't blank
if (empty($room)) {
	register_error(elgg_echo("videochat:blank"));
	
	$ver=explode('.', get_version(true));			
	if ($ver[1]>7)
	forward("videochat/add");
	else forward("pg/videochat/add");
	
	// Otherwise, save the  room
} else {

	if (!videochat_save_room($room, $access_id, $params)) {
		register_error(elgg_echo("videochat:error"));
  	$ver=explode('.', get_version(true));			
  	if ($ver[1]>7)
  	forward("videochat/add");
  	else forward("pg/videochat/add");
	}

	// vwconnect
	if (is_plugin_enabled (vwconnect)) {						
		// twitter
		/* Load required lib files. */
		global $CONFIG;
		$vwconnect = 0;
		$link = $CONFIG->url."videochat/".$room;
		// twitter
		/* Load required lib files. */
		$vwconnect = 0;
		if (is_dir('../../mod/vwconnect')) {
			session_start();
			require_once('../../mod/vwconnect/twitteroauth/twitteroauth.php');
			require_once('../../mod/vwconnect/config.php');
			$vwconnect = 2;
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
			$connection->post('statuses/update', array('status' => 'I just created this video chat room '.$link));

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

		if ($vwconnect == 2 && $lastpost_fb < $fb_exptime) {
			require '../../mod/vwconnect/src/facebook.php';
			
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
					'message' => 'I just created this video chat room '.$link
					)
					);
				} catch (FacebookApiException $e) {
					error_log($e);
					$user = null;
				}
			}
		}
	} // end vwconnect
	
	// Success message
	system_message(elgg_echo("videochat:created"));

	// Forward
	$ver=explode('.', get_version(true));			
	if ($ver[1]>7)
	forward("videochat/all");
	elseif($location == "activity")
	forward("mod/riverdashboard/");
	else
	forward("pg/videochat/all");
}

//create room
function videochat_save_room($room, $access_id, $params = Array())
{

	global $SESSION;

	// Initialise a new ElggObject
	$videochat = new ElggObject();

	// Tell the system it's a videochat
	$videochat->subtype = "videochat";

	// Set its owner to the current user
	$videochat->owner_guid = get_loggedin_userid();

	// For now, set its access to public (we'll add an access dropdown shortly)
	$videochat->access_id = $access_id;

	// Set its title, description appropriately
	$videochat->title = elgg_substr(strip_tags($room), 0, 32);
	$videochat->description = $params['description'];
	$videochat->room = roomName($videochat->title);

	// add some metadata
	//$videochat->method = "site"; //method, e.g. via site, api, sms, etc

	//save
	$save = $videochat->save();

	if ($save)	add_to_river('river/object/videochat/create','create',$SESSION['user']->guid,$videochat->guid);

	return $save;

}

?>
