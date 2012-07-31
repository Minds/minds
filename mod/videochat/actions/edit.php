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
if ( (count($erooms) > 1) &&$erooms)
{
	$eroom = $erooms[0];
	register_error(sprintf(elgg_echo("videochat:room_exists"),roomName($room)));

  $guid = get_input('guid');
	forward("videochat/edit/".$guid);
}

$params['description'] = $description;

// Make sure the title / description aren't blank
if (empty($room)) {
	register_error(elgg_echo("videochat:blank"));
	
	forward("videochat/add");

	// Otherwise, save the  room
} else {

	if (!videochat_save_room($room, $access_id, $params)) {
		register_error(elgg_echo("videochat:error"));
		
		forward("videochat/add");
	}

	// Success message
	system_message(elgg_echo("videochat:saved"));

	// Forward
	if($location == "activity")
	forward("mod/riverdashboard/");
	else
	forward("videochat/all");	

}

//create room
function videochat_save_room($room, $access_id, $params = Array())
{
  $guid = get_input('guid');

	$videochat = get_entity($guid);

	// For now, set its access to public (we'll add an access dropdown shortly)
	$videochat->access_id = $access_id;

	// Set its title, description appropriately
	$videochat->title = elgg_substr(strip_tags($room), 0, 32);
	$videochat->description = $params['description'];
	$videochat->room = roomName($videochat->title);

	//save
	$save = $videochat->save();

	return $save;
}
?>
