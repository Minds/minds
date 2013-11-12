<?php 

	$guid = (int) get_input("guid", 0);
	
	if(!empty($guid) && ($entity = get_entity($guid))){
		if(elgg_instanceof($entity, "object", UserSupportHelp::SUBTYPE, "UserSupportHelp")){
			if($entity->delete()){
				system_message(elgg_echo("user_support:action:help:delete:success"));
			} else {
				register_error(elgg_echo("user_support:action:help:delete:error:delete"));
			}
		} else {
			register_error("InvalidClassException:NotValidElggStar", array($guid, "UserSupportHelp"));	
		}
	} else {
		register_error(elgg_echo("InvalidParameterException:MissingParameter"));
	}

	forward(REFERER);
