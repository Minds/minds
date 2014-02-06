<?php

$title = get_input('title');
$description = get_input('description');

$ticket = new MindsControlTicket();
$ticket->title = $title;
$ticket->description = $description;
$guid = $ticket->save();

if($guid){
	forward('control/tickets/owner');
}
	
