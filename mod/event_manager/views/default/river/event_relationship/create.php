<?php

	$user = get_entity($vars['item']->subject_guid);
	$event = get_entity($vars['item']->object_guid);
	
	$subject_url = "<a href=\"{$user->getURL()}\">{$user->name}</a>";
	$event_url = "<a href=\"" . $event->getURL() . "\">" . $event->title . "</a>";
	
	$relationtype = $event->getRelationshipByUser($user->getGUID()); 
	
	$string = elgg_echo("event_manager:river:event_relationship:create:" . $relationtype, array($subject_url, $event_url));
	
	echo elgg_view('river/item', array(
		'item' => $vars['item'],
		"summary" => $string
	));