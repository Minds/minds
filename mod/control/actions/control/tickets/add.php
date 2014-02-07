<?php

$title = get_input('title');
$description = get_input('description');

elgg_set_ignore_access();

if(!elgg_is_logged_in()){
	$owner_guid = get_user_by_username('mark')->guid;
	$forward = '/';
}

$ticket = new MindsControlTicket();
$ticket->title = $title;
$ticket->description = $description;
$ticket->user_agent = json_encode($_SERVER['HTTP_USER_AGENT']);
$guid = $ticket->save();

if($guid){
	if(!isset($forward)){
		forward('control/tickets/owner');
	} else {
		forward($forward);
	}
}
	
