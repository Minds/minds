<?php

	elgg_make_sticky_form("user_support_faq");

	$guid = (int) get_input("guid", 0);
	$container_guid = (int) get_input("container_guid", 0);
	$title = get_input("title");
	$desc = get_input("description");
	$access_id = (int) get_input("access_id", ACCESS_PRIVATE);
	$tags = string_to_tag_array(get_input("tags"));
	$comments = get_input("allow_comments");
	$help_context = get_input("help_context");

	$forward_url = REFERER;
	
	if (!empty($title) && !empty($desc)) {
		if (!empty($guid)) {
			if (($entity = get_entity($guid)) && $entity->canEdit()) {
				if (!elgg_instanceof($entity, "object", UserSupportFAQ::SUBTYPE, "UserSupportFAQ")) {
					$entity = null;
					register_error(elgg_echo("InvalidClassException:NotValidElggStar", array($guid, "UserSupportFAQ")));
				}
			} else {
				$entity = null;
				register_error(elgg_echo("InvalidParameterException:NoEntityFound"));
			}
		} else {
			$container = get_entity($container_guid);
			
			$entity = new UserSupportFAQ();
			$entity->container_guid = $container_guid;
			
			if (elgg_instanceof($container, "group")) {
				$entity->owner_guid = elgg_get_logged_in_user_guid();
			}
			
			if (!$entity->save()) {
				$entity = null;
				register_error(elgg_echo("IOException:UnableToSaveNew", array("UserSupportFAQ")));
			}
		}
		
		if (!empty($entity)) {
			$entity->title = $title;
			$entity->description = $desc;
			$entity->access_id = $access_id;
			
			$entity->tags = $tags;
			$entity->allow_comments = $comments;
			
			if (elgg_is_admin_logged_in()) {
				$entity->help_context = $help_context;
			}
			
			if ($entity->save()) {
				elgg_clear_sticky_form("user_support_faq");
				
				$forward_url = $entity->getURL();
				system_message(elgg_echo("user_support:action:faq:edit:success"));
			} else {
				register_error(elgg_echo("user_support:action:faq:edit:error:save"));
			}
		}
	} else {
		register_error(elgg_echo("user_support:action:faq:edit:error:input"));
	}

	forward($forward_url);
