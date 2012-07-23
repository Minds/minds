<?php
	
	$guid = get_input("guid");
	$post = $_POST;
	$registrationFields = array();
	
	if(!empty($guid) && $registration = get_entity($guid))
	{
		if($registration->getSubtype() == EventRegistration::SUBTYPE)
		{
			foreach($post as $key => $value)
			{
				$questionId = substr($key, 8, strlen($key));
				if(substr($key, 0, 8) == 'question')
				{
					$registrationFields[] = $questionId.'|'.$value;
				}
			}
			
			$event = $registration->getEntitiesFromRelationship(EVENT_MANAGER_RELATION_USER_REGISTERED, true);
			
			$registration->clearAnnotations('answer');
			
			foreach($registrationFields as $answer)
			{
				$registration->annotate('answer', $answer, $event[0]->access_id);
			}
			
			system_message(elgg_echo("event_manager:action:event:edit:ok"));
			forward($event[0]->getURL());
		}
	}
	else
	{
		system_message(elgg_echo("noguid"));
		forward(REFERER);
	}