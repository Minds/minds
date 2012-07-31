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
if ( (count($erooms) > 1) &&$erooms)
{
	$eroom = $erooms[0];
	register_error(sprintf(elgg_echo("livestreaming:room_exists"),roomName($room)));

  $guid = get_input('guid');
	forward("livestreaming/edit/".$guid);
}

$params['description'] = $description;

// Make sure the title / description aren't blank
if (empty($room)) {
	register_error(elgg_echo("livestreaming:blank"));
	
	forward("livestreaming/add");

	// Otherwise, save the  room
} else {

	if (!livestreaming_save_room($room, $access_id, $params)) {
		register_error(elgg_echo("livestreaming:error"));
		
		forward("livestreaming/add");
	}

	// Success message
	system_message(elgg_echo("livestreaming:saved"));

	// Forward
	if($location == "activity")
	forward("mod/riverdashboard/");
	else
	forward("livestreaming/all");	

}

//create room
function livestreaming_save_room($room, $access_id, $params = Array())
{
  $guid = get_input('guid');

	$livestreaming = get_entity($guid);

	// For now, set its access to public (we'll add an access dropdown shortly)
	$livestreaming->access_id = $access_id;

	// Set its title, description appropriately
	$livestreaming->title = elgg_substr(strip_tags($room), 0, 32);
	$livestreaming->description = $params['description'];
	$livestreaming->room = roomName($livestreaming->title);

	//save
	$save = $livestreaming->save();

	return $save;
}
?>
