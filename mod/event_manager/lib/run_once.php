<?php 

	function event_manager_run_once_subtypes()	{
		add_subtype('object', Event::SUBTYPE, "Event");
		add_subtype('object', EventDay::SUBTYPE, "EventDay");
		add_subtype('object', EventSlot::SUBTYPE, "EventSlot");
		add_subtype('object', EventRegistrationForm::SUBTYPE, "EventRegistrationForm");
		add_subtype('object', EventRegistrationQuestion::SUBTYPE, "EventRegistrationQuestion");
		add_subtype('object', EventRegistration::SUBTYPE, "EventRegistration");
	}