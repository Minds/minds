<?php

$title = get_input('title');
$description = get_input('description');

$ticket = new MindsControlTicket();
$ticket->title = $title;
$ticket->description = $description;
$ticket->user_agent = json_encode($_SERVER['HTTP_USER_AGENT']);
$guid = $ticket->save();

if($guid){
	forward('control/tickets/owner');
}
	
