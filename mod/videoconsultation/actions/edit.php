<?php

/**
	* @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	*/


// Make sure we're logged in (send us to the front page if not)
if (!isloggedin()) forward();

$welcome = get_input('welcome');
$visitor = get_input('visitor');
$background_url = get_input('background_url');
$change_background = get_input('change_background');
$room_limit = get_input('room_limit');
$showTimer = get_input('showTimer');
$regularCams = get_input('regularCams');
$regularWatch = get_input('regularWatch');
$cam = explode("x", get_input('resolution'));
$camfps = get_input('camfps');
$micrate = get_input('micrate');
$camBandwidth = get_input('camBandwidth');
$showCamSettings = get_input('showCamSettings');
$advancedCamSettings = get_input('advancedCamSettings');
$configureSource = get_input('configureSource');
$disableVideo = get_input('disableVideo');
$disableSound = get_input('disableSound');
$files_enabled = get_input('files_enabled');
$file_upload = get_input('file_upload');
$file_delete = get_input('file_delete');
$chat_enabled = get_input('chat_enabled');
$floodProtection = get_input('floodProtection');
$writeText = get_input('writeText');
$privateTextchat = get_input('privateTextchat');
$externalStream = get_input('externalStream');
$slideShow = get_input('slideShow');
$users_enabled = get_input('users_enabled');
$publicVideosN = get_input('publicVideosN');
$publicVideosAdd = get_input('publicVideosAdd');
$publicVideosMax = get_input('publicVideosMax');
$publicVideosW = get_input('publicVideosW');
$publicVideosH = get_input('publicVideosH');
$publicVideosX = get_input('publicVideosX');
$publicVideosY = get_input('publicVideosY');
$publicVideosColumns = get_input('publicVideosColumns');
$publicVideosRows = get_input('publicVideosRows');
$autoplayServer = get_input('autoplayServer');
$autoplayStream = get_input('autoplayStream');
$layoutCode = get_input('layoutCode');
$fillWindow = get_input('fillWindow');
$filterRegex = get_input('filterRegex');
$filterReplace = get_input('filterReplace');
$userList = get_input('userList');
$moderatorList = get_input('moderatorList');
$cleanUp = get_input('cleanUp');
$cleanUp = $cleanUp*86400;     // convert to second
$admin = get_input('admin');
$changeName = get_input('changeName');
$ztime = time();
$verboseLevel = get_input('verboseLevel');

// Get input data
$room = get_input('room');
$description = elgg_substr(strip_tags(get_input('description')), 0, 300)."^";
$description .= $welcome."^"; 
$description .= $visitor."^";
$description .= $background_url."^";
$description .= $change_background."^"; 
$description .= $room_limit."^"; 
$description .= $showTimer."^"; 
$description .= $regularCams."^"; 
$description .= $regularWatch."^"; 
$description .= $cam[0]."^"; 
$description .= $cam[1]."^";
$description .= $camfps."^"; 
$description .= $micrate."^"; 
$description .= $camBandwidth."^"; 
$description .= $showCamSettings."^"; 
$description .= $advancedCamSettings."^"; 
$description .= $configureSource."^"; 
$description .= $disableVideo."^"; 
$description .= $disableSound."^"; 
$description .= $files_enabled."^"; 
$description .= $file_upload."^"; 
$description .= $file_delete."^"; 
$description .= $chat_enabled."^"; 
$description .= $floodProtection."^"; 
$description .= $writeText."^"; 
$description .= $privateTextchat."^";  
$description .= $externalStream."^"; 
$description .= $slideShow."^"; 
$description .= $users_enabled."^"; 
$description .= $publicVideosN."^"; 
$description .= $publicVideosAdd."^"; 
$description .= $publicVideosMax."^";
$description .= $publicVideosW."^"; 
$description .= $publicVideosH."^"; 
$description .= $publicVideosX."^";  
$description .= $publicVideosY."^"; 
$description .= $publicVideosColumns."^"; 
$description .= $publicVideosRows."^"; 
$description .= $autoplayServer."^"; 
$description .= $autoplayStream."^"; 
$description .= $layoutCode."^";
$description .= $fillWindow."^"; 
$description .= $filterRegex."^"; 
$description .= $filterReplace."^"; 
$description .= $userList."^"; 
$description .= $moderatorList."^"; 
$description .= $cleanUp."^"; 
$description .= $changeName."^"; 
$description .= $admin."^"; 
$description .= $ztime."^"; 
$description .= $verboseLevel."^";

$access_id = get_input('access_id');


function roomName($title)
{
	return strtolower(preg_replace("/[^0-9a-zA-Z]+/","-", trim($title)));
}

$options = array();
$options['metadata_name_value_pairs'] = array('room' => roomName($room));
$options['types'] = 'object';
$options['subtypes'] = 'videoconsultation';
$erooms = elgg_get_entities_from_metadata($options);
if ( (count($erooms) > 1) &&$erooms)
{
	$eroom = $erooms[0];
	register_error(sprintf(elgg_echo("videoconsultation:room_exists"),roomName($room)));

  $guid = get_input('guid');
	forward("videoconsultation/edit/".$guid);
}

$params['description'] = $description;

// Make sure the title / description aren't blank
if (empty($room)) {
	register_error(elgg_echo("videoconsultation:blank"));
	
	forward("videoconsultation/add");

	// Otherwise, save the  room
} else {

	if (!videoconsultation_save_room($room, $access_id, $params)) {
		register_error(elgg_echo("videoconsultation:error"));
		
		forward("videoconsultation/add");
	}

	// Success message
	system_message(elgg_echo("videoconsultation:saved"));

	// Forward
	if($location == "activity")
	forward("mod/riverdashboard/");
	else
	forward("videoconsultation/all");	

}

//create room
function videoconsultation_save_room($room, $access_id, $params = Array())
{
  $guid = get_input('guid');

	$videoconsultation = get_entity($guid);

	// For now, set its access to public (we'll add an access dropdown shortly)
	$videoconsultation->access_id = $access_id;

	// Set its title, description appropriately
	$videoconsultation->title = elgg_substr(strip_tags($room), 0, 32);
	$videoconsultation->description = $params['description'];
	$videoconsultation->room = roomName($videoconsultation->title);

	//save
	$save = $videoconsultation->save();

	return $save;
}
?>
