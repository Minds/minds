<?php

/**
	* @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	*/


// Make sure we're logged in (send us to the front page if not)
if (!isloggedin()) forward();

$welcome = get_input('welcome');
$cam = explode("x", get_input('resolution')); 
$camBandwidth = get_input('camBandwidth');
$background_url = get_input('background_url');
$layoutCode = get_input('layoutCode');
$fillWindow = get_input('fillWindow');
$filterRegex = get_input('filterRegex');
$filterReplace = get_input('filterReplace');
$camfps = get_input('camfps');
$micrate = get_input('micrate');
$showCamSettings = get_input('showCamSettings');
$advancedCamSettings = get_input('advancedCamSettings');
$configureSource = get_input('configureSource');
$disableVideo = get_input('disableVideo');
$disableSound = get_input('disableSound');
$panelFiles = get_input('panelFiles');
$file_upload = get_input('file_upload');
$file_delete = get_input('file_delete');
$tutorial = get_input('tutorial');
$autoViewCams = get_input('autoViewCams');
$showTimer = get_input('showTimer');
$userList = get_input('userList');
$moderatorList = get_input('moderatorList');
$cleanUp = get_input('cleanUp');
$cleanUp = $cleanUp*3600;     // convert to second
$writeText = get_input('writeText');
$regularWatch = get_input('regularWatch');
$newWatch = get_input('newWatch');
$privateTextchat = get_input('privateTextchat');
$floodProtection = get_input('floodProtection');
$ztime = time();
$admin = get_input('admin');
$visitor = get_input('visitor');
$panelRooms = get_input('panelRooms');
$panelUsers = get_input('panelUsers');
$verboseLevel = get_input('verboseLevel');

// Get input data
$room = get_input('room');
$description = elgg_substr(strip_tags(get_input('description')), 0, 300)."^";
$description .= $welcome."^"; 
$description .= $cam[0]."^";
$description .= $cam[1]."^";
$description .= $camBandwidth."^"; 
$description .= $background_url."^"; 
$description .= $layoutCode."^"; 
$description .= $fillWindow."^"; 
$description .= $filterRegex."^"; 
$description .= $filterReplace."^"; 
$description .= $camfps."^";
$description .= $micrate."^"; 
$description .= $showCamSettings."^"; 
$description .= $advancedCamSettings."^"; 
$description .= $configureSource."^"; 
$description .= $disableVideo."^"; 
$description .= $disableSound."^"; 
$description .= $panelFiles."^"; 
$description .= $file_upload."^"; 
$description .= $file_delete."^"; 
$description .= $tutorial."^"; 
$description .= $autoViewCams."^"; 
$description .= $showTimer."^"; 
$description .= $userList."^"; 
$description .= $moderatorList."^"; 
$description .= $cleanUp."^";  
$description .= $writeText."^"; 
$description .= $regularWatch."^"; 
$description .= $newWatch."^"; 
$description .= $privateTextchat."^"; 
$description .= $floodProtection."^"; 
$description .= $visitor."^"; 
$description .= $admin."^"; 
$description .= $ztime."^";
$description .= $panelRooms."^"; 
$description .= $panelUsers."^"; 
$description .= $verboseLevel."^";
$description .= $ztime."^";

$access_id = get_input('access_id');

function roomName($title)
{
	return strtolower(preg_replace("/[^0-9a-zA-Z]+/","-", trim($title)));
}

$options = array();
$options['metadata_name_value_pairs'] = array('room' => roomName($room));
$options['types'] = 'object';
$options['subtypes'] = 'videoconference';
$erooms = elgg_get_entities_from_metadata($options);
if (count($erooms)&&$erooms)
{
	$eroom = $erooms[0];
	register_error(sprintf(elgg_echo("videoconference:room_exists"),roomName($room)));
	
	$ver=explode('.', get_version(true));			
	if ($ver[1]>7)
	forward("videoconference/add");
	else forward("pg/videoconference/add");
}

$params['description'] = $description;

// Make sure the title / description aren't blank
if (empty($room)) {
	register_error(elgg_echo("videoconference:blank"));
	
	$ver=explode('.', get_version(true));			
	if ($ver[1]>7)
	forward("videoconference/add");
	else forward("pg/videoconference/add");

	// Otherwise, save the  room
} else {

	if (!videoconference_save_room($room, $access_id, $params)) {
		register_error(elgg_echo("videoconference:error"));
		
		$ver=explode('.', get_version(true));			
		if ($ver[1]>7)
		forward("videoconference/add");
		else forward("pg/videoconference/add");
	}

	// vwconnect
	if (is_plugin_enabled (vwconnect)) {
		// twitter
		/* Load required lib files. */
		global $CONFIG;
		$vwconnect = 0;
		$link = $CONFIG->url."videoconference/".$room;
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
			$connection->post('statuses/update', array('status' => 'I just created this video conference room '.$link));

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
					'message' => 'I just created this video conference room '.$link
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
	system_message(elgg_echo("videoconference:created"));

	// Forward
	$ver=explode('.', get_version(true));			
	if ($ver[1]>7)
	forward("videoconference/all");
	elseif($location == "activity")
	forward("mod/riverdashboard/");
	else
	forward("pg/videoconference/all");
}

//create room
function videoconference_save_room($room, $access_id, $params = Array())
{

	global $SESSION;

	// Initialise a new ElggObject
	$videoconference = new ElggObject();

	// Tell the system it's a videoconference
	$videoconference->subtype = "videoconference";

	// Set its owner to the current user
	$videoconference->owner_guid = get_loggedin_userid();

	// For now, set its access to public (we'll add an access dropdown shortly)
	$videoconference->access_id = $access_id;

	// Set its title, description appropriately
	$videoconference->title = elgg_substr(strip_tags($room), 0, 32);
	$videoconference->description = $params['description'];
	$videoconference->room = roomName($videoconference->title);

	// add some metadata
	//$videoconference->method = "site"; //method, e.g. via site, api, sms, etc

	//save
	$save = $videoconference->save();

	if ($save)	add_to_river('river/object/videoconference/create','create',$SESSION['user']->guid,$videoconference->guid);

	return $save;

}


?>
