<?php

	$guid = (int) get_input("guid", 0);
	
	$forward_url = REFERER;
	
	if(!empty($guid)){
		if (($entity = get_entity($guid)) && $entity->canEdit()) {
			$container = $entity->getContainerEntity();
			
			if(elgg_instanceof($entity, "object", UserSupportFAQ::SUBTYPE, "UserSupportFAQ")){
				if($entity->delete()){
					
					if (elgg_instanceof($container, "group")) {
						$forward_url = "user_support/faq/group/" . $container->getGUID() . "/all";
					} else {
						$forward_url = "user_support/faq";
					}
					
					system_message(elgg_echo("user_support:action:faq:delete:success"));
				} else {
					register_error(elgg_echo("user_support:action:faq:delete:error:delete"));
				}
			} else {
				register_error(elgg_echo("InvalidClassException:NotValidElggStar", array($guid, "UserSupportFAQ")));
			}
		} else {
			register_error(elgg_echo("InvalidParameterException:NoEntityFound"));
		}
	} else {
		register_error(elgg_echo("InvalidParameterException:MissingParameter"));
	}

	forward($forward_url);
	