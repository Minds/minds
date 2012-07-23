<?php 

	class EventRegistrationForm extends ElggObject 
	{
		const SUBTYPE = "eventregistrationform";
		
		protected function initializeAttributes() 
		{
			parent::initializeAttributes();
			
			$this->attributes["subtype"] = self::SUBTYPE;
		}
		
		public function getQuestions()
		{
			return $this->getEntitiesFromRelationship('event_registration_questions');
		}
	}