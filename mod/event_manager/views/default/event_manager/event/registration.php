<?php

	$event = $vars['entity'];
	$user_relation = $event->getRelationshipByUser();
	
	if($user_relation && ($user_relation == EVENT_MANAGER_RELATION_ATTENDING) && $event->registration_needed && elgg_is_logged_in()) {
		if($event->isAttending()) {
			echo elgg_view("output/url", array("href" => EVENT_MANAGER_BASEURL . "/registration/view/" . $event->getGUID(), "text" => elgg_echo("event_manager:registration:viewyourregistration")));
		}	
	}