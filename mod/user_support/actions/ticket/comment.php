<?php

	/**
	 * Elgg add comment action
	*
	* @package Elgg.Core
	* @subpackage Comments
	*/
	
	$entity_guid = (int) get_input("entity_guid");
	$comment_text = get_input("generic_comment");
	
	if (empty($comment_text)) {
		register_error(elgg_echo("generic_comment:blank"));
		forward(REFERER);
	}
	
	// Let"s see if we can get an entity with the specified GUID
	$ia = elgg_set_ignore_access(true);
	$entity = get_entity($entity_guid);
	
	if (empty($entity) || !elgg_instanceof($entity, "object", UserSupportTicket::SUBTYPE) || !$entity->canEdit()) {
		elgg_set_ignore_access($ia);
		register_error(elgg_echo("generic_comment:notfound"));
		forward(REFERER);
	}
	
	$user = elgg_get_logged_in_user_entity();
	
	$annotation = create_annotation($entity->getGUID(),
			"generic_comment",
			$comment_text,
			"",
			$user->getGUID(),
			$entity->access_id);
	
	// tell user annotation posted
	if (!$annotation) {
		elgg_set_ignore_access($ia);
		register_error(elgg_echo("generic_comment:failure"));
		forward(REFERER);
	}
	
	// notify if poster wasn"t owner
	if ($entity->getObjectOwnerGUID() != $user->getGUID()) {
	
		notify_user($entity->getObjectOwnerGUID(),
				$user->getGUID(),
				elgg_echo("generic_comment:email:subject"),
				elgg_echo("generic_comment:email:body", array(
					$entity->title,
					$user->name,
					$comment_text,
					$entity->getURL(),
					$user->name,
					$user->getURL()
				))
		);
	}
	
	// some open/close checks
	if ($entity->getStatus() == UserSupportTicket::CLOSED) {
		$entity->setStatus(UserSupportTicket::OPEN);
	} elseif (get_input("submit") == elgg_echo("user_support:comment_close")) {
		$entity->setStatus(UserSupportTicket::CLOSED);
	}
	
	system_message(elgg_echo("generic_comment:posted"));
	
	//add to river
	add_to_river("river/annotation/generic_comment/create", "comment", $user->getGUID(), $entity->getGUID(), "", 0, $annotation);
	
	// restore access
	elgg_set_ignore_access($ia);
	
	// Forward to the page the action occurred on
	forward(REFERER);
