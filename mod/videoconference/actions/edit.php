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
if ( (count($erooms) > 1) &&$erooms)
{
	$eroom = $erooms[0];
	register_error(sprintf(elgg_echo("videoconference:room_exists"),roomName($room)));

  $guid = get_input('guid');
	forward("videoconference/edit/".$guid);
}

$params['description'] = $description;

// Make sure the title / description aren't blank
if (empty($room)) {
	register_error(elgg_echo("videoconference:blank"));
	
	forward("videoconference/add");

	// Otherwise, save the  room
} else {

	if (!videoconference_save_room($room, $access_id, $params)) {
		register_error(elgg_echo("videoconference:error"));
		
		forward("videoconference/add");
	}

	// Success message
	system_message(elgg_echo("videoconference:saved"));

	// Forward
	if($location == "activity")
	forward("mod/riverdashboard/");
	else
	forward("videoconference/all");
	
	

}

//create room
function videoconference_save_room($room, $access_id, $params = Array())
{
  $guid = get_input('guid');

	$videoconference = get_entity($guid);

	// For now, set its access to public (we'll add an access dropdown shortly)
	$videoconference->access_id = $access_id;

	// Set its title, description appropriately
	$videoconference->title = elgg_substr(strip_tags($room), 0, 32);
	$videoconference->description = $params['description'];
	$videoconference->room = roomName($videoconference->title);

	//save
	$save = $videoconference->save();

	return $save;
}
?>
