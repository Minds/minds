<?php

	// staff only action
	user_support_staff_gatekeeper();

	$guid = (int) get_input("guid");
	
	$user = elgg_get_logged_in_user_entity();

	if (!empty($guid) && ($entity = get_entity($guid))) {
		if (elgg_instanceof($entity, "object", UserSupportTicket::SUBTYPE, "UserSupportTicket")) {
			create_annotation($entity->getGUID(),
								'generic_comment',
								elgg_echo("user_support:support_ticket:reopened"),
								"",
								$user->getGUID(),
								$entity->access_id);
			
			if ($entity->setStatus(UserSupportTicket::OPEN)) {
				notify_user($entity->getOwnerGUID(),
					$user->getGUID(),
					elgg_echo('generic_comment:email:subject'),
					elgg_echo('generic_comment:email:body', array(
						$entity->title,
						$user->name,
						elgg_echo("user_support:support_ticket:reopened"),
						$entity->getURL(),
						$user->name,
						$user->getURL()
					))
				);
				
				system_message(elgg_echo("user_support:action:ticket:reopen:success"));
			} else {
				register_error(elgg_echo("user_support:action:ticket:reopen:error:enable"));
			}
		} else {
			register_error(elgg_echo("InvalidClassException:NotValidElggStar", array($guid, "UserSupportTicket")));
		}
	} else {
		register_error(elgg_echo("InvalidParameterException:MissingParameter"));
	}
	
	forward(REFERER);
