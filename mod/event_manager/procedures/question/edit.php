<?php 

	$result = array();
	
	$event_guid 	= get_input('event_guid');
	$question_guid 	= get_input('question_guid');
	
	$fieldtype 		= get_input("fieldtype");
	$fieldoptions 	= get_input("fieldoptions");
	$questiontext 	= get_input("questiontext");
	$required 		= get_input("required");
	
	$result['valid'] = 0;
	
	if($event_guid && ($event = get_entity($event_guid))) {
		if(($event->getSubtype() == Event::SUBTYPE) && ($event->canEdit())) {
			if($question_guid && ($question = get_entity($question_guid))) {
				if(!($question instanceof EventRegistrationQuestion)) {
					unset($question);
				}
				$result['edit'] = 1;
			} else {
				$result['edit'] = 0;
				$question = new EventRegistrationQuestion();
			}
			
			if($question && !empty($question)) {
				$question->title			= $questiontext;
				$question->container_guid	= $event->getGUID();
				$question->owner_guid		= $event->getGUID();
				$question->access_id		= $event->access_id;
				
				if($question->save()) {
					$question->fieldtype = $fieldtype;
					$question->required = $required;
					$question->fieldoptions = $fieldoptions;
					
					if($result['edit'] == 0) {
						$question->order = $event->getRegistrationFormQuestions(true);
					}
					
					$question->addRelationship($event->getGUID(), 'event_registrationquestion_relation');
					
					$result['valid'] = 1;
					$result['guid'] = $question->getGUID();
					
					$result['content'] = elgg_view("event_manager/registration/question", array("entity" => $question));
				}
			}
		}
	}
	
	echo json_encode($result);
	exit;