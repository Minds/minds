<?php

	$guid = get_input("event_guid");
 	$relation = get_input("relation");
	
	$register_type = get_input('register_type');
	
	$program_guids = get_input('program_guids');
	
	$answers = array();
	foreach($_POST as $key => $value)
	{		
		$value = get_input($key);
		if(substr($key, 0, 9) == 'question_')
		{
			if(is_array($value))
			{
				$value = $value[0];
			}
			
			$answers[substr($key, 9, strlen($key))] = $value;
		}
	}
	
	if(!empty($guid) && !empty($relation) && ($entity = get_entity($guid)))
	{
		if($entity instanceof Event)
		{
			$event = $entity;
			
			if($event)
			{	
				$user = elgg_get_logged_in_user_entity();
				
				$questions = $event->getRegistrationFormQuestions();
				foreach($questions as $question)
				{
					if($question->required && empty($answers[$question->getGUID()]))
					{
						$required_error = true;
					}
					
					if(!elgg_is_logged_in())
					{
						if(empty($answers['name']) || empty($answers['email']))
						{
							$required_error = true;
						}
					}
					
					$_SESSION['registerevent_values']['question_'.$question->getGUID()]	= $answers[$question->getGUID()];
				}
				
				if(empty($user)){
					$_SESSION['registerevent_values']['question_name']	= $answers["name"];
					$_SESSION['registerevent_values']['question_email']	= $answers["email"];
				}
				
				if($event->with_program && !$required_error)
				{
					if(empty($program_guids))
					{
						$required_error = true;
					}
				}
				
				if($required_error)
				{
					if($event->with_program)
					{
						if($questions)
						{
							register_error(elgg_echo("event_manager:action:registration:edit:error_fields_with_program"));
						}
						else
						{
							register_error(elgg_echo("event_manager:action:registration:edit:error_fields_program_only"));
						}
					}
					else
					{
						register_error(elgg_echo("event_manager:action:event:edit:error_fields"));
					}
					
					forward(REFERER);
				}
				else
				{
					$_SESSION['registerevent_values'] = null;
				}
				
				if(elgg_is_logged_in())
				{
					$object = elgg_get_logged_in_user_entity();
				}
				else
				{
					elgg_set_ignore_access(true);
					
					$object = new EventRegistration();
					$object->title = 'EventRegistrationNotLoggedinUser';
					$object->description = 'EventRegistrationNotLoggedinUser';
					$object->owner_guid = $event->getGUID();
					$object->container_guid = $event->getGUID();
					$object->access_id = ACCESS_PUBLIC;
					$object->save();
					
					elgg_set_ignore_access(false);
				}				
				
				foreach($answers as $question_guid => $answer)
				{
					if(!empty($question_guid) && ($question = get_entity($question_guid)))
					{
						if($question instanceof EventRegistrationQuestion)
						{
							$question->updateAnswerFromUser($event, $answer, $object->getGUID());
						}
					}
					else
					{
						$object->{$question_guid} = $answer;
					}
				}

				$guid_explode = explode(',', $program_guids);
				
				if(elgg_is_logged_in())
				{
					$event->relateToAllSlots(false);
				}
				
				foreach($guid_explode as $slot_guid)
				{
					if(!empty($slot_guid))
					{
						if($register_type == 'waitinglist')
						{
							$relation 		= EVENT_MANAGER_RELATION_ATTENDING_WAITINGLIST;
							$slot_relation 	= EVENT_MANAGER_RELATION_SLOT_REGISTRATION_WAITINGLIST;
						}
						else
						{
							$slot_relation = EVENT_MANAGER_RELATION_SLOT_REGISTRATION;
						}
						
						$object->addRelationship($slot_guid, $slot_relation);
					}
				}
			
				if($rsvp = $event->rsvp($relation, $object->getGUID()))
				{
					system_message(elgg_echo('event_manager:event:relationship:message:'.$relation));
				} 
				else
				{
					register_error(elgg_echo('event_manager:event:relationship:message:error'));
				}
				
				forward($event->getURL());
			}
			else
			{	
				register_error(elgg_echo("event_manager:event_not_found"));
				forward(REFERER);
			}
		}
	}
	else
	{
		system_message(elgg_echo("no guid"));
		forward(REFERER);
	}