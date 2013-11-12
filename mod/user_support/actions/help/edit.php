<?php 
	
	$guid = (int) get_input("guid");
	$desc = get_input("description");
	$help_context = get_input("help_context");
	$tags = string_to_tag_array(get_input("tags"));
	
	if(!empty($desc) && !empty($help_context)){
		if(!empty($guid)){
			if($help = get_entity($guid)){
				if(!elgg_instanceof($help, "object", UserSupportHelp::SUBTYPE, "UserSupportHelp")){
					register_error("InvalidClassException:NotValidElggStar", array($guid, "UserSupportHelp"));	
					unset($help);
				}
			}
		} else {
			$help = new UserSupportHelp();
			
			if(!$help->save()){
				register_error(elgg_echo("IOException:UnableToSaveNew", array("UserSupportHelp")));
				unset($help);
			}
		}
		
		if(!empty($help)){
			$help->description = $desc;
			$help->tags = $tags;
			$help->help_context = $help_context;
			
			if($help->save()){
				system_message(elgg_echo("user_support:action:help:edit:success"));
			} else {
				register_error(elgg_echo("user_support:action:help:edit:error:save"));
			}
		}
	} else {
		register_error(elgg_echo("user_support:action:help:edit:error:input"));
	}

	forward(REFERER);
	