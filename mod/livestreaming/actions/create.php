<?php

// Make sure we're logged in (send us to the front page if not)
if (!isloggedin()) forward();

$welcome = get_input('welcome');
$room_limit = get_input('room_limit');
$showTimer = get_input('showTimer');
$cam = explode("x", get_input('resolution')); 
$camfps = get_input('camfps');
$micrate = get_input('micrate');
$camBandwidth = get_input('camBandwidth');
$showCamSettings = get_input('showCamSettings');
$advancedCamSettings = get_input('advancedCamSettings');
$configureSource = get_input('configureSource');
$onlyVideo = get_input('onlyVideo');
$noVideo = get_input('noVideo');
$noEmbeds = get_input('noEmbeds');
$labelColor = get_input('labelColor');
$writeText = get_input('writeText');
$floodProtection = get_input('floodProtection');
$welcome2 = get_input('welcome2');
$offlineMessage = get_input('offlineMessage');
$floodProtection2 = get_input('floodProtection2');
$filterRegex = get_input('filterRegex');
$filterReplace = get_input('filterReplace');
$layoutCode = get_input('layoutCode');
$fillWindow = get_input('fillWindow');
$writeText2 = get_input('writeText2');
$disableVideo = get_input('disableVideo');
$disableChat = get_input('disableChat');
$disableUsers = get_input('disableUsers');
$visitor = get_input('visitor');
$userList = get_input('userList');
$moderatorList = get_input('moderatorList');
$cleanUp = get_input('cleanUp');
$changeName = get_input('changeName');
$cleanUp = $cleanUp*86400;
$ztime = time();
$layoutCode2 = get_input('layoutCode2');
$fillWindow2 = get_input('fillWindow2');
$verboseLevel = get_input('verboseLevel');
$verboseLevel2 = get_input('verboseLevel2');
$privateTextchat = get_input('privateTextchat');
$privateTextchat2 = get_input('privateTextchat2');
$soundQuality = get_input('soundQuality');
$overLogo = get_input('overLogo');
$overLink = get_input('overLink');

// Get input data
$room = get_input('room');
$description = elgg_substr(strip_tags(get_input('description')), 0, 300)."^";
$description .= $welcome."^"; 
$description .= $cam[0]."^";
$description .= $cam[1]."^";
$description .= $room_limit."^"; 
$description .= $showTimer."^"; 
$description .= $camfps."^";
$description .= $micrate."^"; 
$description .= $camBandwidth."^"; 
$description .= $showCamSettings."^"; 
$description .= $advancedCamSettings."^"; 
$description .= $configureSource."^"; 
$description .= $onlyVideo."^"; 
$description .= $noVideo."^"; 
$description .= $noEmbeds."^"; 
$description .= $labelColor."^"; 
$description .= $writeText."^"; 
$description .= $floodProtection."^"; 
$description .= $welcome2."^";
$description .= $offlineMessage."^";
$description .= $floodProtection2."^";
$description .= $filterRegex."^";
$description .= $filterReplace."^";
$description .= $layoutCode."^";
$description .= $fillWindow."^";
$description .= $writeText2."^";
$description .= $disableVideo."^";
$description .= $disableChat."^";
$description .= $disableUsers."^";
$description .= $visitor."^";
$description .= $userList."^"; 
$description .= $moderatorList."^"; 
$description .= $cleanUp."^";  
$description .= $changeName."^";
$description .= $ztime."^";
$description .= $layoutCode2."^";
$description .= $fillWindow2."^";
$description .= $verboseLevel."^";
$description .= $verboseLevel2."^";
$description .= $privateTextchat."^";
$description .= $privateTextchat2."^";
$description .= $soundQuality."^";
$description .= $overLogo."^";
$description .= $overLink."^";
$description .= $ztime."^";

$access_id = get_input('access_id');


function roomName($title)
{
	return strtolower(preg_replace("/[^0-9a-zA-Z]+/","-", trim($title)));
}

$options = array();
$options['metadata_name_value_pairs'] = array('room' => roomName($room));
$options['types'] = 'object';
$options['subtypes'] = 'livestreaming';
$erooms = elgg_get_entities_from_metadata($options);
if (count($erooms)&&$erooms)
{
	$eroom = $erooms[0];
	register_error(sprintf(elgg_echo("livestreaming:room_exists"),roomName($room)));

	$ver=explode('.', get_version(true));			
	if ($ver[1]>7)
	forward("livestreaming/add");
	else forward("pg/livestreaming/add");
}

$params['description'] = $description;

// Make sure the title / description aren't blank
if (empty($room)) {
	register_error(elgg_echo("livestreaming:blank"));

	$ver=explode('.', get_version(true));			
	if ($ver[1]>7)
	forward("livestreaming/add");
	else forward("pg/livestreaming/add");

	// Otherwise, save the  room
} else {

	if (!livestreaming_save_room($room, $access_id, $params)) {
		register_error(elgg_echo("livestreaming:error"));
  	$ver=explode('.', get_version(true));			
  	if ($ver[1]>7)
  	forward("livestreaming/add");
  	else forward("pg/livestreaming/add");
	}

	// vwconnect
	if (is_plugin_enabled (vwconnect)) {			
		// twitter
		/* Load required lib files. */
		global $CONFIG;
		$vwconnect = 0;
		$link2 = $CONFIG->url."livestreaming/".$room."?live=2";
		$link3 = $CONFIG->url."livestreaming/".$room."?live=3";
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
			$connection->post('statuses/update', array('status' => 'I just created this rooms '.$link2.' (live watch), '.$link3.' (live video)'));

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
					'message' => 'I just created this room '.$link2.' (live watch), '.$link3.' (live video)'
					)
					);
				} catch (FacebookApiException $e) {
					error_log($e);
					$user = null;
				}
			}
		}
	}
	
	// Success message
	system_message(elgg_echo("livestreaming:created"));

	// Forward
	$ver=explode('.', get_version(true));			
	if ($ver[1]>7)
	forward("livestreaming/all");
	elseif($location == "activity")
	forward("mod/riverdashboard/");
	else
	forward("pg/livestreaming/all");

}

//create room
function livestreaming_save_room($room, $access_id, $params = Array())
{

	global $SESSION;

	// Initialise a new ElggObject
	$livestreaming = new ElggObject();

	// Tell the system it's a livestreaming
	$livestreaming->subtype = "livestreaming";

	// Set its owner to the current user
	$livestreaming->owner_guid = get_loggedin_userid();

	// For now, set its access to public (we'll add an access dropdown shortly)
	$livestreaming->access_id = $access_id;

	// Set its title, description appropriately
	$livestreaming->title = elgg_substr(strip_tags($room), 0, 32);
	$livestreaming->description = $params['description'];
	$livestreaming->room = roomName($livestreaming->title);

	// add some metadata
	//$livestreaming->method = "site"; //method, e.g. via site, api, sms, etc

	//save
	$save = $livestreaming->save();

	if ($save)	add_to_river('river/object/livestreaming/create','create',$SESSION['user']->guid,$livestreaming->guid);

	return $save;

}


?>
