<?php

	function user_support_create_annotation_event($event, $type, $annotation){
		
		if(!empty($annotation) && ($annotation instanceof ElggAnnotation)){
			$entity = $annotation->getEntity();
			$user = $annotation->getOwnerEntity();
			
			if(!empty($entity) && elgg_instanceof($entity, "object", UserSupportTicket::SUBTYPE, "UserSupportTicket") && !empty($user)){
				if($user->getGUID() == $entity->getOwnerGUID()){
					// user is the owner, so reopen
					$entity->setStatus(UserSupportTicket::OPEN);
					
					// notify admins about update
					if($admins = user_support_get_admin_notify_users($entity)){
						$subject = elgg_echo("user_support:notify:admin:updated:subject");
						$message = elgg_echo("user_support:notify:admin:updated:message", array(
											$entity->getOwnerEntity()->name,
											$entity->title,
											$annotation->value,
											$entity->getURL()
						));
						
						foreach($admins as $admin){
							notify_user($admin->getGUID(), $entity->getOwnerGUID(), $subject, $message);
						}
					}
				}
				
				$entity->save();
			}
		}
	}

	function user_support_create_object_event($event, $type, $object){
		global $CONFIG;
		
		if(!empty($object) && elgg_instanceof($object, "object", UserSupportTicket::SUBTYPE, "UserSupportTicket")){
			
			if($users = user_support_get_admin_notify_users($object)){
				$subject = elgg_echo("user_support:notify:admin:create:subject");
				$message = elgg_echo("user_support:notify:admin:create:message", array(
										$object->getOwnerEntity()->name,
										$object->description,
										$object->getURL()
				));
				
				foreach($users as $user){
					notify_user($user->getGUID(), $object->getOwnerGUID(), $subject, $message);
				}
			}
		}
	}
	