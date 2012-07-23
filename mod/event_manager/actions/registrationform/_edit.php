<?php
	
	$guid = get_input("guid");
	$post = $_POST;
	$questions = array();	
	
	if(!empty($guid) && $event = get_entity($guid))
	{
		if($event->getSubtype() == Event::SUBTYPE)
		{
			foreach($post as $field => $value)
			{
				if(substr($field, 0, 8) == 'question')
				{
					if($value != '')
					{
						$annotationId = substr($field, 8, strlen($field));
						$questions[$annotationId] = $value;
					}
				}	
			}
			
			if(count($questions) >0)
			{
				$questionsObject = $event->getEntitiesFromRelationship(EVENT_MANAGER_RELATION_REGISTRATION_QUESTION);
				if(!$questionsObject)
				{
					$newQuestionsObject = true;
					
					$questionsObject = new EventQuestions();
						$questionsObject->title = $event->title.':questions';
						$questionsObject->description = 'question_description';
						$questionsObject->access_id = $event->access_id;
						$questionsObject->save();
					
					$event->addRelationship($questionsObject->getGUID(), EVENT_MANAGER_RELATION_REGISTRATION_QUESTION);
				}
				else
				{
					$newQuestionsObject = false;
					
					$questionsObject = $questionsObject[0];
				}
			}
			else
			{
				register_error(elgg_echo("no questions posted"));
				forward(REFERER);
			}
			
			system_message(elgg_echo("event_manager:action:event:edit:ok"));
			forward($event->getURL());
		}
	}
	else
	{
		system_message(elgg_echo("noguid"));
		forward(REFERER);
	}