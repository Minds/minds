<?php 

	class EventRegistrationQuestion extends ElggObject 
	{
		const SUBTYPE = "eventregistrationquestion";
		
		protected function initializeAttributes() 
		{
			parent::initializeAttributes();
			
			$this->attributes["subtype"] = self::SUBTYPE;
		}
		
		public function getAllAnswers()
		{
			$result = false;
			
			$params = array(
				"guid" => $this->getGUID(),
				"annotation_name" => "answer_to_event_registration",
				"limit" => false
			);
			
			if($annotations = elgg_get_annotations($params))
			{
				$result = $annotations;
			}
			
			return $result;
		}
		
		public function getAnswerFromUser($user_guid = null)
		{
			$result = false;
			
			if(empty($user_guid))
			{
				$user_guid = elgg_get_logged_in_user_guid();
			}
			
			$params = array(
				"guid" => $this->getGUID(),
				"annotation_name" => "answer_to_event_registration",
				"annotation_owner_guid" => $user_guid,
				"limit" => 1
			);
			
			if($annotations = elgg_get_annotations($params))
			{
				$result = $annotations[0];
			}
			
			return $result;
		}
		
		public function deleteAnswerFromUser($user_guid = null)
		{			
			if(empty($user_guid))
			{
				$user_guid = elgg_get_logged_in_user_guid();
			}
			
			if($annotation = $this->getAnswerFromUser($user_guid))
			{
				$annotation->delete();
			}
		}
		
		public function updateAnswerFromUser($event, $new_answer, $user_guid = null)
		{	
			if(empty($user_guid))
			{
				$user_guid = elgg_get_logged_in_user_guid();
			}
			
			if(($old_answer = $this->getAnswerFromUser($user_guid)) && ($user = get_user($user_guid)))
			{
				if(!empty($new_answer))
				{
					update_annotation($old_answer->id, 'answer_to_event_registration', $new_answer, '', $user_guid, $event->access_id);
				}
				else
				{
					delete_annotation($old_answer->id);
				}
			}
			else
			{
				
				$this->annotate('answer_to_event_registration', $new_answer, $event->access_id, $user_guid);
			}
		}
		
		public function getOptions()
		{
			$field_options = array();
			
			if(!empty($this->fieldoptions))
			{
				$field_options = string_to_tag_array($this->fieldoptions);
			}
			
			return $field_options;
		}
	}

	