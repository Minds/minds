<?php

$ticket = get_entity(get_input('guid'));

if($ticket->canEdit()){
	$ticket->delete();
}

forward('/control/tickets/owner');
