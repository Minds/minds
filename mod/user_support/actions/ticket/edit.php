<?php

	$guid = (int) get_input("guid");
	$title = get_input("title");
	$help_url = get_input("help_url");
	$help_context = get_input("help_context");
	$tags = get_input("tags");
	$support_type = get_input("support_type");
	$elgg_xhr = get_input("elgg_xhr");
	
	$forward_url = REFERER;
	
	$loggedin_user = elgg_get_logged_in_user_entity();
	
	if (!empty($title) && !empty($support_type)) {
		if (!empty($guid)) {
			if ($ticket = get_entity($guid)) {
				if (!elgg_instanceof($ticket, "object", UserSupportTicket::SUBTYPE, "UserSupportTicket")) {
					register_error(elgg_echo("InvalidClassException:NotValidElggStar", array($guid, "UserSupportTicket")));
					unset($ticket);
				}
			}
		} else {
			$ticket = new UserSupportTicket();
			
			$ticket->title = elgg_get_excerpt($title, 50);
			$ticket->description = $title;
			
			if (!$ticket->save()) {
				register_error(elgg_echo("IOException:UnableToSaveNew", array("UserSupportTicket")));
				unset($ticket);
			}
		}
		
		if (!empty($ticket)) {
			$ticket->title = elgg_get_excerpt($title, 50);
			$ticket->description = $title;
			
			$ticket->help_url = $help_url;
			$ticket->help_context = $help_context;
			$ticket->tags = $tags;
			$ticket->support_type = $support_type;
			
			if ($ticket->save()) {
				if (!empty($guid)) {
					$forward_url = $ticket->getURL();
				} elseif (empty($elgg_xhr)) {
					$forward_url = "user_support/support_ticket/owner/" . $loggedin_user->username;
				}
				system_message(elgg_echo("user_support:action:ticket:edit:success"));
			} else {
				register_error(elgg_echo("user_support:action:ticket:edit:error:save"));
			}
		}
	} else {
		register_error(elgg_echo("user_support:action:ticket:edit:error:input"));
	}

	forward($forward_url);
