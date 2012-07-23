<?php

  // Load Elgg engine
  //require_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");
		
$user = elgg_get_logged_in_user_entity();

// get a list of all apps
$apps = elgg_get_entities(array('types' => 'object', 'subtypes' => 'oauthconsumer')); 

// sort out the apps into server and client
$sApps = array();
$cApps = array();
foreach ($apps as $app) {
	if ($app->consumer_type == 'outbound') { 
		$sApps[] = $app;
	} else {
		$cApps[] = $app;
	}
}


// existing applications

$area2 .= elgg_view_title(elgg_echo('oauth:register:inbound'));

if ($cApps) {
	$text = elgg_echo('oauth:register:inbound:desc');
		
	$text .= elgg_view('oauth/registerform');

	$area2 .= $text;
		
	$tokList = elgg_view_entity_list($cApps, array(
	    'count' => count($cApps),
	    'offset' => 0,
	    'limit' => 0,
	    'full_view' => true,
	    'list_type' => true,
	    'pagination' => false));
	$area2 .= $tokList;
} else {
	$text = elgg_echo('oauth:register:inbound:none');

	$text .= elgg_view('oauth/registerform');

	$area2 .= $text;
}


$area2 .= elgg_view_title(elgg_echo('oauth:register:outbound'));

if ($sApps) {
	$text = elgg_echo('oauth:register:outbound:desc');
		
	$area2 .= elgg_view('page/elements/wrapper', array('body' => $text));
		
	$tokList = elgg_view_entity_list($sApps, array(
	    'count' => count($sApps),
	    'offset' => 0,
	    'limit' => 0,
	    'full_view' => true,
	    'list_type' => true,
	    'pagination' => false));
		
	$area2 .= $tokList;
} else {
	$text = elgg_echo('oauth:register:outbound:none');
	
	$area2 .= $text;
}
			  
			  


echo $area2;


			  
